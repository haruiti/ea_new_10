<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YhcUser extends Model
{
    protected $table = 'ea_users'; // 🔥 tabela correta
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];
}
