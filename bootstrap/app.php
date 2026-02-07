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
            // Admin authentication routes (guest access)
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(function() {
                    Route::middleware('guest')->group(function () {
                        \Livewire\Volt\Volt::route('/login', 'pages.admin.auth.login')
                            ->name('login');
                        
                        /* \Livewire\Volt\Volt::route('/register', 'pages.admin.auth.register')
                            ->name('register');
                        
                        \Livewire\Volt\Volt::route('/forgot-password', 'pages.admin.auth.forgot-password')
                            ->name('password.request');
                        
                        \Livewire\Volt\Volt::route('/reset-password/{token}', 'pages.admin.auth.reset-password')
                            ->name('password.reset'); */
                    });
                });
            
            // Protected admin routes
            Route::middleware('web', 'auth', 'verified', 'role:admin|Super Admin|Doctor|Recepcionista|Administrador')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        /* Use the following middleware to check for roles and permissions in routes files */
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
