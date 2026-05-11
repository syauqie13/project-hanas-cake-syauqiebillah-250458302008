<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckIsPelanggan
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login & rolenya adalah 'pelanggan'
        if (Auth::check() && Auth::user()->role == 'pelanggan') {
            
            $user = Auth::user();

            // --- LOGIKA PAKSA BUAT PIN ---
            // Syarat: Email sudah verif, PIN masih kosong, dan BUKAN sedang di halaman setup-pin
            if ($user->email_verified_at && 
                is_null($user->payment_pin) && 
                !$request->routeIs('pelanggan.setup-pin')) { 
                
                return redirect()->route('pelanggan.setup-pin');
            }

            // Jika semua syarat terpenuhi, izinkan request
            return $next($request);
        }

        // Jika tidak login/bukan pelanggan, kembalikan ke login
        return redirect(route('login'))->with('error', 'Anda harus login sebagai pelanggan untuk mengakses halaman ini.');
    }
}