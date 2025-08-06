<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PaymentReceiptController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;

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

    Volt::route('/doctors/{doctor}/schedules', 'pages.admin.doctors.schedules')
        ->middleware('permission:view-doctor')
        ->name('doctors.schedules');

    Volt::route('/appointments', 'pages.admin.appointments.index')
        ->middleware('permission:view-appointment')
        ->name('appointments.index');

    Volt::route('/appointments/create', 'pages.admin.appointments.create')
        ->middleware('permission:create-appointment')
        ->name('appointments.create');

    Volt::route('/appointments/{appointment}/edit', 'pages.admin.appointments.edit')
        ->middleware('permission:edit-appointment')
        ->name('appointments.edit');

    Volt::route('/appointments/{appointment}/show', 'pages.admin.appointments.show')
        ->middleware('permission:view-appointment')
        ->name('appointments.show');

    Volt::route('/appointments/{appointment}', 'pages.admin.appointments.destroy')
        ->middleware('permission:delete-appointment')
        ->name('appointments.destroy');

    // Rutas para la gestión de estatuses de cita
    Volt::route('/appointment-statuses', 'pages.admin.appointment-statuses.index')
        ->middleware('permission:view-appointment-status')
        ->name('appointment-statuses.index');

    Volt::route('/appointment-statuses/create', 'pages.admin.appointment-statuses.create')
        ->middleware('permission:create-appointment-status')
        ->name('appointment-statuses.create');

    Volt::route('/appointment-statuses/{appointmentStatus}/edit', 'pages.admin.appointment-statuses.edit')
        ->middleware('permission:edit-appointment-status')
        ->name('appointment-statuses.edit');

    Volt::route('/appointment-statuses/{appointmentStatus}/show', 'pages.admin.appointment-statuses.show')
        ->middleware('permission:view-appointment-status')
        ->name('appointment-statuses.show');

    Volt::route('/appointment-statuses/{appointmentStatus}', 'pages.admin.appointment-statuses.destroy')
        ->middleware('permission:delete-appointment-status')
        ->name('appointment-statuses.destroy');

    Volt::route('/appointments/{appointment}/consultation', 'pages.admin.appointments.consultation')
        ->middleware('permission:view-consultation')
        ->name('appointments.consultation');

    Volt::route('/calendar', 'pages.admin.calendar.index')
        ->middleware('permission:view-calendar')
        ->name('calendar.index');

    Volt::route('/calendar/create', 'pages.admin.calendar.create')
        ->middleware('permission:create-calendar')
        ->name('calendar.create');

    Volt::route('/calendar/{calendar}/edit', 'pages.admin.calendar.edit')
        ->middleware('permission:edit-calendar')
        ->name('calendar.edit');

    Volt::route('/calendar/{calendar}/show', 'pages.admin.calendar.show')
        ->middleware('permission:view-calendar')
        ->name('calendar.show');

    Volt::route('/calendar/{calendar}', 'pages.admin.calendar.destroy')
        ->middleware('permission:delete-calendar')
        ->name('calendar.destroy');

    Volt::route('/settings/general', 'pages.admin.settings.general')
        ->middleware('permission:view-setting')
        ->name('settings.general');

    Volt::route('/settings/logo', 'pages.admin.settings.logo')
        ->middleware('permission:view-setting')
        ->name('settings.logo');

    Volt::route('/settings/profile', 'pages.admin.profile.index')
        ->middleware('permission:profile-setting')
        ->name('profile.index');

    Volt::route('/categories', 'pages.admin.categories.index')
        ->middleware('permission:view-category')
        ->name('categories.index');

    Volt::route('/categories/create', 'pages.admin.categories.create')
        ->middleware('permission:create-category')
        ->name('categories.create');
    
    Volt::route('/categories/{category}/edit', 'pages.admin.categories.edit')
        ->middleware('permission:edit-category')
        ->name('categories.edit');

    Volt::route('/categories/{category}/show', 'pages.admin.categories.show')
        ->middleware('permission:view-category')
        ->name('categories.show');
    
    Volt::route('/categories/{category}', 'pages.admin.categories.destroy')
        ->middleware('permission:delete-category')
        ->name('categories.destroy');

    Volt::route('/products', 'pages.admin.products.index')
        ->middleware('permission:view-product')
        ->name('products.index');

    Volt::route('/products/create', 'pages.admin.products.create')
        ->middleware('permission:create-product')
        ->name('products.create');
    
    Volt::route('/products/{product}/edit', 'pages.admin.products.edit')
        ->middleware('permission:edit-product')
        ->name('products.edit');

    Volt::route('/products/{product}/show', 'pages.admin.products.show')
        ->middleware('permission:view-product')
        ->name('products.show');
    
    Volt::route('/products/{product}', 'pages.admin.products.destroy')
        ->middleware('permission:delete-product')
        ->name('products.destroy');

    Route::post('/products/{product}/dropzone', [ProductController::class, 'dropzone'])
        ->middleware('permission:view-product')
        ->name('products.dropzone.store');

    Route::delete('images/{image}', [ImageController::class, 'destroy'])
        ->middleware('permission:delete-product')
        ->name('images.destroy');

    //Rutas de Customer
    Volt::route('/customers', 'pages.admin.customers.index')
        ->middleware('permission:view-customer')
        ->name('customers.index');

    Volt::route('/customers/create', 'pages.admin.customers.create')
        ->middleware('permission:create-customer')
        ->name('customers.create');
    
    Volt::route('/customers/{customer}/edit', 'pages.admin.customers.edit')
        ->middleware('permission:edit-customer')
        ->name('customers.edit');

    Volt::route('/customers/{customer}/show', 'pages.admin.customers.show')
        ->middleware('permission:view-customer')
        ->name('customers.show');
    
    Volt::route('/customers/{customer}', 'pages.admin.customers.destroy')
        ->middleware('permission:delete-customer')
        ->name('customers.destroy');

    //Rutas de Supplier Proveedores
    Volt::route('/suppliers', 'pages.admin.suppliers.index')
        ->middleware('permission:view-supplier')
        ->name('suppliers.index');

    Volt::route('/suppliers/create', 'pages.admin.suppliers.create')
        ->middleware('permission:create-supplier')
        ->name('suppliers.create');
    
    Volt::route('/suppliers/{supplier}/edit', 'pages.admin.suppliers.edit')
        ->middleware('permission:edit-supplier')
        ->name('suppliers.edit');

    Volt::route('/suppliers/{supplier}/show', 'pages.admin.suppliers.show')
        ->middleware('permission:view-supplier')
        ->name('suppliers.show');
    
    Volt::route('/suppliers/{supplier}', 'pages.admin.suppliers.destroy')
        ->middleware('permission:delete-supplier')
        ->name('suppliers.destroy');

    //Rutas de Warehouse Almacenes
    Volt::route('/warehouses', 'pages.admin.warehouses.index')
        ->middleware('permission:view-warehouse')
        ->name('warehouses.index');

    Volt::route('/warehouses/create', 'pages.admin.warehouses.create')
        ->middleware('permission:create-warehouse')
        ->name('warehouses.create');
    
    Volt::route('/warehouses/{warehouse}/edit', 'pages.admin.warehouses.edit')
        ->middleware('permission:edit-warehouse')
        ->name('warehouses.edit');

    Volt::route('/warehouses/{warehouse}/show', 'pages.admin.warehouses.show')
        ->middleware('permission:view-warehouse')
        ->name('warehouses.show');
    
    Volt::route('/warehouses/{warehouse}', 'pages.admin.warehouses.destroy')
        ->middleware('permission:delete-warehouse')
        ->name('warehouses.destroy');

    //Rutas de Ordenes de Compra
    Volt::route('/purchase-orders', 'pages.admin.purchase-orders.index')
        ->middleware('permission:view-purchase-order')
        ->name('purchase-orders.index');

    Volt::route('/purchase-orders/create', 'pages.admin.purchase-orders.create')
        ->middleware('permission:create-purchase-order')
        ->name('purchase-orders.create');
    
    Volt::route('/purchase-orders/{purchaseOrder}/edit', 'pages.admin.purchase-orders.edit')
        ->middleware('permission:edit-purchase-order')
        ->name('purchase-orders.edit');
        
    Volt::route('/purchase-orders/{purchaseOrder}/show', 'pages.admin.purchase-orders.show')
        ->middleware('permission:view-purchase-order')
        ->name('purchase-orders.show');
    
    Volt::route('/purchase-orders/{purchaseOrder}', 'pages.admin.purchase-orders.destroy')
        ->middleware('permission:delete-purchase-order')
        ->name('purchase-orders.destroy');

    //Rutas de Purchase
    Volt::route('/purchases', 'pages.admin.purchases.index')
        ->middleware('permission:view-purchase')
        ->name('purchases.index');
    
    Volt::route('/purchases/create', 'pages.admin.purchases.create')
        ->middleware('permission:create-purchase')
        ->name('purchases.create');
    
    Volt::route('/purchases/{purchase}/edit', 'pages.admin.purchases.edit')
        ->middleware('permission:edit-purchase')
        ->name('purchases.edit');
    
    Volt::route('/purchases/{purchase}/show', 'pages.admin.purchases.show')
        ->middleware('permission:view-purchase')
        ->name('purchases.show');

    Volt::route('/purchases/{purchase}', 'pages.admin.purchases.destroy')
        ->middleware('permission:delete-purchase')
        ->name('purchases.destroy');

    //Rutas de Quote
    Volt::route('/quotes', 'pages.admin.quotes.index')
        ->middleware('permission:view-quote')
        ->name('quotes.index');

    Volt::route('/quotes/create', 'pages.admin.quotes.create')
        ->middleware('permission:create-quote')
        ->name('quotes.create');
    
    Volt::route('/quotes/{quote}/edit', 'pages.admin.quotes.edit')
        ->middleware('permission:edit-quote')
        ->name('quotes.edit');

    Volt::route('/quotes/{quote}/show', 'pages.admin.quotes.show')
        ->middleware('permission:view-quote')
        ->name('quotes.show');
    
    Volt::route('/quotes/{quote}', 'pages.admin.quotes.destroy')
        ->middleware('permission:delete-quote')
        ->name('quotes.destroy');

    //Rutas de Sale
    Volt::route('/sales', 'pages.admin.sales.index')
        ->middleware('permission:view-sale')
        ->name('sales.index');
    
    Volt::route('/sales/create', 'pages.admin.sales.create')
        ->middleware('permission:create-sale')
        ->name('sales.create');
    
    Volt::route('/sales/{sale}/edit', 'pages.admin.sales.edit')
        ->middleware('permission:edit-sale')
        ->name('sales.edit');
    
    Volt::route('/sales/{sale}/show', 'pages.admin.sales.show')
        ->middleware('permission:view-sale')
        ->name('sales.show');
    
    Volt::route('/sales/{sale}', 'pages.admin.sales.destroy')
        ->middleware('permission:delete-sale')
        ->name('sales.destroy');

    //Rutas de Movimientos
    Volt::route('/movements', 'pages.admin.movements.index')
        ->middleware('permission:view-movement')
        ->name('movements.index');
    
    Volt::route('/movements/create', 'pages.admin.movements.create')
        ->middleware('permission:create-movement')
        ->name('movements.create');
    
    Volt::route('/movements/{movement}/edit', 'pages.admin.movements.edit')
        ->middleware('permission:edit-movement')
        ->name('movements.edit');
    
    Volt::route('/movements/{movement}/show', 'pages.admin.movements.show')
        ->middleware('permission:view-movement')
        ->name('movements.show');
    
    Volt::route('/movements/{movement}', 'pages.admin.movements.destroy')
        ->middleware('permission:delete-movement')
        ->name('movements.destroy');

    //Rutas de Transferencias
    Volt::route('/transfers', 'pages.admin.transfers.index')
        ->middleware('permission:view-transfer')
        ->name('transfers.index');
        
    Volt::route('/transfers/create', 'pages.admin.transfers.create')
        ->middleware('permission:create-transfer')
        ->name('transfers.create');
    
    Volt::route('/transfers/{transfer}/edit', 'pages.admin.transfers.edit')
        ->middleware('permission:edit-transfer')
        ->name('transfers.edit');

    Volt::route('/transfers/{transfer}/show', 'pages.admin.transfers.show')
        ->middleware('permission:view-transfer')
        ->name('transfers.show');

    Volt::route('/transfers/{transfer}', 'pages.admin.transfers.destroy')
        ->middleware('permission:delete-transfer')
        ->name('transfers.destroy');
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
