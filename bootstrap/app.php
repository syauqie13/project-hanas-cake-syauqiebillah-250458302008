<?php

use App\Http\Middleware\CheckIsAdmin;
use App\Http\Middleware\CheckIsKaryawan;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is.admin' => CheckIsAdmin::class,
            'is.karyawan' => CheckIsKaryawan::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/midtrans/webhook', // <-- URL Anda
            'api/midtrans/webhook/*' // <-- Opsional, untuk jaga-jaga
        ]);
        $middleware->trustProxies(at: ['*']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
