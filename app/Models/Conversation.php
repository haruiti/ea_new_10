<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversas';

    protected $fillable = [
        'numero',
        'tipo',
        'mensagem',
        'dados_extras',
    ];

    protected $casts = [
        'dados_extras' => 'array',
    ];
    
    // 👇 Adicione este método logo após os $casts
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
