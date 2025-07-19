<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Appointment;

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
            fn($query) => $query->whereHas('patient', function($query) use ($request) {
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