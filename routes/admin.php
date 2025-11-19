<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PaymentReceiptController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MapController;

Route::get('/', function () {
    return view('welcome');
})->name('home');



Route::middleware(['auth'])->group(function () {

    //Ruta de Dashboard
    Volt::route('/dashboard', 'pages.admin.dashboard.index')->name('dashboard');

    // Rutas para la gestión de usuarios
    Volt::route('/users', 'pages.admin.users.index')
        ->middleware('permission:view-user')
        ->name('users.index');

    Volt::route('/users/create', 'pages.admin.users.create')
        ->middleware('permission:create-user')
        ->name('users.create');

    Volt::route('/users/{user}/edit', 'pages.admin.users.edit')
        ->middleware('permission:edit-user')
        ->name('users.edit');

    Volt::route('/users/{user}/show', 'pages.admin.users.show')
        ->middleware('permission:view-user')
        ->name('users.show');

    Volt::route('/users/{user}', 'pages.admin.users.destroy')
        ->middleware('permission:delete-user')
        ->name('users.destroy');

    // Rutas para la gestión de roles
    Volt::route('/roles', 'pages.admin.roles.index')
        ->middleware('permission:view-role')
        ->name('roles.index');

    Volt::route('/roles/create', 'pages.admin.roles.create')
        ->middleware('permission:create-role')
        ->name('roles.create');

    Volt::route('/roles/{role}/edit', 'pages.admin.roles.edit')
        ->middleware('permission:edit-role')
        ->name('roles.edit');

    Volt::route('/roles/{role}/show', 'pages.admin.roles.show')
        ->middleware('permission:view-role')
        ->name('roles.show');

    Volt::route('/roles/{role}', 'pages.admin.roles.destroy')
        ->middleware('permission:delete-role')
        ->name('roles.destroy');

    // Rutas para la gestión de permisos
    Volt::route('/permissions', 'pages.admin.permissions.index')
        ->middleware('permission:view-permission')
        ->name('permissions.index');

    Volt::route('/permissions/create', 'pages.admin.permissions.create')
        ->middleware('permission:create-permission')
        ->name('permissions.create');

    Volt::route('/permissions/{permission}/edit', 'pages.admin.permissions.edit')
        ->middleware('permission:edit-permission')
        ->name('permissions.edit');

    Volt::route('/permissions/{permission}/show', 'pages.admin.permissions.show')
        ->middleware('permission:view-permission')
        ->name('permissions.show');

    Volt::route('/permissions/{permission}', 'pages.admin.permissions.destroy')
        ->middleware('permission:delete-permission')
        ->name('permissions.destroy');

    // Rutas para la gestión de estados
    Volt::route('/estados', 'pages.admin.estados.index')
        ->middleware('permission:view-estado')
        ->name('estados.index');

    Volt::route('/estados/create', 'pages.admin.estados.create')
        ->middleware('permission:create-estado')
        ->name('estados.create');

    Volt::route('/estados/{estado}/edit', 'pages.admin.estados.edit')
        ->middleware('permission:edit-estado')
        ->name('estados.edit');

    Volt::route('/estados/{estado}/show', 'pages.admin.estados.show')
        ->middleware('permission:view-estado')
        ->name('estados.show');

    Volt::route('/estados/{estado}', 'pages.admin.estados.destroy')
        ->middleware('permission:delete-estado')
        ->name('estados.destroy');

    // Rutas para la gestión de municipios
    Volt::route('/municipios', 'pages.admin.municipios.index')
        ->middleware('permission:view-municipio')
        ->name('municipios.index');

    Volt::route('/municipios/create', 'pages.admin.municipios.create')
        ->middleware('permission:create-municipio')
        ->name('municipios.create');

    Volt::route('/municipios/{municipio}/edit', 'pages.admin.municipios.edit')
        ->middleware('permission:edit-municipio')
        ->name('municipios.edit');

    Volt::route('/municipios/{municipio}/show', 'pages.admin.municipios.show')
        ->middleware('permission:view-municipio')
        ->name('municipios.show');

    Volt::route('/municipios/{municipio}', 'pages.admin.municipios.destroy')
        ->middleware('permission:delete-municipio')
        ->name('municipios.destroy');

    // Rutas para la gestión de parroquia
    Volt::route('/parroquias', 'pages.admin.parroquias.index')
        ->middleware('permission:view-parroquia')
        ->name('parroquias.index');

    Volt::route('/parroquias/create', 'pages.admin.parroquias.create')
        ->middleware('permission:create-parroquia')
        ->name('parroquias.create');

    Volt::route('/parroquias/{parroquia}/edit', 'pages.admin.parroquias.edit')
        ->middleware('permission:edit-parroquia')
        ->name('parroquias.edit');

    Volt::route('/parroquias/{parroquia}/show', 'pages.admin.parroquias.show')
        ->middleware('permission:view-parroquia')
        ->name('parroquias.show');

    Volt::route('/parroquias/{parroquia}', 'pages.admin.parroquias.destroy')
        ->middleware('permission:delete-parroquia')
        ->name('parroquias.destroy');

    // Rutas para la gestión de circuitos comunales (solo lectura)
    Volt::route('/circuitos-comunales', 'pages.admin.circuitos-comunales.index')
        ->middleware('permission:view-parroquia')
        ->name('circuitos-comunales.index');

    // Rutas para la gestión de tipos de pago
    Volt::route('/payment-types', 'pages.admin.payment-types.index')
        ->middleware('permission:view-payment-type')
        ->name('payment-types.index');

    Volt::route('/payment-types/create', 'pages.admin.payment-types.create')
        ->middleware('permission:create-payment-type')
        ->name('payment-types.create');

    Volt::route('/payment-types/{paymentType}/edit', 'pages.admin.payment-types.edit')
        ->middleware('permission:edit-payment-type')
        ->name('payment-types.edit');

    Volt::route('/payment-types/{paymentType}/show', 'pages.admin.payment-types.show')
        ->middleware('permission:view-payment-type')
        ->name('payment-types.show');

    Volt::route('/payment-types/{paymentType}', 'pages.admin.payment-types.destroy')
        ->middleware('permission:delete-payment-type')
        ->name('payment-types.destroy');

    // Rutas para la gestión de orígenes de pago
    Volt::route('/payment-origins', 'pages.admin.payment-origins.index')
        ->middleware('permission:view-payment-origin')
        ->name('payment-origins.index');

    Volt::route('/payment-origins/create', 'pages.admin.payment-origins.create')
        ->middleware('permission:create-payment-origin')
        ->name('payment-origins.create');

    Volt::route('/payment-origins/{paymentOrigin}/edit', 'pages.admin.payment-origins.edit')
        ->middleware('permission:edit-payment-origin')
        ->name('payment-origins.edit');

    Volt::route('/payment-origins/{paymentOrigin}/show', 'pages.admin.payment-origins.show')
        ->middleware('permission:view-payment-origin')
        ->name('payment-origins.show');

    Volt::route('/payment-origins/{paymentOrigin}', 'pages.admin.payment-origins.destroy')
        ->middleware('permission:delete-payment-origin')
        ->name('payment-origins.destroy');

    // Rutas para la gestión de configuración
    Volt::route('/settings', 'pages.admin.settings.index')
        ->middleware('permission:view-setting')
        ->name('settings.index');

    Route::get('/payments/{payment}/receipt/{type}', [PaymentReceiptController::class, 'download'])
        ->name('payments.receipt.download');

    Volt::route('/settings/general', 'pages.admin.settings.general')
        ->middleware('permission:view-setting')
        ->name('settings.general');

    Volt::route('/settings/logo', 'pages.admin.settings.logo')
        ->middleware('permission:view-setting')
        ->name('settings.logo');

    Volt::route('/settings/profile', 'pages.admin.profile.index')
        ->middleware('permission:profile-setting')
        ->name('settings.profile');

    // Rutas para Categorías de Inventario
    Volt::route('/categories', 'pages.admin.categories.index')
        ->middleware('permission:view-category')
        ->name('categories.index');

    // Rutas para Almacenes
    Volt::route('/warehouses', 'pages.admin.warehouses.index')
        ->middleware('permission:view-warehouse')
        ->name('warehouses.index');

    // Rutas para Productos
    Volt::route('/products', 'pages.admin.products.index')
        ->middleware('permission:view-product')
        ->name('products.index');

    // Rutas para Ajustes de Stock
    Volt::route('/stock-adjustments', 'pages.admin.stock-adjustments.index')
        ->middleware('permission:view-stock-adjustment')
        ->name('stock-adjustments.index');

    // Rutas para Movimientos de Inventario
    Volt::route('/inventory-entries', 'pages.admin.inventory-entries.index')
        ->middleware('permission:view-inventory-entry')
        ->name('inventory-entries.index');

    Volt::route('/inventory-exits', 'pages.admin.inventory-exits.index')
        ->middleware('permission:view-inventory-exit')
        ->name('inventory-exits.index');

    Volt::route('/movements', 'pages.admin.movements.index')
        ->middleware('permission:view-movement')
        ->name('movements.index');

    // Rutas para Beneficiarios
    Route::get('/beneficiaries', \App\Livewire\Pages\Admin\Beneficiaries\Index::class)
        ->name('beneficiaries.index');
    
    Route::get('/beneficiaries/create', \App\Livewire\Pages\Admin\Beneficiaries\Create::class)
        ->name('beneficiaries.create');
    
    Route::get('/beneficiaries/{id}/edit', \App\Livewire\Pages\Admin\Beneficiaries\Edit::class)
        ->name('beneficiaries.edit');

    // Rutas para Reportes de Entregas
    Volt::route('/reports', 'pages.admin.reports.index')
        ->name('reports.index');

    Volt::route('/reports/create', 'pages.admin.reports.create')
        ->name('reports.create');

    Volt::route('/reports/{id}', 'pages.admin.reports.show')
        ->name('reports.show');

    Volt::route('/reports/{id}/edit', 'pages.admin.reports.edit')
        ->name('reports.edit');

    // Rutas para el Mapa de Geolocalización
    Volt::route('/map', 'pages.admin.map.index')
        ->name('map.index');

    // Rutas para el Registro de Actividades
    Volt::route('/activity-logs', 'pages.admin.activity-logs.index')
        ->name('activity-logs.index');

});

/* Route::middleware('guest')->group(function () {
    Volt::route('/login', 'pages.admin.auth.login')
        ->name('login');
    
    Volt::route('/register', 'pages.admin.auth.register')
        ->name('register');
    
    Volt::route('/forgot-password', 'pages.admin.auth.forgot-password')
        ->name('password.request');
    
    Volt::route('/reset-password/{token}', 'pages.admin.auth.reset-password')
        ->name('password.reset');
}); */
// Admin authentication routes moved to bootstrap/app.php

/* Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
}); */


Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
