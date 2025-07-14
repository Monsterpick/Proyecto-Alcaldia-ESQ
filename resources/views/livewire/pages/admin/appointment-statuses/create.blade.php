<?php

use Livewire\Volt\Component;
use App\Models\AppointmentStatus;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Estatus de Cita');
    }

    public $appointment_status_name;
    public $appointment_status_description;

    public function mount()
    {
        $this->appointment_status_name = '';
        $this->appointment_status_description = '';
    }

    public function save() {
        $validated = $this->validate([
            'appointment_status_name' => 'required',
            'appointment_status_description' => 'required',
        ]);

        $appointment_status = AppointmentStatus::create([
            'name' => $this->appointment_status_name,
            'description' => $this->appointment_status_description,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Estatus de Cita creado',
            'text' => 'El estatus de cita se ha creado correctamente',
        ]);

        $this->redirect(route('admin.appointment-statuses.index'), navigate: true);
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
                'name' => 'Crear Estatus de Cita',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Estatus de Cita
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del estatus de cita.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.appointment-statuses.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Guardar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
