<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class YhcModel extends Model
{
    protected $table = 'yhc_cliente';
    public $timestamps = false;

    // Clientes
    public function getClientes($id = null)
    {
        if(isset($id)){
            $clientes= DB::table('yhc_cliente')
            ->select('*')
            ->orderBy('nome', 'asc')
            ->where('id','=',$id)
            ->get();
        }else{
            $clientes= DB::table('yhc_cliente')
            ->select('id','nome')
            ->orderBy('nome', 'asc')
            ->get();
        }

        return $clientes;
    }

    public function getCountClientes()
    {
        return DB::table('yhc_cliente')->count();
    }

    public function saveCliente($params)
    {
        if (isset($params['id'])) {
            return DB::table('yhc_cliente')->where('id', $params['id'])->update($params['cliente']);
        } else {
            return DB::table('yhc_cliente')->insertGetId($params['cliente']);
        }
    }


    // Vendas
    public function getVendaMes(){

        return DB::select('select year(data) as ano, 
                            monthname(data) as mes, 
                            sum(valor_pago) as total 
                            from yhc_venda 
                            group by year(data), monthname(data) 
                            order by year(data), monthname(data) DESC');
    }
    public function  getConsultas(){
        //dd('teste');
        return DB::select("select mesano,
                            sum(consulta) as consulta,
                            sum(tratamento) as tratamento,
                            sum(sessaohipnose) as sessaohipnose,
                            sum(sessaopsicanalise) as sessaopsicanalise 
                            from (
                                SELECT CONCAT(month(DATA),'/',year(DATA)) as mesano, 
                                (select count(*)  FROM `yhc_venda`
                                    where pacote_id = 1 and yhc_venda.data=tbl.data) as consulta,
                                (select count(*) FROM `yhc_venda`
                                    where pacote_id not in (1, 5, 7) and yhc_venda.data=tbl.data) as tratamento,
                                (select count(*) FROM `yhc_venda`
                                    where pacote_id = 5 and yhc_venda.data=tbl.data) as sessaohipnose,
                                (select count(*) FROM `yhc_venda`
                                    where pacote_id = 7 and yhc_venda.data=tbl.data) as sessaopsicanalise
                                from yhc_venda as tbl) as tbl2
                            GROUP BY mesano
                            order by mesano DESC");


    }


    public function getDespesaMes(){

        return DB::select("select 
                            CONCAT(month(DATA),'/',year(DATA)) as mesano,
                            monthname(data) as mes,
                            year(DATA) AS ano,
                            (select sum(valor)
                            from yhc_despesa where
                            categoria = 'Marketing' AND ano=year(data) AND mes=monthname(data)) AS marketing,
                            (select sum(valor)
                            from yhc_despesa where
                            categoria = 'Transporte' AND ano=year(data) AND mes=monthname(data)) AS transporte,
                            (select sum(valor)
                            from yhc_despesa where
                            categoria = 'Sala' AND ano=year(data) AND mes=monthname(data)) AS sala,
                            (select sum(valor)
                            from yhc_despesa where
                            categoria = 'Material' AND ano=year(data) AND mes=monthname(data)) AS material,
                            (select sum(valor)
                            from yhc_despesa where
                            categoria = 'Alimentação' AND ano=year(data) AND mes=monthname(data)) AS alimentacao,
                            sum(valor) as total
                            from yhc_despesa
                            group by mesano, year(data), monthname(data)
                            order by mesano, year(data), monthname(data) DESC");
    }

    public function getDatas(){
        return DB::select("select CONCAT(month(DATA),'/',year(DATA)) as mesano
                            from yhc_despesa
                            group by mesano
                            order by data DESC");
    }

    public function getVendas($id, $criterio)
    {

        try {

            if($criterio=='cliente'){


                return DB::table('yhc_venda')
                ->select('yhc_venda.*','yhc_cliente.nome','yhc_pacote.p_nome', 'yhc_pagamento.forma_pagamento')
                ->join("yhc_cliente", "yhc_cliente.id", "=", "yhc_venda.cliente_id")
                ->join("yhc_pacote", "yhc_pacote.id", "=", "yhc_venda.pacote_id")
                ->join("yhc_pagamento", "yhc_pagamento.id", "=", "yhc_venda.forma_pagamento")
                ->orderBy('data', 'desc')
                ->where('yhc_venda.cliente_id','=',$id)
                ->get();

            }else{
                return DB::table('yhc_venda')
                ->select('yhc_venda.*','yhc_cliente.nome','yhc_pacote.p_nome', 'yhc_pagamento.forma_pagamento')
                ->join("yhc_cliente", "yhc_cliente.id", "=", "yhc_venda.cliente_id")
                ->join("yhc_pacote", "yhc_pacote.id", "=", "yhc_venda.pacote_id")
                ->join("yhc_pagamento", "yhc_pagamento.id", "=", "yhc_venda.forma_pagamento")
                ->orderBy('data', 'desc')
                ->where('yhc_venda.id','=',$id)
                ->get();
            }


        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function getPacotes()
    {
        try{
            return DB::table('yhc_pacote')
            ->select('*')
            ->orderBy('p_nome', 'asc')
            ->get();


        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function getfPagamento(){
        try{
            return DB::table('yhc_pagamento')
            ->select('*')
            ->orderBy('id', 'asc')
            ->get();


        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function saveVenda($params){
        try {

            if(isset($params['id'])){

                return DB::connection('mysql')->table('yhc_venda')->where("id", $params['id'])->update($params['venda']);
            }else{

                return DB::connection('mysql')->table('yhc_venda')->insertGetId($params['venda']);

            }

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function deleteVenda($id){
        try {

            $deletedRows = DB::connection('mysql')->table('yhc_venda')->where('id', $id)->delete();
            return $deletedRows;

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function deleteCliente($id){
        try {

            $deletedRows = DB::connection('mysql')->table('yhc_cliente')->where('id', $id)->delete();
            return $deletedRows;

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function deleteSessoes($vendaId=null, $sessaoId=null){
        try {
            if($vendaId){
                $deletedRows = DB::connection('mysql')->table('yhc_tratamento')->where('venda_id', $vendaId)->delete();
            }else{
                $deletedRows = DB::connection('mysql')->table('yhc_tratamento')->where('id', $sessaoId)->delete();
            }

            return $deletedRows;

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function getNsessao($id){

        return DB::table('yhc_pacote')
        ->select('n_sessoes')
        ->where('id','=',$id)
        ->get();
    }

    public function deleteSessao($id){

    }

    public function getTratamento($id)
    {
        try{

            return DB::table('yhc_tratamento')
            ->select('*')
            ->where('venda_id','=',$id)
            ->orderBy('t_data', 'DESC')
            ->get();


        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function getSessaoInfo($id){
        try{

            return DB::table('yhc_tratamento')
            ->select('*')
            ->where('id','=',$id)
            ->get();


        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function insertSessao($data)
    {
        try{

            return DB::table('yhc_tratamento')->insert($data);

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function saveSessao($params){
        try {
            return DB::connection('mysql')->table('yhc_tratamento')->where("id", $params['id'])->update($params['sessao']);

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function getDespesa(){
        try {

            return DB::table('yhc_despesa')
            ->select('*')
            ->orderBy('data', 'DESC')
            ->get();

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function saveDespesa($params){
        try {
            if(isset($params['id'])){
                return DB::connection('mysql')->table('yhc_despesa')->where("id", $params['id'])->update($params['despesa']);
            }else{
                return DB::connection('mysql')->table('yhc_despesa')->insertGetId($params['despesa']);
            }

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function saveDespesas($params){
        try {

            return DB::connection('mysql')->table('yhc_despesa')->insert($params);

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function deleteDespesa($id){
        try {
            return DB::table('yhc_despesa')->delete($id);

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function getConsulta() {
        try {

            return DB::table('yhc_venda')
            ->select('yhc_venda.data','yhc_cliente.nome','yhc_cliente.notes', 'yhc_pacote.p_nome')
            ->join("yhc_pacote", "yhc_pacote.id", "=", "yhc_venda.pacote_id")
            ->join("yhc_cliente", "yhc_cliente.id", "=", "yhc_venda.cliente_id")
            ->orderBy('data', 'desc')
            // ->where('yhc_tratamento.t_data','<>','Null')
            ->get();

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

    public function getSessao() {
        try {

            return DB::table('yhc_tratamento')
            ->select('yhc_tratamento.t_data','yhc_cliente.nome','yhc_tratamento.t_note', 'yhc_tratamento.valor')
            ->join("yhc_venda", "yhc_tratamento.venda_id", "=", "yhc_venda.id")
            ->join("yhc_cliente", "yhc_cliente.id", "=", "yhc_venda.cliente_id")
            ->orderBy('t_data', 'desc')
            // ->where('yhc_tratamento.t_data','<>','Null')
            ->get();

        } catch (\Exception $e) {

            return "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
        }
    }

}
