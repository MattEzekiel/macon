<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Files;
use App\Models\Clients;
use App\Models\Products;
use App\Models\QRs;
use App\Traits\FileSizeFormatter;

class AdminController extends Controller
{
    use FileSizeFormatter;

    public function index(): View|Application|Factory
    {
        // Tamaño total de los archivos
        $totalFileSize = Files::sum('file_size');
        $formattedFileSize = $this->formatFileSize($totalFileSize);
        
        // Total de archivos
        $totalFiles = Files::count();
        
        // Clientes con más archivos
        $topClients = Clients::withCount('files')
            ->orderByDesc('files_count')
            ->take(5)
            ->get();
        
        // Productos con más visitas
        $topProducts = Products::withSum('qrs as visit_count', 'visits_count')
            ->orderByDesc('visit_count')
            ->take(5)
            ->get();
        
        // Archivos con más visitas
        $topQRs = Files::orderByDesc('visits_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'formattedFileSize',
            'totalFiles',
            'topClients',
            'topProducts',
            'topQRs'
        ));
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
            'email.email' => 'El correo es inválido',
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
