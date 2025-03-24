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
            'updated_at'
        ]);

        $products = $products
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
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
        try {
            $validated = $request->validate(
                [
                    'name' => 'required',
                    'client' => 'required',
                    'description' => 'required',
                    'brand' => 'required',
                    'model' => 'required',
                    'origin' => 'required',
                ],
                [
                    'name.required' => __('products.name') . ' es requerido',
                    'client.required' => __('products.client') . ' es requerido',
                    'description.required' => __('products.description') . ' es requerido',
                    'brand.required' => __('products.brand') . ' es requerido',
                    'model.required' => __('products.model') . ' es requerido',
                    'origin.required' => __('products.origin') . ' es requerido',
                ]
            );

            $product = new Products();
            $product->fill($validated);
            $client = Clients::findOrFail($validated['client']);
            $product->client_id = $client->id;

            $product->save();

            return redirect()->route('admin.products')->with('success', __('products.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', __('products.created_error'));
        }
    }

    public function ProductUpdate(int $id, Request $request): RedirectResponse
    {
        $product = Products::findOrFail($id);

        try {
            $validated = $request->validate(
                [
                    'name' => 'required',
                    'client' => 'required',
                    'description' => 'required',
                    'brand' => 'required',
                    'model' => 'required',
                    'origin' => 'required',
                ],
                [
                    'name.required' => __('products.name') . ' es requerido',
                    'client.required' => __('products.client') . ' es requerido',
                    'description.required' => __('products.description') . ' es requerido',
                    'brand.required' => __('products.brand') . ' es requerido',
                    'model.required' => __('products.model') . ' es requerido',
                    'origin.required' => __('products.origin') . ' es requerido',
                ]
            );

            $product->fill($validated);
            $client = Clients::findOrFail($validated['client']);
            $product->client_id = $client->id;

            $product->save();

            return redirect()->route('admin.products')->with('success', __('products.updated_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', __('products.updated_error'));
        }
    }
}
