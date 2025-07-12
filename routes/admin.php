<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PaymentReceiptController;

Route::get('/', function () {
    return view('welcome');
})->name('home');



Route::middleware(['auth'])->group(function () {

    //Ruta de Dashboard
    Volt::route('/dashboard', 'pages.admin.dashboard.index')->name('dashboard');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');

    // Rutas para la gestión de tenants
    Volt::route('/tenants', 'pages.admin.tenants.index')
        ->middleware('permission:view-tenant')
        ->name('tenants.index')
    ;

    Volt::route('/tenants/create', 'pages.admin.tenants.create')
        ->middleware('permission:create-tenant')
        ->name('tenants.create');

    Volt::route('/tenants/{tenant}/edit', 'pages.admin.tenants.edit')
        ->middleware('permission:edit-tenant')
        ->name('tenants.edit');

    Volt::route('/tenants/{tenant}/show', 'pages.admin.tenants.show')
        ->middleware('permission:view-tenant')
        ->name('tenants.show');

    Volt::route('/tenants/{tenant}/destroy', 'pages.admin.tenants.destroy')
        ->middleware('permission:delete-tenant')
        ->name('tenants.destroy');

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

    // Rutas para la gestión de actividad
    Volt::route('/actividads', 'pages.admin.actividads.index')
        ->middleware('permission:view-actividad')
        ->name('actividads.index');

    Volt::route('/actividads/create', 'pages.admin.actividads.create')
        ->middleware('permission:create-actividad')
        ->name('actividads.create');

    Volt::route('/actividads/{actividad}/edit', 'pages.admin.actividads.edit')
        ->middleware('permission:edit-actividad')
        ->name('actividads.edit');

    Volt::route('/actividads/{actividad}/show', 'pages.admin.actividads.show')
        ->middleware('permission:view-actividad')
        ->name('actividads.show');

    Volt::route('/actividads/{actividad}', 'pages.admin.actividads.destroy')
        ->middleware('permission:delete-actividad')
        ->name('actividads.destroy');

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

    // Rutas para la gestión de estatuses
    Volt::route('/estatuses', 'pages.admin.estatuses.index')
        ->middleware('permission:view-estatus')
        ->name('estatuses.index');

    Volt::route('/estatuses/create', 'pages.admin.estatuses.create')
        ->middleware('permission:create-estatus')
        ->name('estatuses.create');

    Volt::route('/estatuses/{estatus}/edit', 'pages.admin.estatuses.edit')
        ->middleware('permission:edit-estatus')
        ->name('estatuses.edit');

    Volt::route('/estatuses/{estatus}/show', 'pages.admin.estatuses.show')
        ->middleware('permission:view-estatus')
        ->name('estatuses.show');

    Volt::route('/estatuses/{estatus}', 'pages.admin.estatuses.destroy')
        ->middleware('permission:delete-estatus')
        ->name('estatuses.destroy');

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

    // Rutas para la gestión de plans
    Volt::route('/plans', 'pages.admin.plans.index')
        ->middleware('permission:view-plan')
        ->name('plans.index');

    Volt::route('/plans/create', 'pages.admin.plans.create')
        ->middleware('permission:create-plan')
        ->name('plans.create');

    Volt::route('/plans/{plan}/edit', 'pages.admin.plans.edit')
        ->middleware('permission:edit-plan')
        ->name('plans.edit');

    Volt::route('/plans/{plan}/show', 'pages.admin.plans.show')
        ->middleware('permission:view-plan')
        ->name('plans.show');

    Volt::route('/plans/{plan}', 'pages.admin.plans.destroy')
        ->middleware('permission:delete-plan')
        ->name('plans.destroy');

    // Rutas para la gestión de pagos
    Volt::route('/tenant-payments', 'pages.admin.tenant-payments.index')
        ->middleware('permission:view-tenant-payment')
        ->name('tenant-payments.index');

    Volt::route('/tenant-payments/create', 'pages.admin.tenant-payments.create')
        ->middleware('permission:create-tenant-payment')
        ->name('tenant-payments.create');

    Volt::route('/tenant-payments/{tenantPayment}/edit', 'pages.admin.tenant-payments.edit')
        ->middleware('permission:edit-tenant-payment')
        ->name('tenant-payments.edit');

    Volt::route('/tenant-payments/{tenantPayment}/show', 'pages.admin.tenant-payments.show')
        ->middleware('permission:view-tenant-payment')
        ->name('tenant-payments.show');

    Volt::route('/tenant-payments/{tenantPayment}', 'pages.admin.tenant-payments.destroy')
        ->middleware('permission:delete-tenant-payment')
        ->name('tenant-payments.destroy');

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

    // Rutas para la gestión de pacientes
    Volt::route('/patients', 'pages.admin.patients.index')
        ->middleware('permission:view-patient')
        ->name('patients.index');

    Volt::route('/patients/create', 'pages.admin.patients.create')
        ->middleware('permission:create-patient')
        ->name('patients.create');

    Volt::route('/patients/{patient}/edit', 'pages.admin.patients.edit')
        ->middleware('permission:edit-patient')
        ->name('patients.edit');

    Volt::route('/patients/{patient}/show', 'pages.admin.patients.show')
        ->middleware('permission:view-patient')
        ->name('patients.show');

    Volt::route('/patients/{patient}', 'pages.admin.patients.destroy')
        ->middleware('permission:delete-patient')
        ->name('patients.destroy');

    // Rutas para la gestión de especialidades
    Volt::route('/specialities', 'pages.admin.specialities.index')
        ->middleware('permission:view-speciality')
        ->name('specialities.index');

    Volt::route('/specialities/create', 'pages.admin.specialities.create')
        ->middleware('permission:create-speciality')
        ->name('specialities.create');

    Volt::route('/specialities/{speciality}/edit', 'pages.admin.specialities.edit')
        ->middleware('permission:edit-speciality')
        ->name('specialities.edit');

    Volt::route('/specialities/{speciality}/show', 'pages.admin.specialities.show')
        ->middleware('permission:view-speciality')
        ->name('specialities.show');

    Volt::route('/specialities/{speciality}', 'pages.admin.specialities.destroy')
        ->middleware('permission:delete-speciality')
        ->name('specialities.destroy');

    // Rutas para la gestión de doctores
    Volt::route('/doctors', 'pages.admin.doctors.index')
        ->middleware('permission:view-doctor')
        ->name('doctors.index');

    Volt::route('/doctors/create', 'pages.admin.doctors.create')
        ->middleware('permission:create-doctor')
            ->name('doctors.create');

    Volt::route('/doctors/{doctor}/edit', 'pages.admin.doctors.edit')
        ->middleware('permission:edit-doctor')
        ->name('doctors.edit');

    Volt::route('/doctors/{doctor}/show', 'pages.admin.doctors.show')
        ->middleware('permission:view-doctor')
        ->name('doctors.show');

    Volt::route('/doctors/{doctor}', 'pages.admin.doctors.destroy')
        ->middleware('permission:delete-doctor')
        ->name('doctors.destroy');
});

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'pages.admin.auth.login')
        ->name('login');

    /* Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset'); */
});

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
