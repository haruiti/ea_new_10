<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController extends Controller
{
    /**
     * Exibe a lista de leads
     */
    public function index()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();
        return view('leads.index', compact('leads'));
    }

    /**
     * Mostra o formulário de criação de lead
     */
    public function create()
    {
        return view('leads.create');
    }

    /**
     * Armazena um novo lead no banco de dados
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255',
            'phone'  => 'nullable|string|max:30',
            'source' => 'nullable|string|max:100',
            'notes'  => 'nullable|string',
        ]);

        $lead = Lead::create($validated);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead criado com sucesso!');
    }

    public function apiStore(Request $request)
    {

        // Cabeçalhos CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Se for uma requisição OPTIONS (pré-flight)
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'message'    => 'nullable|string',
            'lead_code'  => 'nullable|string|max:20',
        ]);

        $lead = Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'notes' => $validated['message'] ?? null,
            'source' => 'landing_page',
            'code' => $validated['lead_code'] ?? null,
        ]);

        // Aqui é onde o bot pode ser acionado (exemplo)
        // event(new NewLeadCreated($lead));

        return response()->json([
            'success' => true,
            'message' => 'Lead criado com sucesso!',
            'data' => $lead,
        ]);
    }


    /**
     * Exibe os detalhes de um lead
     */
    public function show($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.show', compact('lead'));
    }

    /**
     * Mostra o formulário de edição de um lead existente
     */
    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.edit', compact('lead'));
    }

    /**
     * Atualiza os dados de um lead
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255',
            'phone'  => 'nullable|string|max:30',
            'source' => 'nullable|string|max:100',
            'status' => 'required|in:novo,em_contato,agendado,convertido,perdido',
            'notes'  => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update($validated);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead atualizado com sucesso!');
    }

    /**
     * Exclui um lead
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->route('leads.index')
                         ->with('success', 'Lead excluído com sucesso!');
    }

    // =========================================================
    // 📱 API — Usado pelo Bot do WhatsApp
    // =========================================================
    public function obterOuCriar(Request $request)
    {
        $phone = preg_replace('/\D/', '', $request->phone); // remove caracteres não numéricos

        if (!$phone) {
            return response()->json(['error' => 'Número de telefone inválido.'], 400);
        }

        // Busca lead existente
        $lead = Lead::where('phone', $phone)->first();

        // Cria caso não exista
        if (!$lead) {
            $lead = Lead::create([
                'name'   => $request->name ?? 'Cliente WhatsApp',
                'phone'  => $phone,
                'source' => 'WhatsApp Bot',
                'status' => 'novo',
            ]);
        }

        return response()->json([
            'lead' => [
                'id'    => $lead->id,
                'name'  => $lead->name,
                'phone' => $lead->phone,
                'status'=> $lead->status,
            ]
        ]);
    }

    public function porNumero($numero)
    {
        // Remove tudo que não for número
        $numeroLimpo = preg_replace('/\D/', '', $numero);

        // Busca qualquer lead cujo número (também limpo) contenha a sequência
        $lead = Lead::whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') LIKE ?", ["%{$numeroLimpo}%"])
                    ->first();

        if (!$lead) {
            return response()->json(['error' => 'Lead não encontrado'], 404);
        }

        return response()->json($lead);
    }


    public function corrigirNomesImportados()
    {
        $leads = Lead::where('name', 'Lead importado')->get();
        $corrigidos = 0;

        foreach ($leads as $lead) {
            $primeiraConversa = $lead->conversas()->orderBy('created_at', 'asc')->first();
            if (!$primeiraConversa) continue;

            $nome = $this->extrairNomePossivel($primeiraConversa->mensagem, $primeiraConversa->dados_extras['chatName'] ?? '');
            if ($nome && $nome !== 'Lead importado') {
                $lead->name = $nome;
                $lead->save();
                $corrigidos++;
            }
        }

        return response()->json([
            'status' => 'ok',
            'corrigidos' => $corrigidos,
        ]);
    }

    private function extrairNomePossivel($mensagem, $chatName)
    {
        // 1️⃣ Usa chatName se não for número
        if ($chatName && !preg_match('/^\+?\d+$/', $chatName)) {
            return trim($chatName);
        }

        // 2️⃣ Tenta achar nome no texto
        if (preg_match('/(?:sou|meu nome é|aqui é|quem fala é)\s+([A-Za-zÀ-ÖØ-öø-ÿ\s]+)/i', $mensagem, $m)) {
            return trim($m[1]);
        }

        return 'Lead importado';
    }

    public function updateName(Request $request)
    {
        $lead = Lead::where('phone', $request->phone)->first();

        if (!$lead) {
            return response()->json(['error' => 'Lead não encontrado'], 404);
        }

        $lead->name = $request->name;
        $lead->save();

        return response()->json(['success' => true]);
    }

    public function updateByNumber($phone, Request $request)
    {
        // remove qualquer caractere não numérico do telefone
        $cleanPhone = preg_replace('/\D/', '', $phone);

        $lead = \App\Models\Lead::where('phone', 'like', "%$cleanPhone%")->first();

        if (!$lead) {
            return response()->json(['message' => 'Lead não encontrado para o número informado.'], 404);
        }

        $lead->name = $request->input('name', $lead->name);
        $lead->save();

        return response()->json(['message' => 'Nome atualizado com sucesso!', 'lead' => $lead]);
    }

    // =========================================================
// 🌐 API — Recebe leads vindos do site (formulário HTML)
// =========================================================
    public function receberDoFormulario(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|email',
                'phone' => 'nullable|string',
                'message' => 'required|string',
                'lead_code' => 'required|string',
            ]);

            // 🔹 Cria o lead principal
            $lead = \App\Models\Lead::create([
                'lead_code' => $validated['lead_code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'notes' => $validated['message'],
                'source' => 'Formulário do Site',
                'status' => 'novo',
            ]);

            // 🔹 Cria o tracking (caso você tenha a tabela)
            if (class_exists(\App\Models\LeadTracking::class)) {
                \App\Models\LeadTracking::create([
                    'lead_code' => $validated['lead_code'],
                    'gclid' => $request->input('gclid'),
                    'utm_source' => $request->input('utm_source'),
                    'utm_medium' => $request->input('utm_medium'),
                    'utm_campaign' => $request->input('utm_campaign'),
                    'utm_term' => $request->input('utm_term'),
                    'utm_content' => $request->input('utm_content'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referrer' => $request->headers->get('referer'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Lead recebido com sucesso! (Código: {$validated['lead_code']})"
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar lead do formulário: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao salvar lead.'
            ], 500);
        }
    }


    public function goWhatsapp()
    {
        $lead_code = strtoupper(Str::random(8));

        return response()->json([
            'success' => true,
            'lead_code' => $lead_code
        ]);
    }

    public function registrarCliqueWhatsApp(Request $request)
    {
        $lead_code = $request->input('lead_code');

        if (!$lead_code) {
            return response()->json(['success' => false, 'message' => 'Código do lead ausente.'], 400);
        }

        try {
            // Atualiza o leads_tracking com a data do clique
            \App\Models\LeadTracking::where('lead_code', $lead_code)
                ->update(['clicked_at' => now()]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Log::error('Erro ao registrar clique no WhatsApp: '.$e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }




}
