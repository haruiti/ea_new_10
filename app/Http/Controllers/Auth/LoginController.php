<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CustomUser; // ✅ Usa o model correto (ea_users)

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 🔹 Busca o usuário na tabela correta (ea_users)
        $user = CustomUser::where('email', $request->input('email'))->first();

        // 🔹 Autentica comparando com o campo phone_number
        if ($user && $user->phone_number === $request->input('password')) {
            Auth::login($user);
            $request->session()->regenerate();

            // 🔸 Redireciona para o painel YHC
            return redirect('/yhc');
        }

        // 🔹 Caso contrário, retorna erro
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não foram encontradas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
