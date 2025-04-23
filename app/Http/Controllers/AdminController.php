<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\Admin;
use App\Models\Clients;
use App\Models\Files;
use App\Models\PasswordResetToken;
use App\Models\Products;
use App\Traits\FileSizeFormatter;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use FileSizeFormatter;

    public function index(): View|Application|Factory
    {
        // Tamaño total de los archivos
        $totalFileSize = Files::sum('file_size');
        $formattedFileSize = $this->formatFileSize($totalFileSize);

        // Total de archivos
        $totalFiles = Files::count();

        // Clientes con más archivos
        $topClients = Clients::withCount('files')
            ->orderByDesc('files_count')
            ->take(5)
            ->get();

        // Productos con más visitas
        $topProducts = Products::withSum('qrs as visit_count', 'visits_count')
            ->orderByDesc('visit_count')
            ->take(5)
            ->get();

        // Archivos con más visitas
        $topQRs = Files::orderByDesc('visits_count')
            ->take(5)
            ->get();

        $topUsersPerClients = Clients::withCount('users')
            ->orderByDesc('users_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'formattedFileSize',
            'totalFiles',
            'topClients',
            'topProducts',
            'topQRs',
            'topUsersPerClients',
        ));
    }

    public function loginForm(): View|Application|Factory
    {
        return view('admin.login');
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

        if (auth('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', __('auth.invalid_credentials'))->withInput();
    }

    public function logout(): RedirectResponse
    {
        auth('admin')->logout();

        return redirect()->route('admin.login');
    }

    public function forgotPassword(): View|Application|Factory
    {
        return view('admin.forgot-password');
    }

    public function restorePassword(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => __('auth.email_required'),
                'email.email' => __('auth.email_invalid'),
            ]
        );

        if ($validator->fails()) {
            return back()
                ->with('error', __('auth.email_required'))
                ->withErrors($validator)
                ->withInput();
        }

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin) {
            return back()->with('error', __('auth.email_invalid'))->withInput();
        }

        $token = Str::random(60);

        try {
            PasswordResetToken::updateOrInsert(
                [
                    'email' => $admin->email,
                ],
                [
                    'email' => $admin->email,
                    'token' => $token,
                    'created_at' => now(),
                ]
            );

            Mail::to($admin->email)->send(new ResetPasswordMail($token, $admin->name, 'admin.restore.password.token'));

            return back()->with('success', __('auth.password_reset_sent'));
        } catch (Exception $e) {
            if (env('APP_ENV') === 'local') {
                Log::error($e->getMessage());
            }

            return back()->with('error', __('auth.email_send_error'));
        }
    }

    public function restorePasswordForm(string $token): View|Application|Factory
    {
        return view('admin.reset-password', compact('token'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|same:password_confirmed',
                'password_confirmed' => 'required',
            ],
            [
                'token.required' => __('auth.token_invalid'),
                'email.required' => __('auth.email_required'),
                'email.email' => __('auth.email_invalid'),
                'password.required' => __('auth.password_required'),
                'password.min' => __('auth.password_min'),
                'password.same' => __('auth.password_mismatch'),
                'password_confirmed.required' => __('auth.confirm_password_required'),
            ]
        );

        if ($validator->fails()) {
            return back()
                ->with('error', __('auth.form_error'))
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $passwordReset = PasswordResetToken::where([
                ['token', $request->token],
                ['email', $request->email],
            ])->first();

            if (! $passwordReset || $passwordReset->created_at->addMinutes(60)->isPast()) {
                return back()->with('error', __('auth.token_invalid'))->withInput();
            }

            $admin = Admin::where('email', $request->email)->first();
            $admin->password = Hash::make($request->password);
            $admin->save();

            PasswordResetToken::where('email', $request->email)->delete();

            return redirect()->route('admin.login');
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return back()->with('error', __('auth.error_occurred'))->withInput();
        }
    }

    public function changeLanguage(Request $request): RedirectResponse
    {
        $request->validate([
            'language' => 'required|in:en,es'
        ]);

        session(['locale' => $request->language]);
        app()->setLocale($request->language);

        return back();
    }
}
