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
        $products = Products::select([
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

        $products->when(request()->client, function ($query, $id) {
            $query->where('client_id', $id);
        });

        $products->when(request()->name, function ($query, $name) {
            $query->where('name', 'like', '%'.$name.'%');
        });

        $products->when(request()->brand, function ($query, $brand) {
            $query->where('brand', 'like', '%'.$brand.'%');
        });

        $products->when(request()->model, function ($query, $model) {
            $query->where('model', 'like', '%'.$model.'%');
        });

        $products->when(request()->origin, function ($query, $origin) {
            $query->where('origin', 'like', '%'.$origin.'%');
        });

        $products->when(request()->deleted, function ($query, $deletion) {
            if ($deletion == '1') {
                $query->onlyTrashed();
            } elseif ($deletion == '2') {
                $query->withTrashed();
            }
        });

        $products = $products
            ->with('client', 'files')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'searchName' => request()->name
        ]);
    }

    public function newProduct(): View|Application|Factory
    {
        $clients = Clients::select('id', 'legal_name')->get();
        $form_data = (new Products)->getFormData();

        return view('admin.products.new-product', compact('clients', 'form_data'));
    }

    public function editProduct(int $id): View|Application|Factory
    {
        $product = Products::findOrFail($id);
        $clients = Clients::select('id', 'legal_name')->get();
        $form_data = (new Products)->getFormData();

        return view('admin.products.edit-product', compact('product', 'clients', 'form_data'));
    }

    public function ProductStore(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'client' => 'required',
                'description' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'origin' => 'required',
            ],
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
            $client = Clients::findOrFail($validated['client']);
            $product->client_id = $client->id;

            $product->save();

            return redirect()->route('admin.new.files', ['id' => $product->id])->with('success', __('products.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('products.created_error'))->withInput();
        }
    }

    public function ProductUpdate(int $id, Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'client' => 'required',
                'description' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'origin' => 'required',
            ],
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
            $product = Products::findOrFail($id);

            $product->fill($validated);
            $client = Clients::findOrFail($validated['client']);
            $product->client_id = $client->id;

            $product->save();

            if ($request->submit_action) {
                return redirect()->route('admin.edit.files', ['id' => $product->id])->with('success', __('products.updated_successfully'));
            }

            return redirect()->route('admin.products')->with('success', __('products.updated_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('products.updated_error'))->withInput();
        }
    }

    public function ProductDelete(int $id): RedirectResponse
    {
        try {
            $product = Products::findOrFail($id);
            $product->delete();

            return redirect()->route('admin.products')->with('success', __('products.deleted_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('products.deleted_error'));
        }
    }

    public function ProductRestore(int $id): RedirectResponse
    {
        try {
            $product = Products::withTrashed()->findOrFail($id);
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
