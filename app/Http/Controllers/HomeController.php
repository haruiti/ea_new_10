<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Appointments;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $email=Auth::user()->email;

        $users=$this->AppointmentsModel->getAppointments($email);

        return view("appointments")->with('users', $users);
        // return view("appointments");

        
        // $users = session('users');
        // session_unset();
        // if(isset($users)){
        //     return view('appointments', ['users' => $users]);
        // }else{
        //     return redirect('login');

        // }

    }
}
