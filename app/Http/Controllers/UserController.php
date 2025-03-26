<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(): View|Application|Factory
    {
        $users = User::select('id', 'email', 'name', 'client_id')
            ->with('client');

        $users = $users->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function newUser(): View|Application|Factory
    {
        $clients = Clients::select('id', 'legal_name')->get();
        $form_data = (new User)->getFormData();
        return view('admin.users.new-user', compact('form_data', 'clients'));
    }

    public function editUser(int $id): View|Application|Factory
    {
        $user = User::findOrFail($id);
        $clients = Clients::select('id', 'legal_name')->get();
        $form_data = (new User)->getFormData();
        return view('admin.users.edit-user', compact('user', 'clients', 'form_data'));
    }

    public function UserStore(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'client' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ],
            [
                'name.required' => __('users.name') . ' es requerido',
                'client.required' => __('users.client') . ' es requerido',
                'email.required' => __('users.email') . ' es requerido',
                'password.required' => __('users.password') . ' es requerido',
                'password.min' => __('users.password_min') . ' es requerido',
                'confirm_password.required' => __('users.confirm_password') . ' es requerido',
                'confirm_password.same' => __('users.confirm_password') . ' y ' . __('users.password') . ' no coincide',
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
            $user = new User();
            $user->fill($validated);
            $client = Clients::findOrFail($validated['client']);
            $user->client_id = $client->id;

            $user->save();

            return redirect()->route('admin.users')->with('success', __('users.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', __('users.created_error'))->withInput();
        }
    }

    public function UserUpdate(int $id, Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'client' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ],
            [
                'name.required' => __('users.name') . ' es requerido',
                'client.required' => __('users.client') . ' es requerido',
                'email.required' => __('users.email') . ' es requerido',
                'password.required' => __('users.password') . ' es requerido',
                'password.min' => __('users.password_min') . ' es requerido',
                'confirm_password.required' => __('users.confirm_password') . ' es requerido',
                'confirm_password.same' => __('users.confirm_password') . ' y ' . __('users.password') . ' no coincide',
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
            $user = User::findOrFail($id);
            $user->fill($validated);
            $client = Clients::findOrFail($validated['client']);
            $user->client_id = $client->id;

            $user->save();

            return redirect()->route('admin.users')->with('success', __('users.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', __('users.created_error'))->withInput();
        }
    }

    public function UserDelete(int $id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.users')->with('success', __('users.deleted_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }
            return back()->with('error', __('users.deleted_error'));
        }
    }
}
