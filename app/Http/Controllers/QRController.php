<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\QRs;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    public function index(): View|Application|Factory
    {
        return view('admin.qr.index');
    }

    public function newQR(int|null $id = null): View|Application|Factory
    {
        $product = null;
        $qr_code = null;
        $url = null;
        if ($id) {
            $product = Products::with('client')->findOrFail($id);
            $payload = [
                'product_id' => $product->id,
                'client_id' => $product->client->id,
            ];
            $encrypted_payload = Crypt::encrypt($payload);
            $url = route('home', ['payload' => $encrypted_payload]);
            $qr_code = QrCode::margin(0)->size(200)->generate($url);
        } else {
            $product = Products::select('id', 'name')->get();
        }

        return view('admin.qr.new-qr', compact('product', 'qr_code', 'url'));
    }

    public function QRStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'product' => 'required|exists:products,id',
                    'client' => 'required|exists:clients,id',
                    'url' => 'required',
                ],
                [
                    'product.required' => 'El producto es requerido',
                    'client.required' => 'El cliente es requerido',
                    'url.required' => 'La URL es requerida',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('error', 'Falló la generación del QR')
                    ->withErrors($validator)
                    ->withInput();
            }

            $product = Products::with('client')->findOrFail($request->product);
            $client_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->client->legal_name));
            $product_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->name));

            $qr_filename = "{$client_name}_{$product_name}_" . uniqid() . '.png';
            $qr_path = public_path('qr_codes/' . $qr_filename);

            if (!file_exists(public_path('qr_codes'))) {
                mkdir(public_path('qr_codes'), 0777, true);
            }

            QrCode::size(200)
                ->margin(0)
                ->format('png')
                ->generate($request->url, $qr_path);

            QRs::create([
                'product_id' => $request->product,
                'client_id' => $request->client,
                'url_qrcode' => 'qr_codes/' . $qr_filename
            ]);

            return redirect()->route('admin.qrs')->with('success', 'QR creado correctamente');
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', 'Falló la generación del QR');
        }
    }
}
