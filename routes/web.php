<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
