<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Services\GoogleAdsConversionService;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    protected $googleAds;

    public function __construct(GoogleAdsConversionService $googleAds)
    {
        $this->middleware('auth');
        $this->googleAds = $googleAds;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomeCliente' => 'required|string|max:255',
            ]);

            $dados = [
                'nome'          => $request->nomeCliente,
                'idade'         => $request->idadeCliente,
                'sexo'          => $request->sexoCliente,
                'estado_civil'  => $request->estadoCivil,
                'possui_filhos' => $request->possuiFilhos,
                'qtd_filhos'    => $request->qtdFilhos,
                'profissao'     => $request->profissao,
                'diagnostico'   => $request->diagnostico,
                'motivacao'     => $request->motivacao,
                'notes'         => $request->notes,
            ];

            if ($request->id) {
                Cliente::salvar($dados, $request->id);
                $msg = "Cliente atualizado com sucesso!";
            } else {
                Cliente::salvar($dados);
                $msg = "Cliente salvo com sucesso!";
            }

            // Conversão de lead
            if ($request->lead_id) {
                $lead = DB::table('leads')->where('id', $request->lead_id)->first();
                if ($lead) {
                    DB::table('leads')->where('id', $request->lead_id)->update(['status' => 'convertido']);
                    $this->googleAds->enviarConversaoAgendamento($lead);
                }
            }

            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro: '.$e->getMessage()]);
        }
    }
}
