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




}
