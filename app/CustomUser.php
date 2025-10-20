<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'ea_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','id_roles','email', 'phone_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'phone_number', 'remember_token',
    ];


    public function getAuthPassword()
    {
        //dd(str_replace("-","",$this->phone_number));
      return str_replace("-","",$this->phone_number);
    }
}
