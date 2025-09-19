<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class AuthController extends Controller 
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (FacadesAuth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        } else {
            return back()->with('error', 'Username atau password salah');
        }
    }

    public function logout(Request $request)
    {
        FacadesAuth::logout();
        return redirect('/');
    }
}
