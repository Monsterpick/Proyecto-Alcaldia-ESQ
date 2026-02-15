<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\WelcomeController;

// Página pública principal con Inertia + React
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Formulario Alcaldía Digital (React) - rate limit: 5 solicitudes/min por IP
Route::post('/solicitud', [WelcomeController::class, 'storeSolicitud'])
    ->middleware('throttle:5,1')
    ->name('solicitud.store');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Ruta de perfil movida a admin.php
    // Route::get('/settings/profile', function () {
    //     return view('livewire.pages.patient.settings.profile');
    // })->name('settings.profile');



    /* Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance'); */
});

require __DIR__.'/auth.php';
