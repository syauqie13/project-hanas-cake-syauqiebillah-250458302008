<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware ForceJsonResponse
 *
 * Memaksa semua request ke route /api/* agar:
 * 1. Menerima header Accept: application/json
 * 2. Mengembalikan response dalam format JSON (termasuk error 404, 500, dll.)
 *
 * Tanpa middleware ini, Laravel bisa mengembalikan HTML error page
 * saat client (Flutter) tidak mengirim header Accept yang benar.
 */
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // Paksa header Accept menjadi JSON agar Laravel
        // selalu merender error/exception sebagai JSON
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
