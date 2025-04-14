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
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(): View|Application|Factory
    {
        // Tamaño total de los archivos
        $totalFileSize = Files::whereNull('deleted_at')->sum('file_size');
        
        // Total de archivos
        $totalFiles = Files::whereNull('deleted_at')->count();
        
        // Clientes con más archivos
        $topClients = Clients::select([
            'clients.id',
            'clients.legal_name',
            'clients.tax_id',
            'clients.contact_name',
            'clients.contact_email',
            'clients.contact_phone',
            'clients.legal_address',
            \DB::raw('COUNT(DISTINCT CASE WHEN files.deleted_at IS NULL THEN files.id END) as files_count')
        ])
        ->leftJoin('products', 'clients.id', '=', 'products.client_id')
        ->leftJoin('files', 'products.id', '=', 'files.product_id')
        ->whereNull('clients.deleted_at')
        ->whereNull('products.deleted_at')
        ->groupBy([
            'clients.id',
            'clients.legal_name',
            'clients.tax_id',
            'clients.contact_name',
            'clients.contact_email',
            'clients.contact_phone',
            'clients.legal_address'
        ])
        ->orderByDesc('files_count')
        ->take(5)
        ->get();
        
        // Productos con más visitas
        $topProducts = Products::select([
            'products.id',
            'products.name',
            'products.description',
            'products.brand',
            'products.model',
            'products.origin',
            \DB::raw('(COALESCE(SUM(qrs.visits_count), 0) + COALESCE(SUM(files.visits_count), 0)) as visit_count')
        ])
        ->leftJoin('_q_r as qrs', 'products.id', '=', 'qrs.product_id')
        ->leftJoin('files', 'products.id', '=', 'files.product_id')
        ->whereNull('products.deleted_at')
        ->groupBy([
            'products.id',
            'products.name',
            'products.description',
            'products.brand',
            'products.model',
            'products.origin'
        ])
        ->orderByDesc('visit_count')
        ->take(5)
        ->get();
        
        // Archivos con más visitas
        $topQRs = Files::select([
            'files.id',
            'files.original_file_name',
            'files.file_name',
            'files.visits_count'
        ])
        ->whereNull('files.deleted_at')
        ->orderByDesc('visits_count')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'totalFileSize',
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
