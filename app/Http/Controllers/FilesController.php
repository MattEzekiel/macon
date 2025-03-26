<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\Products;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FilesController extends Controller
{
    public function newFiles(int $id): View|Application|Factory
    {
        $product = Products::findOrFail($id);
        return view('admin.files.new-file', compact('product'));
    }

    public function FileStore(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'product' => 'required|exists:products,id',
                    'files.*' => 'required|mimes:pdf|max:10240',
                ],
                [
                    'product.required' => 'El producto es requerido',
                    'product.exists' => 'El producto no existe',
                    'files.*.required' => 'Los archivos son requeridos',
                    'files.*.mimes' => 'Los archivos deben ser en formato PDF',
                    'files.*.max' => 'Cada archivo no debe superar los 10 MB',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('error', 'Hubo un error al subir el archivo')
                    ->withErrors($validator);
            }

            $product = Products::with('client')->findOrFail($request->product);

            $client_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->client->legal_name));
            $product_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->name));

            $base_path = public_path("files/{$client_name}/{$product_name}");

            if (!file_exists($base_path)) {
                mkdir($base_path, 0777, true);
            }

            foreach ($request->file('files') as $file) {
                $original_name = $file->getClientOriginalName();
                $file_name = uniqid() . '_' . $original_name;

                $file->move($base_path, $file_name);

                $files = new Files();
                $files->create([
                    'product_id' => $product->id,
                    'file_url' => "files/{$client_name}/{$product_name}/{$file_name}"
                ]);
            }

            return redirect()
                ->route('admin.new.qr', ['id' => $product->id])
                ->with('success', 'Los archivos se han subido correctamente');
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', 'Hubo un error al subir el archivo');
        }
    }
}
