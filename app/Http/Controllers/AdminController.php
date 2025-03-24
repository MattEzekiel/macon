<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(): View|Application|Factory
    {
        return view('admin.dashboard');
    }

    public function loginForm(): View|Application|Factory
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo es inválido',
            'password.required' => 'La contraseña es requerida',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Las credenciales son incorrectas')->withInput();
    }
}
