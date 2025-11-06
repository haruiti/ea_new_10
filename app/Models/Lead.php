<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use App\Models\LeadTracking;

    class Lead extends Model
    {
        protected $table = 'leads';

        protected $fillable = [
            'name',
            'email',
            'phone',
            'source',
            'campaign_source',
            'status',
            'notes',
            'converted_user_id',
        ];

        public function appointments()
        {
            return $this->hasMany(Appointment::class);
        }

        public function conversas()
        {
            return $this->hasMany(Conversation::class, 'lead_id');
        }

        public function tracking()
        {
            return $this->belongsTo(LeadTracking::class, 'lead_code', 'lead_code');
        }


    }
