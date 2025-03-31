<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\Products;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
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

    public function editFiles(int $id): View|Application|Factory
    {
        $product = Products::with('files')->findOrFail($id);
        $product->file_edition = true;
        return view('admin.files.edit-file', compact('product'));
    }

    public function nameFiles(int $id): View|Application|Factory
    {
        $product = Products::with('files')->findOrFail($id);
        $files = $product->files()->get();
        return view('admin.files.name-file', compact('product', 'files'));
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

            $base_path = public_path(strtolower("files/{$client_name}/{$product_name}"));

            if (!file_exists($base_path)) {
                mkdir($base_path, 0777, true);
            }

            foreach ($request->file('files') as $file) {
                $original_name = strtolower($file->getClientOriginalName());
                $file_name = uniqid() . '_' . $original_name;

                $file->move($base_path, $file_name);

                $files = new Files();
                $files->create([
                    'product_id' => $product->id,
                    'file_url' => strtolower("files/{$client_name}/{$product_name}/{$file_name}"),
                    'original_file_name' => $original_name,
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

    public function FileUpdate(Request $request)
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

            $base_path = public_path(strtolower("files/{$client_name}/{$product_name}"));

            if (!file_exists($base_path)) {
                mkdir($base_path, 0777, true);
            }

            foreach ($request->file('files') as $file) {
                $original_name = strtolower($file->getClientOriginalName());
                $file_name = uniqid() . '_' . $original_name;

                $file->move($base_path, $file_name);

                $files = new Files();
                $files->create([
                    'product_id' => $product->id,
                    'file_url' => strtolower("files/{$client_name}/{$product_name}/{$file_name}"),
                    'original_file_name' => $original_name,
                ]);
            }

            return redirect()
                ->route('admin.name.files', ['id' => $product->id])
                ->with('success', 'Los archivos se han subido correctamente');
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', 'Hubo un error al subir el archivo');
        }
    }

    public function FileRename(Request $request): RedirectResponse
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

            return redirect()->route('admin.products')->with('success', 'Archivos renombreados correctamente');
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', 'Algo falló, intentelo de nuevo');
        }

    }

    public function FileDelete(int $id): RedirectResponse
    {
        try {
            Files::findOrFail($id)->delete();
            return redirect()->back()->with('success', __('files.deleted_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', __('files.deleted_error'));
        }
    }
}
