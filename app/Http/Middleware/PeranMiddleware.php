<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeranMiddleware
{
    public function handle(Request $request, Closure $next, string $peran): Response
    {
        $user = $request->user();

        if (! $user || $user->peran !== $peran) {
            abort(403, 'Anda tidak berhak mengakses halaman ini.');
        }

        return $next($request);
    }
}
