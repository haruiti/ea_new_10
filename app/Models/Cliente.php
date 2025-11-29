<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    protected $table = 'yhc_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'idade',
        'sexo',
        'estado_civil',
        'possui_filhos',
        'qtd_filhos',
        'profissao',
        'diagnostico',
        'motivacao',
        'notes',
        'date_created'
    ];

    // ✅ Listar clientes (todos ou um)
    public static function listar($id = null)
    {
        if ($id) {
            return DB::table('yhc_cliente')
                ->select('*')
                ->where('id', '=', $id)
                ->orderBy('nome', 'asc')
                ->get();
        }

        return DB::table('yhc_cliente')
            ->select('id', 'nome')
            ->orderBy('nome', 'asc')
            ->get();
    }

    // ✅ Contar clientes
    public static function contar()
    {
        return DB::table('yhc_cliente')->count();
    }

    // ✅ Criar ou atualizar cliente
    public static function salvar($dados, $id = null)
    {
        if ($id) {
            return DB::table('yhc_cliente')->where('id', $id)->update($dados);
        }

        $dados['date_created'] = now();
        return DB::table('yhc_cliente')->insertGetId($dados);
    }

    // ✅ Excluir cliente
    public static function excluir($id)
    {
        return DB::table('yhc_cliente')->where('id', $id)->delete();
    }
}
