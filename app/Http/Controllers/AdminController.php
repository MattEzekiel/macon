<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        ]);

        $credentials = $request->only('email', 'password');

        if (auth('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Las credenciales son incorrectas');
    }

    public function clients(): View|Application|Factory
    {
        return view('admin.clients.index');
    }

    public function newClient(): View|Application|Factory
    {
        $form_data = (new Clients)->getFormData();
        return view('admin.clients.new-client', compact('form_data'));
    }

    public function ClientStore(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'legal_name' => 'required',
                'tax_id' => 'required',
                'contact_name' => 'required',
                'contact_email' => 'required|email',
                'contact_phone' => 'required',
                'legal_address' => 'required',
            ],
            [
                'legal_name.required' => __('clients.legal_name') . ' es requerido',
                'tax_id.required' => __('clients.tax_id') . ' es requerido',
                'contact_name.required' => __('clients.contact_name') . ' es requerido',
                'contact_email.required' => __('clients.contact_email') . ' es requerido',
                'contact_email.email' => __('clients.contact_email') . ' es inválido',
                'contact_phone.required' => __('clients.contact_phone') . ' es requerido',
                'legal_address.required' => __('clients.legal_address') . ' es requerido',
            ]
        );

        try {
            $client = new Clients();
            $client->fill($validated);
            $client->save();

            return redirect()->route('admin.clients')->with('success', __('client.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', __('client.created_error'));
        }
    }

    public function QR(): View|Application|Factory
    {
        return view('admin.qr.index');
    }

    public function contactos(): View|Application|Factory
    {
        return view('admin.contacto.index');
    }
}
