<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Crie uma view chamada login.blade.php
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // Recupera a senha armazenada no banco de dados
        $storedPassword = DB::table('auth_users')->value('password');

        // Log para ver as senhas
        Log::info('Stored Password: ' . $storedPassword);
        Log::info('Input Password: ' . $request->password);

        // Verifica se a senha está correta
        if (Hash::check($request->password, $storedPassword)) {
            session(['authenticated' => true]);
            return redirect()->route('dashboard');
        }

        Log::info('Authentication failed');
        return back()->withErrors(['password' => 'Senha incorreta.']);
    }
}
