<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ConversationController extends Controller
{
    /**
     * 📥 Registrar nova conversa (mensagem recebida ou enviada)
     * Rota: POST /api/conversas/registrar
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'numero' => 'required|string',
                'mensagem' => 'required|string',
                'tipo' => 'required|in:recebida,enviada',
                'dados_extras' => 'nullable|array',
            ]);

            // 🧩 Criar ou atualizar lead
            $lead = Lead::firstOrCreate(
                ['phone' => $validated['numero']],
                [
                    'status' => 'novo',
                    'source' => 'whatsapp',
                ]
            );

            // Atualiza data da última interação
            $lead->ultima_interacao = Carbon::now();
            if ($validated['tipo'] === 'recebida' && $lead->status === 'novo') {
                $lead->status = 'em_contato';
            }
            $lead->save();

            // 💾 Registrar conversa
            $conversa = Conversation::create($validated);

            return response()->json([
                'success' => true,
                'data' => [
                    'conversa' => $conversa,
                    'lead' => $lead,
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('❌ Erro ao registrar conversa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erro ao registrar conversa',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 📄 Listar últimas conversas (paginação)
     * Rota: GET /api/conversas
     */
    public function index()
    {
        try {
            $conversas = Conversation::latest()->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $conversas
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Erro ao listar conversas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erro ao listar conversas'
            ], 500);
        }
    }

    /**
     * 📜 Retornar o histórico recente de um lead (últimas 10 mensagens)
     * Rota: GET /api/conversas/historico/{numero}
     */
    public function historico($numero)
    {

        try {
            $mensagens = Conversation::where('numero', $numero)
                ->orderBy('id', 'desc')
                ->take(10)
                ->get(['tipo', 'mensagem', 'created_at']);

            // Atualiza a última interação do lead
            // $lead = Lead::where('phone', $numero)->first();
            // if ($lead) {
            //     $lead->ultima_interacao = Carbon::now();
            //     $lead->save();
            // }

            return $mensagens;

            return response()->json([
                'success' => true,
                'data' => $mensagens
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Erro ao buscar histórico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erro ao buscar histórico'
            ], 500);
        }
    }

   
    public function ultimosTresMeses(Request $request)
    {
        // Intervalo padrão: últimos 3 meses
        $inicio = $request->input('data_inicio', Carbon::now()->subMonths(3)->startOfDay());
        $fim = $request->input('data_fim', Carbon::now()->endOfDay());

        // Filtros opcionais
        $source = $request->input('source'); // Ex: "Google Ads"
        $status = $request->input('status'); // Ex: "convertido", "novo", etc.

        // Query base
        $query = Conversation::with('lead')
            ->whereBetween('created_at', [$inicio, $fim])
            ->orderBy('created_at', 'desc');

        // Filtro por campanha
        if ($source) {
            $query->whereHas('lead', function ($q) use ($source) {
                $q->where('source', $source);
            });
        }

        // Filtro por status
        if ($status) {
            $query->whereHas('lead', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        // Executa a query
        $conversas = $query->get();

        return response()->json([
            'total' => $conversas->count(),
            'data' => $conversas,
        ]);
    }

    /**
     * Retorna todas as campanhas distintas registradas nos leads.
     * Exemplo: ["Google Ads", "Instagram", "Indicação"]
     */
    public function listarCampanhas()
    {
        $campanhas = Lead::select('source')
            ->whereNotNull('source')
            ->distinct()
            ->pluck('source');

        return response()->json($campanhas);
    }

    public function storeBatch(Request $request)
    {
        $data = $request->all();
        $importados = 0;

        foreach ($data as $item) {
            // Evita duplicar mensagens importadas
            $existe = Conversation::where('numero', $item['numero'])
                ->where('mensagem', $item['mensagem'])
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if (!$existe) {
                Conversation::create([
                    'numero' => $item['numero'],
                    'tipo' => $item['tipo'],
                    'mensagem' => $item['mensagem'],
                    'dados_extras' => $item['dados_extras'] ?? null,
                ]);

                $importados++;
            }
        }

        return response()->json([
            'status' => 'ok',
            'importados' => $importados,
            'recebidos' => count($data)
        ]);
    }
}
