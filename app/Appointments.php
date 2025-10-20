<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appointments extends Model
{
    public function getAppointments($email){
        try{
            $now=date('Y-m-d').' 00:00';

            $users = DB::table('ea_appointments')
                ->where('ea_users.email', $email)
                ->where('ea_users.id_roles', '3')
                ->join ('ea_users','ea_users.id','=','ea_appointments.id_users_customer')
                ->join ('ea_services','ea_services.id','=','ea_appointments.id_services')
                ->join ('ea_users as ea_users2','ea_appointments.id_users_provider','=','ea_users2.id')
                ->select('ea_users.email','ea_users.phone_number','start_datetime','end_datetime','hash','ea_services.name','ea_appointments.notes','ea_users2.last_name','ea_users.id_roles')
                ->orderBy('start_datetime', 'asc')
                ->get();

            return $users;

        } catch (\Exception $e) {
            $retorno = "Erro: " . $e->getMessage() . "<br>linha: " . $e->getLine() . "<br>arquivo: " . $e->getFile();
            return $retorno;
        }
    }
}
