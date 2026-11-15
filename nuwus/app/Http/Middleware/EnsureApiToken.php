<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Halaman admin hanya bisa dibuka setelah login (token dari Django tersimpan di session). */
class EnsureApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
