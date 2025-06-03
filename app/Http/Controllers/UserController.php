<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Files;
use App\Models\Products;
use App\Models\User;
use App\Traits\FileSizeFormatter;
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
    use FileSizeFormatter;

    public function index(): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $users = User::select('id', 'email', 'name', 'client_id')
            ->with('client');

        if ($isClient && auth()->check()) {
            $users->where('client_id', auth()->user()->client_id);
        } else {
            $users->when(request()->client, function ($query, $id) {
                $query->where('client_id', $id);
            });
        }

        $users->when(request()->name, function ($query, $name) {
            $query->where('name', 'like', '%'.$name.'%');
        });

        $users->when(request()->deleted, function ($query, $deletion) {
            if ($deletion == '1') {
                $query->onlyTrashed();
            } elseif ($deletion == '2') {
                $query->withTrashed();
            }
        });

        $users = $users->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $view = $isClient ? 'client.users.index' : 'admin.users.index';

        return view($view, compact('users'));
    }

    public function newUser(): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $form_data = (new User)->getFormData();
        if ($isClient) {
            return view('client.users.new-user', compact('form_data'));
        } else {
            $clients = Clients::select('id', 'legal_name')->get();

            return view('admin.users.new-user', compact('form_data', 'clients'));
        }
    }

    public function editUser(int $id): View|Application|Factory
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $user = User::findOrFail($id);
        $form_data = (new User)->getFormData();
        if ($isClient) {
            if (! auth()->check() || $user->client_id !== auth()->user()->client_id) {
                abort(403, __('users.edit_permission_error'));
            }

            return view('client.users.edit-user', compact('user', 'form_data'));
        } else {
            $clients = Clients::select('id', 'legal_name')->get();

            return view('admin.users.edit-user', compact('user', 'clients', 'form_data'));
        }
    }

    public function UserStore(Request $request): RedirectResponse
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ];
        $messages = [
            'name.required' => __('users.name').' es requerido',
            'email.required' => __('users.email').' es requerido',
            'password.required' => __('users.password').' es requerido',
            'password.min' => __('users.password_min').' es requerido',
            'confirm_password.required' => __('users.confirm_password').' es requerido',
            'confirm_password.same' => __('users.confirm_password').' y '.__('users.password').' no coincide',
        ];
        if (! $isClient) {
            $rules['client'] = 'required';
            $messages['client.required'] = __('users.client').' es requerido';
        }
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->with('error', __('users.created_error'))
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $validated = $validator->validated();
            $user = new User;
            $user->fill($validated);
            if ($isClient) {
                $user->client_id = auth()->user()->client_id;
            } else {
                $client = Clients::findOrFail($validated['client']);
                $user->client_id = $client->id;
            }
            $user->save();

            $route = $isClient ? 'client.users' : 'admin.users';

            return redirect()->route($route)->with('success', __('users.created_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('users.created_error'))->withInput();
        }
    }

    public function UserUpdate(int $id, Request $request): RedirectResponse
    {
        $isClient = str_starts_with(request()->route()->getName(), 'client.');
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'min:8',
            'confirm_password' => 'same:password',
        ];
        $messages = [
            'name.required' => __('users.name').' es requerido',
            'email.required' => __('users.email').' es requerido',
            'password.required' => __('users.password').' es requerido',
            'password.min' => __('users.password_min').' es requerido',
            'confirm_password.required' => __('users.confirm_password').' es requerido',
            'confirm_password.same' => __('users.confirm_password').' y '.__('users.password').' no coincide',
        ];
        if (! $isClient) {
            $rules['client'] = 'required';
            $messages['client.required'] = __('users.client').' es requerido';
        }
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->with('error', __('users.updated_error'))
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $validated = $validator->validated();
            $user = User::findOrFail($id);
            $user->fill($validated);
            if ($isClient) {
                if (! auth()->check() || $user->client_id !== auth()->user()->client_id) {
                    abort(403, __('users.edit_permission_error'));
                }
                $user->client_id = auth()->user()->client_id;
            } else {
                $client = Clients::findOrFail($validated['client']);
                $user->client_id = $client->id;
            }
            $user->save();

            $route = $isClient ? 'client.users' : 'admin.users';

            return redirect()->route($route)->with('success', __('users.updated_successfully'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('users.updated_error'))->withInput();
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

    public function loginForm(): View|Application|Factory
    {
        return view('client.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_invalid'),
            'password.required' => __('auth.password_required'),
        ]);

        $credentials = $request->only('email', 'password');

        if (auth('web')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('client.dashboard');
        }

        return back()->with('error', __('auth.invalid_credentials'))->withInput();
    }

    public function logout(): RedirectResponse
    {
        auth('web')->logout();

        return redirect()->route('client.login');
    }

    public function forgotPassword(): View|Application|Factory
    {
        return view('client.forgot-password');
    }

    public function dashboard(): View|Application|Factory
    {
        $client_id = auth()->user()->client_id;

        // Total de archivos del cliente
        $totalFiles = Files::whereHas('product', function ($query) use ($client_id) {
            $query->where('client_id', $client_id);
        })->count();

        // Tamaño total de archivos del cliente
        $totalFileSize = Files::whereHas('product', function ($query) use ($client_id) {
            $query->where('client_id', $client_id);
        })->sum('file_size');
        $formattedFileSize = $this->formatFileSize($totalFileSize);

        // Productos más visitados del cliente
        $topProducts = Products::where('client_id', $client_id)
            ->withSum('qrs as visit_count', 'visits_count')
            ->orderByDesc('visit_count')
            ->take(5)
            ->get();

        // Archivos más visitados del cliente
        $topQRs = Files::whereHas('product', function ($query) use ($client_id) {
            $query->where('client_id', $client_id);
        })
            ->orderByDesc('visits_count')
            ->take(5)
            ->get();

        // Usuarios del cliente
        $users = User::where('client_id', $client_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.dashboard', compact(
            'totalFiles',
            'formattedFileSize',
            'topProducts',
            'topQRs',
            'users'
        ));
    }

    public function changeLanguage(Request $request): RedirectResponse
    {
        $request->validate([
            'language' => 'required|in:es,en',
        ]);

        session(['locale' => $request->language]);
        app()->setLocale($request->language);

        return back();
    }
}
