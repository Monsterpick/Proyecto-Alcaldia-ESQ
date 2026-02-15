<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        /* Routes for admin prefix */
        then: function() {
            // /admin/login redirige al login único en /login
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(function() {
                    Route::redirect('/login', '/login', 301)->name('login');
                });
            
            // Protected admin routes (prevent.concurrent = sesión única para Alcalde, Analista, Operador)
            Route::middleware('web', 'auth', 'verified', 'prevent.concurrent', 'role:admin|Super Admin|Alcalde|Analista|Operador|Director|Doctor|Recepcionista|Administrador')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Inertia middleware para compartir datos con React
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        /* Use the following middleware to check for roles and permissions in routes files */
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'prevent.concurrent' => \App\Http\Middleware\PreventConcurrentSessions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
