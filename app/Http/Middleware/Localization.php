<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } elseif (auth()->check() && auth()->user()->locale) {
            App::setLocale(auth()->user()->locale);
            Session::put('locale', auth()->user()->locale);
        }
        return $next($request);
    }
}