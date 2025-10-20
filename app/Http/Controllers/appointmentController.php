<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Appointments;

class appointmentController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->AppointmentsModel = new Appointments();
    }

    public function index()
    {
        $email=Auth::user()->email;

        $users=$this->AppointmentsModel->getAppointments($email);

        return view("appointments")->with('users', $users);

    }
}
