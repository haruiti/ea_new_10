<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\YhcModel;
use App\Models\YhcUser;


class Yhc extends Controller
{
    protected $YhcModel;

    public function __construct()
    {
        $this->middleware('auth');
        $this->YhcModel = new YhcModel();
    }

    // Página principal do YHC
    public function index()
    {
        $clientes = $this->YhcModel->getClientes();
        $countClientes = $this->YhcModel->getCountClientes();

        return view("yhc/index")
            ->with('clientes', $clientes)
            ->with('countClientes', $countClientes);
    }

    // Adicionar cliente
    public function addcliente()
    {
        return view("yhc/addcliente");
    }

    // Editar cliente
    public function editcliente(Request $request)
    {
    //  dd('Entrou no método editcliente', $request->id);
        $cliente = $this->YhcModel->getClientes($request->id);
        return view("yhc/addcliente")->with('cliente', $cliente);
    }

    // Salvar cliente
    public function savecliente(Request $request)
    {
        $clienteData = [
            "nome" => $request->nome,
            "date_created" => date("Y-m-d H:i:s"),
            "idade" => $request->idade,
            "notes" => $request->notes
        ];

        $params = isset($request->id)
            ? ['cliente' => $clienteData, 'id' => $request->id]
            : ['cliente' => $clienteData];

        return $this->YhcModel->saveCliente($params);
    }

    // Vendas



    public function saveVenda(Request $request)
    {
        $time = substr($request->data, 10);
        $ano = substr($request->data, 6, 4);
        $mes = substr($request->data, 3, 2);
        $dia = substr($request->data, 0, 2);
        $request->data = "$ano-$mes-$dia$time";

        $vendaData = [
            "data" => $request->data,
            "pacote_id" => $request->pacote_id,
            "forma_pagamento" => $request->forma_pagamento,
            "valor_pacote" => $request->valorPacote,
            "valor_pago" => $request->valorPago
        ];

        if (isset($request->cliente_id)) {
            $vendaData['cliente_id'] = $request->cliente_id;
        }

        $params = isset($request->id)
            ? ['venda' => $vendaData, 'id' => $request->id]
            : ['venda' => $vendaData];

        $vendaID = $this->YhcModel->saveVenda($params);

        // Criar sessões
        if (isset($request->pacote_id)) {
            $sessoes = $this->YhcModel->getNsessao($request->pacote_id);
            $valorSessao = (float)$request->valorPago / (int)$sessoes[0]->n_sessoes;
            $data = [];
            for ($x = 1; $x <= $sessoes[0]->n_sessoes; $x++) {
                $data[] = ['venda_id' => $vendaID, 'valor' => $valorSessao];
            }
            $this->YhcModel->insertSessao($data);
        }

        return;
    }

    public function deleteVenda(Request $request)
    {
        $this->YhcModel->deleteSessoes($request->id, '');
        return $this->YhcModel->deleteVenda($request->id);
    }

    public function deleteCliente(Request $request)
    {
        $this->YhcModel->deleteCliente($request->id);
        return $this->YhcModel->getClientes();
    }

    // Sessões / tratamentos
    public function getTratamento(Request $request)
    {
        $sessoes = $this->YhcModel->getTratamento($request->id);
        return view("yhc/sessaoTable")->with('sessoes', $sessoes);
    }

    public function editarSessao(Request $request)
    {
        $sessaoInfo = $this->YhcModel->getSessaoInfo($request->id);
        return view("yhc/modalSessao")->with('sessaoInfo', $sessaoInfo);
    }

    public function saveSessao(Request $request)
    {
        if ($request->t_data) {
            $time = substr($request->t_data, 10);
            $ano = substr($request->t_data, 6, 4);
            $mes = substr($request->t_data, 3, 2);
            $dia = substr($request->t_data, 0, 2);
            $request->t_data = "$ano-$mes-$dia$time";
        }

        $sessaoData = [
            "t_data" => $request->t_data ?? null,
            "t_note" => $request->t_note,
            "valor" => $request->valor
        ];

        $params = isset($request->id)
            ? ['sessao' => $sessaoData, 'id' => $request->id]
            : ['sessao' => $sessaoData];

        return $this->YhcModel->saveSessao($params);
    }

    public function addSessao(Request $request)
    {
        $this->YhcModel->insertSessao([['venda_id' => $request->id, 'valor' => 0.00]]);
    }

    public function deleteSessao(Request $request)
    {
        $this->YhcModel->deleteSessoes('', $request->id);
    }

    // Despesas
    public function despesaModal()
    {
        return view("yhc/modalDespesa");
    }

    public function getDespesa()
    {
        $despesas = $this->YhcModel->getDespesa();
        return view("yhc/despesaTable")->with('despesas', $despesas);
    }

    public function saveDespesa(Request $request)
    {
        $time = substr($request->data, 10);
        $ano = substr($request->data, 6, 4);
        $mes = substr($request->data, 3, 2);
        $dia = substr($request->data, 0, 2);
        $request->data = "$ano-$mes-$dia$time";

        $despesaData = [
            "data" => $request->data,
            "categoria" => $request->categoria,
            "despesa" => $request->despesa,
            "valor" => $request->valor
        ];

        $params = isset($request->id)
            ? ['despesa' => $despesaData, 'id' => $request->id]
            : ['despesa' => $despesaData];

        return $this->YhcModel->saveDespesa($params);
    }

    public function deleteDespesa(Request $request)
    {
        return $this->YhcModel->deleteDespesa($request->id);
    }

    public function importJson()
    {
        $json = json_decode(file_get_contents('c:/temp/ads.json'), true);
        $params = [];

        foreach ($json as $value) {
            $params[] = [
                "data" => date('Y-m-d H:i', strtotime($value['Data'])),
                "valor" => $value['Valor'],
                "categoria" => 'Marketing',
                "despesa" => $value['Despesa']
            ];
        }

        $this->YhcModel->saveDespesas($params);
    }
    public function getVenda(Request $request)
        {

            $vendas=$this->YhcModel->getVendas($request->id, 'cliente');


            return view("yhc/vendasTable")->with('vendas', $vendas);


        }

    // Dashboard
    public function dashboard(){

        $vendas=$this->YhcModel->getVendaMes();
    
        $despesas=$this->YhcModel->getDespesaMes();
    
        $datas=$this->YhcModel->getDatas();
       
        $consultas=$this->YhcModel->getConsultas();        

        $dados1=[];
        foreach($vendas as $venda){
            foreach($despesas as $despesa){
                if($venda->ano==$despesa->ano && $venda->mes==$despesa->mes){
                    $dados1[]=array(
                        "data"=> $despesa->mesano,
                        "entrada"=>$venda->total,
                        "marketing"=>$despesa->marketing,
                        "transporte"=>$despesa->transporte,
                        "sala"=>$despesa->sala,
                        "alimentacao"=>$despesa->alimentacao,
                        "material"=>$despesa->material,
                        "saida"=>$despesa->total,
                        "saldo"=>$venda->total-$despesa->total
                    );
                }
            }

        }

        $dados2=[];
        foreach ($dados1 as $venda){
            foreach ($consultas as $consulta){
                if($venda['data']==$consulta->mesano ){
                    $dados2[]=array(
                        "data"=> $venda['data'],
                        "entrada"=>$venda['entrada'],
                        "marketing"=>$venda['marketing'],
                        "transporte"=>$venda['transporte'],
                        "sala"=>$venda['sala'],
                        "alimentacao"=>$venda['alimentacao'],
                        "material"=>$venda['material'],
                        "saida"=>$venda['saida'],
                        "saldo"=>$venda['saldo'],
                        "consulta"=>isset($consulta->consulta)?$consulta->consulta:null,
                        "tratamento"=>isset($consulta->tratamento)?$consulta->tratamento:null,
                        "sessaohipnose"=>isset($consulta->sessaohipnose)?$consulta->sessaohipnose:null,
                        "sessaopsicanalise"=>isset($consulta->sessaopsicanalise)?$consulta->sessaopsicanalise:null,
                    );
                }
            }
        }


        $dados=[];
        foreach($datas as $data){
            foreach($dados2 as $dado1){
                if ($dado1['data']==$data->mesano){
                    $dados[]=array(
                        "data"=> $data->mesano,
                        "entrada"=>$dado1['entrada'],
                        "marketing"=>$dado1['marketing'],
                        "transporte"=>$dado1['transporte'],
                        "sala"=>$dado1['sala'],
                        "alimentacao"=>$dado1['alimentacao'],
                        "material"=>$dado1['material'],
                        "saida"=>$dado1['saida'],
                        "saldo"=>$dado1['saldo'],
                        "consulta"=>isset($dado1['consulta'])?$dado1['consulta']:null,
                        "tratamento"=>isset($dado1['tratamento'])?$dado1['tratamento']:null,
                        "sessaohipnose"=>isset($dado1['sessaohipnose'])?$dado1['sessaohipnose']:null,
                        "sessaopsicanalise"=>isset($dado1['sessaopsicanalise'])?$dado1['sessaopsicanalise']:null
                    );
                }
            }
        }

        // usort($dados, array($this, "sortByOrder"));

        return view("yhc/dashboardTable")->with('dados', $dados);
    }



    public function addVendaModal(Request $request)
    {

        if($request->id){
            $venda=$this->YhcModel->getVendas($request->id, 'venda');
        }

        $pacotes=$this->YhcModel->getPacotes();
        $formaPagamento=$this->YhcModel->getfPagamento();
        if($request->id){
            return view("yhc/addVenda")->with('pacotes', $pacotes)
                                        ->with('cliente_id', $request->cliente_id)
                                        ->with('formaPagamento',$formaPagamento)
                                        ->with('venda', $venda);
        }else{
            return view("yhc/addVenda")->with('pacotes', $pacotes)
                                        ->with('cliente_id', $request->cliente_id)
                                        ->with('formaPagamento',$formaPagamento);

        }
    }
    // Atendimento
    public function atendimento()
    {
        $vendas = $this->YhcModel->getConsulta(); // CORRIGIDO: substituir getSessao por getConsulta()
        return view("yhc/atendimentoTable")->with('vendas', $vendas);
    }
}
