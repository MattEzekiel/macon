<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\Products;
use App\Traits\FileSizeFormatter;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FilesController extends Controller
{
    use FileSizeFormatter;

    public function index(): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $filesQuery = Files::with(['product.client'])
            ->select([
                'id',
                'file_name',
                'original_file_name',
                'file_url',
                'product_id',
                'file_size',
                'created_at',
            ]);

        if ($isClient && auth()->check()) {
            $filesQuery->whereHas('product', function ($query) {
                $query->where('client_id', auth()->user()->client_id);
            });
        } else {
            $filesQuery->when(request()->client, function ($query, $id) {
                $query->whereHas('product.client', function ($query) use ($id) {
                    $query->where('id', $id);
                });
            });
        }

        $filesQuery->when(request()->product, function ($query, $id) {
            $query->where('product_id', $id);
        });

        $filesQuery->when(request()->file_name, function ($query, $name) {
            $query->where('file_name', 'like', '%'.$name.'%');
        });

        $filesQuery->when(request()->original_file_name, function ($query, $name) {
            $query->where('original_file_name', 'like', '%'.$name.'%');
        });

        $filesQuery->when(request()->deleted, function ($query, $deletion) {
            if ($deletion == '1') {
                $query->onlyTrashed();
            } elseif ($deletion == '2') {
                $query->withTrashed();
            }
        });

        $files = $filesQuery->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $view = $isClient ? 'client.files.index' : 'admin.files.index';

        return view($view, compact('files'));
    }

    public function newFiles(int $id): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $product = Products::findOrFail($id);
        $view = $isClient ? 'client.files.new-file' : 'admin.files.new-file';

        return view($view, compact('product'));
    }

    public function editFiles(int $id): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $product = Products::with('files')->findOrFail($id);
        $product->file_edition = true;

        $view = $isClient ? 'client.files.edit-file' : 'admin.files.edit-file';

        return view($view, compact('product'));
    }

    public function nameFiles(int $id): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $product = Products::with('files')->findOrFail($id);
        $files = $product->files()->get();

        $view = $isClient ? 'client.files.name-file' : 'admin.files.name-file';

        return view($view, compact('product', 'files'));
    }

    public function FileStore(Request $request)
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'product' => 'required|exists:products,id',
                    'files.*' => 'required|mimes:pdf|max:10240',
                ],
                [
                    'product.required' => __('files.product_required'),
                    'product.exists' => __('files.product_not_exists'),
                    'files.*.required' => __('files.files_required'),
                    'files.*.mimes' => __('files.files_pdf_only'),
                    'files.*.max' => __('files.file_size_limit'),
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('error', __('files.upload_error'))
                    ->withErrors($validator);
            }

            $product = Products::with('client')->findOrFail($request->product);

            $client_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->client->legal_name));
            $product_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->name));

            $base_path = strtolower("files/{$client_name}/{$product_name}");

            $files = $request->allFiles();

            $this->saveFiles($files['files'], $base_path, $product->id);

            $routeName = $isClient ? 'client.new.qr' : 'admin.new.qr';

            return redirect()
                ->route($routeName, ['id' => $product->id])
                ->with('success', __('files.upload_success'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('files.upload_error'));
        }
    }

    protected function saveFiles($files, string $base_path, int $product_id): void
    {
        $disk = config('filesystems.default');

        foreach ($files as $file) {
            $original_name = strtolower($file->getClientOriginalName());
            $file_name = uniqid().'_'.Str::random(10);
            $file_size = $file->getSize();
            $file_path = strtolower("{$base_path}/{$file_name}");

            $uploaded = Storage::disk($disk)->put($file_path, file_get_contents($file));

            if (! $uploaded) {
                continue;
            }

            Files::create([
                'product_id' => $product_id,
                'file_url' => $file_path,
                'original_file_name' => $original_name,
                'file_size' => $file_size,
            ]);
        }
    }

    public function FileUpdate(Request $request)
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'product' => 'required|exists:products,id',
                    'files.*' => 'nullable|mimes:pdf|max:10240',
                ],
                [
                    'product.required' => __('files.product_required'),
                    'product.exists' => __('files.product_not_exists'),
                    'files.*.mimes' => __('files.files_pdf_only'),
                    'files.*.max' => __('files.file_size_limit'),
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('error', __('files.upload_error'))
                    ->withErrors($validator);
            }

            $product = Products::with('client')->findOrFail($request->product);

            // Si hay archivos nuevos, procesarlos
            if ($request->hasFile('files')) {
                $client_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->client->legal_name));
                $product_name = trim(preg_replace('/[^A-Za-z0-9\-]/', '_', $product->name));

                $base_path = strtolower("files/{$client_name}/{$product_name}");

                $files = $request->allFiles();
                $this->saveFiles($files['files'], $base_path, $product->id);
            }

            $routeName = $isClient ? 'client.name.files' : 'admin.name.files';

            return redirect()
                ->route($routeName, ['id' => $product->id])
                ->with('success', __('files.upload_success'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('files.upload_error'));
        }
    }

    public function FileRename(Request $request): RedirectResponse
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        try {
            $validator = Validator::make($request->all(),
                [
                    'product' => 'required|exists:products,id',
                    'file_names.*' => 'required',
                    'files_ids.*' => 'required|exists:files,id',
                ],
                [
                    'product.required' => __('files.product_required'),
                    'product.exists' => __('files.product_not_exists'),
                    'file_names.*.required' => __('files.file_name').' '.__('general.required'),
                    'files_ids.*.required' => __('files.files').' '.__('general.required'),
                    'files_ids.*.exists' => __('files.files').' '.__('general.invalid'),
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('error', __('files.rename_error'))
                    ->withErrors($validator)
                    ->withInput();
            }

            foreach ($request->files_ids as $key => $file_id) {
                $file = Files::findOrFail($file_id);
                $file->file_name = $request->file_names[$key];
                $file->save();
            }

            $routeName = $isClient ? 'client.products' : 'admin.products';

            return redirect()->route($routeName)->with('success', __('files.rename_success'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('files.rename_error'));
        }
    }

    public function FileDelete(int $id): RedirectResponse
    {
        try {
            $file = Files::findOrFail($id);
            $file->delete();

            return redirect()->back()->with('success', __('files.delete_success'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error('Error al eliminar archivo', [
                    'file_id' => $id,
                    'error' => $exception->getMessage(),
                ]);
            }

            return back()->with('error', __('files.delete_error'));
        }
    }

    public function getFileContent($id)
    {
        try {
            $file = Files::findOrFail($id);

            // Usa el sistema de almacenamiento configurado
            $disk = config('filesystems.default');
            $path = $disk === 'local'
                ? storage_path('app/private/'.$file->file_url)
                : $file->file_url;

            // Validación de la existencia del archivo
            if (! Storage::disk($disk)->exists($file->file_url) || ! file_exists($path)) {
                abort(404, __('files.file_not_found'));
            }

            // Incrementar visitas
            $this->incrementVisits($id);

            // Retorna la respuesta adecuada según el tipo de disco
            return $disk === 'local'
                ? response()->file($path)
                : Storage::disk($disk)->response($file->file_url, $file->file_name ?: $file->original_file_name);
        } catch (Exception $exception) {
            // Manejo de errores
            Log::error($exception->getMessage());

            abort(404, __('files.file_retrieval_error'));
        }
    }

    public function viewFile($id)
    {
        $file = Files::findOrFail($id);

        return view('admin.files.view', compact('file'));
    }

    public function incrementVisits($id): void
    {
        $file = Files::findOrFail($id);
        $file->increment('visits_count');
    }

    protected function getFileUrl($disk, $file_path)
    {
        if ($disk === 'public') {
            return asset('/storage/'.$file_path);
        }

        return Storage::disk($disk)->url($file_path);
    }
}
