<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadTracking extends Model
{
    use HasFactory;
    protected $table = 'leads_tracking';

    protected $fillable = [
        'lead_code',
        'gclid',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'ip_address',
        'user_agent',
        'referrer',
        'clicked_at',
    ];
}

