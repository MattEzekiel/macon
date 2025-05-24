<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Products;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index(): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');

        $productsQuery = Products::select([
            'id',
            'name',
            'client_id',
            'description',
            'brand',
            'model',
            'origin',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        if ($isClient && auth()->check()) {
            $productsQuery->where('client_id', auth()->user()->client_id);
        } else {
            $productsQuery->when(request()->client, function ($query, $id) {
                $query->where('client_id', $id);
            });
        }

        $productsQuery->when(request()->name, function ($query, $name) {
            $query->where('name', 'like', '%'.$name.'%');
        });

        $productsQuery->when(request()->brand, function ($query, $brand) {
            $query->where('brand', 'like', '%'.$brand.'%');
        });

        $productsQuery->when(request()->model, function ($query, $model) {
            $query->where('model', 'like', '%'.$model.'%');
        });

        $productsQuery->when(request()->origin, function ($query, $origin) {
            $query->where('origin', 'like', '%'.$origin.'%');
        });

        $productsQuery->when(request()->deleted, function ($query, $deletion) {
            if ($deletion == '1') {
                $query->onlyTrashed();
            } elseif ($deletion == '2') {
                $query->withTrashed();
            }
        });

        $products = $productsQuery
            ->with('client', 'files')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $view = $isClient ? 'client.products.index' : 'admin.products.index';

        return view($view, [
            'products' => $products,
            'searchName' => request()->name,
        ]);
    }

    public function newProduct(): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $form_data = (new Products)->getFormData();
        if ($isClient) {
            return view('client.products.new-product', compact('form_data'));
        } else {
            $clients = Clients::select('id', 'legal_name')->get();

            return view('admin.products.new-product', compact('clients', 'form_data'));
        }
    }

    public function editProduct(int $id): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $product = Products::findOrFail($id);
        $form_data = (new Products)->getFormData();
        if ($isClient) {
            if (! auth()->check() || $product->client_id !== auth()->user()->client_id) {
                abort(403, __('products.edit_permission_error'));
            }

            return view('client.products.edit-product', compact('product', 'form_data'));
        } else {
            $clients = Clients::select('id', 'legal_name')->get();

            return view('admin.products.edit-product', compact('product', 'clients', 'form_data'));
        }
    }

    public function ProductStore(Request $request): RedirectResponse
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'origin' => 'required',
            ] + ($isClient ? [] : ['client' => 'required']),
            [
                'name.required' => __('products.name').' es requerido',
                'client.required' => __('products.client').' es requerido',
                'description.required' => __('products.description').' es requerido',
                'brand.required' => __('products.brand').' es requerido',
                'model.required' => __('products.model').' es requerido',
                'origin.required' => __('products.origin').' es requerido',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->with('error', __('products.created_error'))
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $validated = $validator->validated();
            $product = new Products;
            $product->fill($validated);

            if ($isClient) {
                if (auth()->check() && auth()->user()->client_id) {
                    $product->client_id = auth()->user()->client_id;
                } else {
                    return back()->with('error', __('products.client_not_found_error'))->withInput();
                }
            } elseif (isset($validated['client'])) {
                $client = Clients::findOrFail($validated['client']);
                $product->client_id = $client->id;
            } else {
                return back()->with('error', __('products.client_selection_required'))->withInput();
            }

            $product->save();

            $routeName = $isClient ? 'client.new.files' : 'admin.new.files';

            return redirect()->route($routeName, ['id' => $product->id])->with('success', __('products.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('products.created_error'))->withInput();
        }
    }

    public function ProductUpdate(int $id, Request $request): RedirectResponse
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'origin' => 'required',
        ];

        if (! $isClient) {
            $rules['client'] = 'required';
        }

        $messages = [
            'name.required' => __('products.name').' es requerido',
            'client.required' => __('products.client').' es requerido',
            'description.required' => __('products.description').' es requerido',
            'brand.required' => __('products.brand').' es requerido',
            'model.required' => __('products.model').' es requerido',
            'origin.required' => __('products.origin').' es requerido',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            $messages
        );

        if ($validator->fails()) {
            return back()
                ->with('error', __('products.created_error'))
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $validated = $validator->validated();
            $product = Products::findOrFail($id);

            if ($isClient && auth()->check() && $product->client_id !== auth()->user()->client_id) {
                abort(403, __('products.edit_permission_error'));
            }

            $product->fill($validated);

            if (! $isClient && isset($validated['client'])) {
                $client = Clients::findOrFail($validated['client']);
                $product->client_id = $client->id;
            }

            $product->save();

            if ($request->submit_action === 'update_and_continue') {
                $routeName = $isClient ? 'client.edit.files' : 'admin.edit.files';

                return redirect()->route($routeName, ['id' => $product->id])->with('success', __('products.updated_successfully'));
            } else {
                $routeName = $isClient ? 'client.products' : 'admin.products';

                return redirect()->route($routeName)->with('success', __('products.updated_successfully'));
            }
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('products.updated_error'))->withInput();
        }
    }

    public function ProductDelete(int $id): RedirectResponse
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        try {
            $product = Products::findOrFail($id);
            if ($isClient) {
                if (! auth()->check() || $product->client_id !== auth()->user()->client_id) {
                    return back()->with('error', __('products.delete_permission_error'));
                }
            }
            $product->delete();

            return redirect()->back()->with('success', __('products.deleted_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('products.deleted_error'));
        }
    }

    public function ProductRestore(int $id): RedirectResponse
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        try {
            $product = Products::withTrashed()->findOrFail($id);
            if ($isClient) {
                if (! auth()->check() || $product->client_id !== auth()->user()->client_id) {
                    return back()->with('error', __('products.restore_permission_error'));
                }
            }
            $product->restore();

            return redirect()->back()->with('success', __('products.restored_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('products.restored_error'));
        }
    }
}
