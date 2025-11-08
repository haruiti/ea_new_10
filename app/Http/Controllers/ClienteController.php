<?php
use App\Services\GoogleAdsConversionService;
use Illuminate\Support\Facades\DB;
use App\Models\Lead;

class ClienteController extends Controller
{
    protected $YhcModel;
    protected $googleAds;

    public function __construct(GoogleAdsConversionService $googleAds)
    {
        $this->middleware('auth');
        $this->YhcModel = new \App\YhcModel();
        $this->googleAds = $googleAds;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomeCliente' => 'required|string|max:255',
            ]);

            $clienteData = [
                "nome" => $request->nomeCliente,
                "idade" => $request->idadeCliente,
                "sexo" => $request->sexoCliente,
                "estado_civil" => $request->estadoCivil,
                "possui_filhos" => $request->possuiFilhos,
                "qtd_filhos" => $request->qtdFilhos,
                "profissao" => $request->profissao,
                "diagnostico" => $request->diagnostico,
                "motivacao" => $request->motivacao,
                "notes" => $request->notes,
                "date_created" => now(),
            ];

            if ($request->id) {
                $params = ['cliente' => $clienteData, 'id' => $request->id];
                $this->YhcModel->saveCliente($params);
                $msg = "Cliente atualizado com sucesso!";
            } else {
                $this->YhcModel->saveCliente(['cliente' => $clienteData]);
                $msg = "Cliente salvo com sucesso!";
            }

            // Se veio de lead, marcar como convertido e enviar conversão
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
