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
    public function index(Request $request): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $data = QRs::with('product', 'client', 'product.files');

        if ($isClient && auth()->check()) {
            $data->whereHas('product', function ($query) {
                $query->where('client_id', auth()->user()->client_id);
            });
        } else {
            $data->when($request->client, function ($query, $id) {
                $query->where('client_id', $id);
            });
        }

        $data->when($request->product, function ($query, $name) {
            $query->whereHas('product', function ($q) use ($name) {
                $q->where('name', 'like', '%'.$name.'%');
            });
        });

        $qrs = $data->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $view = $isClient ? 'client.qr.index' : 'admin.qr.index';

        return view($view, compact('qrs'));
    }

    public function newQR(?int $id = null): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        if ($id) {
            $product = Products::with('client', 'files')->findOrFail($id);
            if ($isClient && auth()->check() && $product->client_id !== auth()->user()->client_id) {
                abort(403);
            }
        } else {
            $product = Products::select('id', 'name')->with('files');
            if ($isClient && auth()->check()) {
                $product->where('client_id', auth()->user()->client_id);
            }
            $product = $product->get();
        }

        $files = $product->files()->get();
        $view = $isClient ? 'client.qr.new-qr' : 'admin.qr.new-qr';

        return view($view, compact('product', 'files'));
    }

    public function QRStore(Request $request)
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        try {
            $validator = Validator::make($request->all(),
                [
                    'product' => 'required|exists:products,id',
                    'client' => 'required|exists:clients,id',
                    'file_names.*' => 'required',
                    'original_names.*' => 'required',
                ],
                [
                    'product.required' => __('files.product_required'),
                    'client.required' => __('clients.client').' '.__('general.required'),
                    'product.exists' => __('files.product_not_exists'),
                    'client.exists' => __('clients.client').' '.__('general.invalid'),
                    'file_names.*.required' => __('files.files').' '.__('general.required'),
                    'original_names.*.required' => __('files.original_file_name').' '.__('general.required'),
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('error', __('qrs.qr_creation_error'))
                    ->withErrors($validator)
                    ->withInput();
            }

            $combined = [];
            foreach ($request->file_names as $key => $file_name) {
                $combined[] = [
                    'file_name' => $file_name,
                    'original_name' => $request->original_names[$key],
                ];
            }

            foreach ($combined as $file_name) {
                $file = Files::where('product_id', $request->product)->where('original_file_name', $file_name['original_name'])->sole();
                $file->file_name = $file_name['file_name'];
                $file->save();
            }

            $product = Products::with('client')->findOrFail($request->product);
            if ($isClient && auth()->check() && $product->client_id !== auth()->user()->client_id) {
                abort(403);
            }

            $client_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', strtolower($product->client->legal_name)));
            $product_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', strtolower($product->name)));

            $payload = [
                'product_id' => $product->id,
                'client_id' => $product->client->id,
            ];

            $qr_filename = uniqid().'.svg';
            $local_path = 'qr_codes/'.$client_name.'/'.$product_name.'/'.$qr_filename;
            $qr_path = public_path($local_path);

            $directory = dirname(public_path($local_path));
            if (! file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $link = Crypt::encrypt(json_encode($payload));

            QrCode::size(200)
                ->margin(0)
                ->format('svg')
                ->generate(route('public_qr', ['payload' => $link]), $qr_path);

            QRs::create([
                'product_id' => $request->product,
                'client_id' => $request->client,
                'url_qrcode' => Crypt::encrypt($local_path),
                'link' => $link,
            ]);

            $routeName = $isClient ? 'client.qrs' : 'admin.qrs';

            return redirect()->route($routeName)->with('success', __('qrs.qr_created'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('qrs.qr_creation_error'));
        }
    }

    public function DisplayData(string $payload): View|Application|Factory
    {
        $data = json_decode(Crypt::decrypt($payload));
        if (Clients::where('id', $data->client_id)->exists() && Products::where('id', $data->product_id)->exists()) {
            $product = Products::with('client')->find($data->product_id);
            $files = Files::where('product_id', $data->product_id)->get();

            QRs::where('product_id', $data->product_id)
                ->where('client_id', $data->client_id)
                ->increment('visits_count');

            return view('links', compact('files', 'product'));
        }

        return view('links', ['files' => [], 'product']);
    }
}
