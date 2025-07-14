<?php

use Livewire\Volt\Component;
use App\Models\Appointment;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Editar Cita');
    }

    public $appointment;
    public $appointment_date;
    public $appointment_start_time;
    public $appointment_end_time;
    public $appointment_duration;
    public $appointment_reason;
    public $appointmentStatus;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->appointment_date = $appointment->date;
        $this->appointment_start_time = $appointment->start_time;
        $this->appointment_end_time = $appointment->end_time;
        $this->appointment_duration = $appointment->duration;
        $this->appointment_reason = $appointment->reason;
        $this->appointmentStatus = $appointment->appointmentStatus->name;
    }

    public function save()
    {
        $validated = $this->validate([
            'appointment_date' => 'required',
            'appointment_start_time' => 'required',
            'appointment_end_time' => 'required',
            'appointment_duration' => 'required',
            'appointment_reason' => 'required',
            'appointmentStatus' => 'required',
        ]);

        $this->estado->update([
            'date' => $this->appointment_date,
            'start_time' => $this->appointment_start_time,
            'end_time' => $this->appointment_end_time,
            'duration' => $this->appointment_duration,
            'reason' => $this->appointment_reason,
            'status' => $this->appointment_status,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita actualizada',
            'text' => 'La cita se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.appointments.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.appointments.index'), navigate: true);
    }
}; ?>

<div>

    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Citas',
                'route' => route('admin.appointments.index'),
            ],
            [
                'name' => $this->appointment->id,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card class="mb-4">

            <h1 class="text-2xl font-bold">
                Información de la Cita
            </h1>
            <div class="flex items-center justify-between">
                <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Editando la cita para:
                    <span class="font-bold">
                        {{ $appointment->patient->user->name }} {{ $appointment->patient->user->last_name }}
                    </span>
                </p>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Fecha de la cita:

                    <span class="font-semibold ">
                        {{ $appointment->date->format('d/m/Y') }} a las
                        {{ $appointment->start_time->format('H:i') }}
                    </span>
                </p>
                </div>
                <div class="">
                    <x-badge lg :color="$appointment->appointmentStatus->color">
                        {{ $appointment->appointmentStatus->name }}
                    </x-badge>
                </div>
            </div>


            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button info wire:click="save" spinner="save" label="Actualizar" icon="check"
                        interaction="positive" />
                    <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>

        <livewire:pages.admin.appointments.appointmentmanager :appointmentEdit="$appointment" />
        
    </x-container>
</div>
