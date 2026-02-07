<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\Reason;

Route::get('/patients', function (Request $request) {

    return User::query()
        ->select('id', 'name', 'email', 'last_name')
        ->when(
            $request->search,
            fn($query) => $query
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
        )
        ->when(
            $request->exists('selected'),
            /* fn($query) => $query->whereIn('id', $request->input('selected', [])), */
            /* Se sustituye la funcion anterior por la siguiente, para que se busque en la tabla de pacientes */
            fn($query) => $query->whereHas('patient', function ($query) use ($request) {
                $query->whereIn('id', $request->input('selected', []));
            }),
            fn($query) => $query->limit(10)
        )
        ->whereHas('patient')
        ->with('patient')
        ->orderBy('name')
        ->get()
        ->map(function (User $user) {
            return [
                'id' => $user->patient->id,
                'name' => $user->name . ' ' . $user->last_name,
            ];
        });
})->name('api.patients.index');


Route::get('/appointments', function (Request $request) {
    $appointments = Appointment::with(['patient.user', 'doctor.user'])
        ->whereBetween('date', [$request->start, $request->end])
        ->get();

    return $appointments->map(function ($appointment) {


        return [
            'id' => $appointment->id,
            'title' => $appointment->patient->user->name . ' ' . $appointment->patient->user->last_name,
            'start' => $appointment->start,
            'end' => $appointment->end,
            'color' => $appointment->appointmentStatus->color_hex,
            'extendedProps' => [
                'dateTime' => $appointment->start,
                'patient' => $appointment->patient->user->name . ' ' . $appointment->patient->user->last_name,
                'doctor' => $appointment->doctor->user->name . ' ' . $appointment->doctor->user->last_name,
                'status' => $appointment->appointmentStatus->name,
                'color' => $appointment->appointmentStatus->color_hex,
                'url' => route('admin.appointments.edit', $appointment->id),
            ],
        ];
    });
})->name('api.appointments.index');

Route::post('/suppliers', function (Request $request) {
    return Supplier::select('id', 'name')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('document_number', 'like', "%{$search}%");
        })
        ->when($request->selected, function ($query, $selected) {
            $query->whereIn('id', $selected);
        })
        ->get();
})->name('api.suppliers.index');

Route::post('/customers', function (Request $request) {
    return Customer::select('id', 'name')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('document_number', 'like', "%{$search}%");
        })
        ->when($request->selected, function ($query, $selected) {
            $query->whereIn('id', $selected);
        })
        ->get();
})->name('api.customers.index');

Route::post('/wharehouses', function (Request $request) {
    return Warehouse::select('id', 'name', 'location as description')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        })
        ->when($request->exclude, function ($query, $exclude) {
            $query->where('id', '!=', $exclude);
        })
        ->when($request->selected, function ($query, $selected) {
            $query->whereIn('id', $selected);
        })
        ->get();
})->name('api.warehouses.index');

Route::post('/products', function (Request $request) {
    return Product::select('id', 'name')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        })
        ->when($request->selected, function ($query, $selected) {
            $query->whereIn('id', $selected);
        })
        ->get();
})->name('api.products.index');

Route::post('/purchase-orders', function (Request $request) {
    $purchaseOrders = PurchaseOrder::when($request->search, function ($query, $search) {

        $parts = explode('-', $search);

        if (count($parts) === 1) {
            $query->whereHas('supplier', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            });

            return;
        }

        if (count($parts) == 2) {

            $serie = $parts[0];
            $correlative = ltrim($parts[1], '0'); //Elimina los ceros a la izquierda

            $query->where('serie', $serie)
                ->where('correlative', 'like', "%{$correlative}%");

            return;
        }

        /* $query->where('serie', 'like', "%{$search}%")
                ->orWhere('correlative', 'like', "%{$search}%"); */
    })
        ->when($request->selected, function ($query, $selected) {
            $query->whereIn('id', $selected);
        })
        ->with('supplier')
        ->orderBy('created_at', 'desc')
        ->get();

    return $purchaseOrders->map(function ($purchaseOrder) {
        return [
            'id' => $purchaseOrder->id,
            'name' => $purchaseOrder->serie . '-' . $purchaseOrder->correlative,
            'description' => $purchaseOrder->supplier->name . ' - ' . $purchaseOrder->supplier->document_number,
        ];
    });
})->name('api.purchase-orders.index');


Route::post('/quotes', function (Request $request) {
    $quotes = Quote::when($request->search, function ($query, $search) {

        $parts = explode('-', $search);

        if (count($parts) === 1) {
            $query->whereHas('customer', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            });

            return;
        }

        if (count($parts) == 2) {

            $serie = $parts[0];
            $correlative = ltrim($parts[1], '0'); //Elimina los ceros a la izquierda

            $query->where('serie', $serie)
                ->where('correlative', 'like', "%{$correlative}%");

            return;
        }
    })
        ->when($request->selected, function ($query, $selected) {
            $query->whereIn('id', $selected);
        })
        ->with('customer')
        ->orderBy('created_at', 'desc')
        ->get();

    return $quotes->map(function ($quote) {
        return [
            'id' => $quote->id,
            'name' => $quote->serie . '-' . $quote->correlative,
            'description' => $quote->customer->name . ' - ' . $quote->customer->document_number,
        ];
    });
})->name('api.quotes.index');

Route::post('/reasons', function (Request $request) {
    return Reason::select('id', 'name')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        })
        ->when($request->selected, function ($query, $selected) {
            $query->whereIn('id', $selected);
        })
        ->where('type', $request->input('type', ''))
        ->get();
})->name('api.reasons.index');

// Rutas del Bot de Telegram
Route::prefix('telegram')->group(function () {
    Route::post('/webhook', [App\Http\Controllers\TelegramBotController::class, 'webhook']);
    Route::post('/set-webhook', [App\Http\Controllers\TelegramBotController::class, 'setWebhook']);
    Route::post('/remove-webhook', [App\Http\Controllers\TelegramBotController::class, 'removeWebhook']);
    Route::get('/me', [App\Http\Controllers\TelegramBotController::class, 'getMe']);
    Route::post('/test', [App\Http\Controllers\TelegramBotController::class, 'sendTestMessage']);
});