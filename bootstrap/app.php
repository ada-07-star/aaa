<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {

            // API Routes
            Route::prefix('api/v1/app')
                ->group(base_path('routes\api\v1\api.php'));

            // Admin Routes
            Route::middleware(['api', \App\Http\Middleware\AdminMiddleware::class])
                ->prefix('api/v1/admin')
                ->name('admin.')
                ->group(base_path('routes/admin/v1/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth:api' => Tymon\JWTAuth\Providers\LumenServiceProvider::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
