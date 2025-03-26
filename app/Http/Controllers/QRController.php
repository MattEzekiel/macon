<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class QRController extends Controller
{
    public function index(): View|Application|Factory
    {
        return view('admin.qr.index');
    }

    public function newQR(int|null $id = null): View|Application|Factory
    {
        $product = null;
        if ($id) {
            $product = Products::findOrFail($id);
        } else {
            $product = Products::select('id', 'name')->get();
        }

        return view('admin.qr.new-qr', compact('product'));
    }
}
