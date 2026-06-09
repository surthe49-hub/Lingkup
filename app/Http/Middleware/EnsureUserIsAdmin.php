<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan hanya user dengan role 'admin'
     * yang dapat mengakses route yang dilindungi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Step 1: Pastikan user sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Step 2: Cek role
        if (!$request->user()->isAdmin()) {
            // User biasa yang mencoba akses /admin/* di-redirect ke dashboard mereka
            // dengan pesan error
            return redirect()
                ->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}