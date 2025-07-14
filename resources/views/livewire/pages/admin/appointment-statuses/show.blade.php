<?php

use Livewire\Volt\Component;
use App\Models\AppointmentStatus;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Estatus de Cita');
    }

    public $appointmentStatus;
    public $appointment_status_name;
    public $appointment_status_description;

    public function mount(AppointmentStatus $appointmentStatus)
    {
        $this->appointmentStatus = $appointmentStatus;
        $this->appointment_status_name = $appointmentStatus->name;
        $this->appointment_status_description = $appointmentStatus->description;
    }

    public function cancel()
    {
        $this->redirect(route('admin.appointment-statuses.index'), navigate: true);
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
                'name' => 'Estatus de Cita',
                'route' => route('admin.appointment-statuses.index'),
            ],
            [
                'name' => $this->appointmentStatus->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <h1 class="text-2xl font-bold">
                Información del Estatus de Cita
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Muestra la información del estatus de cita.
            </p>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input label="Nombre" id="appointment_status_name" class="mt-1 block w-full" type="text"
                        wire:model="appointment_status_name" disabled />
                </div>
                <div>
                    <x-input label="Descripción" id="appointment_status_description" class="mt-1 block w-full" type="text"
                        wire:model="appointment_status_description" disabled />
                </div>
            </div>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
