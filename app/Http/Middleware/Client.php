<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Client
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            app()->setLocale('es');
            session(['locale' => 'es']);
        }

        return $next($request);
    }
}
