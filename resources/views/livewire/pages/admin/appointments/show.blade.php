<?php

use Livewire\Volt\Component;
use App\Models\Appointment;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Cita');
    }

    public $appointment;
    public $appointment_date;
    public $appointment_start_time;
    public $appointment_end_time;
    public $appointment_duration;
    public $appointment_reason;
    public $appointment_status;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->appointment_date = $appointment->date;
        $this->appointment_start_time = $appointment->start_time;
        $this->appointment_end_time = $appointment->end_time;
        $this->appointment_duration = $appointment->duration;
        $this->appointment_reason = $appointment->reason;
        $this->appointment_status = $appointment->status;
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
        <x-card>
            <h1 class="text-2xl font-bold">
                Información de la Cita
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Muestra la información de la cita
            </p>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                
            </div>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
