<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Files;
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
        $qrs = QRs::with('product', 'client', 'product.files')->paginate(10)->withQueryString();
        return view('admin.qr.index', compact('qrs'));
    }

    public function newQR(int|null $id = null): View|Application|Factory
    {
        if ($id) {
            $product = Products::with('client', 'files')->findOrFail($id);
        } else {
            $product = Products::select('id', 'name')->with('files')->get();
        }

        $files = $product->files()->get();

        return view('admin.qr.new-qr', compact('product', 'files'));
    }

    public function QRStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'product' => 'required|exists:products,id',
                    'client' => 'required|exists:clients,id',
                    'file_names.*' => 'required',
                    'original_names.*' => 'required',
                ],
                [
                    'product.required' => 'El producto es requerido',
                    'client.required' => 'El cliente es requerido',
                    'product.exists' => 'El producto no existe',
                    'client.exists' => 'El cliente no existe',
                    'file_names.*.required' => 'Los archivos son requeridos',
                    'original_names.*.required' => 'Los nombres originales son requeridos',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('error', 'Falló la generación del QR')
                    ->withErrors($validator)
                    ->withInput();
            }

            $combined = [];
            foreach ($request->file_names as $key => $file_name) {
                $combined[] = [
                    'file_name' => $file_name,
                    'original_name' => $request->original_names[$key]
                ];
            }

            foreach ($combined as $file_name) {
                $file = Files::where('product_id', $request->product)->where('original_file_name', $file_name['original_name'])->sole();
                $file->file_name = $file_name['file_name'];
                $file->save();
            }

            $product = Products::with('client')->findOrFail($request->product);
            $client_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', strtolower($product->client->legal_name)));
            $product_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', strtolower($product->name)));

            $payload = [
                'product_id' => $product->id,
                'client_id' => $product->client->id
            ];

            $qr_filename = uniqid() . '.svg';
            $local_path = 'qr_codes/' . $client_name . '/' . $product_name . '/' . $qr_filename;
            $qr_path = public_path($local_path);

            $directory = dirname(public_path($local_path));
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            QrCode::size(200)
                ->margin(0)
                ->format('svg')
                ->generate(route('public_qr', ['payload' => Crypt::encrypt(json_encode($payload))]), $qr_path);

            QRs::create([
                'product_id' => $request->product,
                'client_id' => $request->client,
                'url_qrcode' => Crypt::encrypt($local_path),
            ]);

            return redirect()->route('admin.qrs')->with('success', 'QR creado correctamente');
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', 'Falló la generación del QR');
        }
    }

    public function DisplayData(string $payload): View|Application|Factory
    {
        // http://localhost/quique-app/public/search/eyJpdiI6Ik1hOFBmeTVyTURnS2lkaTU0NS9rUlE9PSIsInZhbHVlIjoiaFpEYlRxT2FGaDFNcmhsUWgvZ3ZKU3EzMERIRXphZTRWSGxiNUtLNjREVWxycUs5V0RBaE8zbklrNzA1eVRociIsIm1hYyI6ImRmZmRkNjA0ODI4YmYwZTM5MzNiMjUwMjc4NzlmNmQ3M2E3OTJjYjc4YWEzZTk1MmU4Zjk1NDc4MjNiZTg5MjUiLCJ0YWciOiIifQ==
        $data = json_decode(Crypt::decrypt($payload));
        if (Clients::where('id', $data->client_id)->exists() && Products::where('id', $data->product_id)->exists()) {
            $files = Files::where('product_id', $data->product_id)->get();
        } else {
            $files = [];
        }
        return view('links', compact('files'));
    }
}
