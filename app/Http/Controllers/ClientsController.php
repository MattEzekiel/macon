<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
{
    public function index(Request $request): View|Application|Factory
    {
        $data = Clients::select(['id', 'legal_name', 'tax_id', 'contact_name', 'contact_email', 'contact_phone', 'legal_address', 'created_at', 'updated_at', 'deleted_at']);

        $data->when($request->deleted, function ($query, $deletion) {
            if ($deletion == '1') {
                $query->onlyTrashed();
            } elseif ($deletion == '2') {
                $query->withTrashed();
            }
        });

        $data->when($request->client, function ($query, $id) {
            $query->where('id', $id);
        });

        $clients = $data
            ->with(['products', 'products.files', 'qrs'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        foreach ($clients as $client) {
            $fileCount = 0;
            if ($client->products->count() > 0) {
                foreach ($client->products as $product) {
                    $fileCount += $product->files->count();
                }
            }
            $client->files_count = $fileCount;
        }

        return view('admin.clients.index', compact('clients'));
    }

    public function newClient(): View|Application|Factory
    {
        $form_data = (new Clients)->getFormData();

        return view('admin.clients.new-client', compact('form_data'));
    }

    public function editClient(int $id): View|Application|Factory
    {
        $form_data = (new Clients)->getFormData();
        $client = Clients::findOrFail($id);

        return view('admin.clients.edit-client', compact('form_data', 'client'));
    }

    public function ClientStore(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'legal_name' => 'required',
                'tax_id' => 'required',
                'contact_name' => 'required',
                'contact_email' => 'required|email',
                'contact_phone' => 'required',
                'legal_address' => 'required',
            ],
            [
                'legal_name.required' => __('clients.legal_name').' es requerido',
                'tax_id.required' => __('clients.tax_id').' es requerido',
                'contact_name.required' => __('clients.contact_name').' es requerido',
                'contact_email.required' => __('clients.contact_email').' es requerido',
                'contact_email.email' => __('clients.contact_email').' es inválido',
                'contact_phone.required' => __('clients.contact_phone').' es requerido',
                'legal_address.required' => __('clients.legal_address').' es requerido',
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
            $client = new Clients;
            $client->fill($validated);
            $client->save();

            return redirect()->route('admin.clients')->with('success', __('clients.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('clients.created_error'))->withInput();
        }
    }

    public function ClientUpdate(int $id, Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'legal_name' => 'required',
                'tax_id' => 'required',
                'contact_name' => 'required',
                'contact_email' => 'required|email',
                'contact_phone' => 'required',
                'legal_address' => 'required',
            ],
            [
                'legal_name.required' => __('clients.legal_name').' es requerido',
                'tax_id.required' => __('clients.tax_id').' es requerido',
                'contact_name.required' => __('clients.contact_name').' es requerido',
                'contact_email.required' => __('clients.contact_email').' es requerido',
                'contact_email.email' => __('clients.contact_email').' es inválido',
                'contact_phone.required' => __('clients.contact_phone').' es requerido',
                'legal_address.required' => __('clients.legal_address').' es requerido',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->with('error', __('products.updated_error'))
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $validated = $validator->validated();
            $client = Clients::findOrFail($id);

            $client->fill($validated);
            $client->save();

            return redirect()->route('admin.clients')->with('success', __('clients.updated_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('clients.updated_error'))->withInput();
        }
    }

    public function ClientDelete(int $id): RedirectResponse
    {
        try {
            $client = Clients::findOrFail($id);
            $client->delete();

            return redirect()->route('admin.clients')->with('success', __('clients.deleted_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('clients.deleted_error'));
        }
    }

    public function ClientRestore(int $id): RedirectResponse
    {
        try {
            $client = Clients::withTrashed()->findOrFail($id);
            $client->restore();

            return redirect()->back()->with('success', __('clients.restored_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('clients.restored_error'));
        }
    }
}
