<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'date',
        'time',
        'status',
        'notes',
        'google_event_id', // <-- adicionar este campo
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'string',
    ];

}
