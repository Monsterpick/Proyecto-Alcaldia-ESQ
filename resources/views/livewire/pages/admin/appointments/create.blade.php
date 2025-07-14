<?php

use Livewire\Volt\Component;
use App\Models\Appointment;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Cita');
    }

    public $appointment_date;
    public $appointment_start_time;
    public $appointment_end_time;
    public $appointment_duration;
    public $appointment_reason;
    public $appointment_status;

    public function mount()
    {
        $this->appointment_date = '';
        $this->appointment_start_time = '';
        $this->appointment_end_time = '';
        $this->appointment_duration = '';
        $this->appointment_reason = '';
        $this->appointment_status = '';
    }

    public function save() {
        $validated = $this->validate([
            'appointment_date' => 'required',
            'appointment_start_time' => 'required',
            'appointment_end_time' => 'required',
            'appointment_duration' => 'required',
            'appointment_reason' => 'required',
            'appointment_status' => 'required',
        ]);

        $appointment = Appointment::create([
            'date' => $this->appointment_date,
            'start_time' => $this->appointment_start_time,
            'end_time' => $this->appointment_end_time,
            'duration' => $this->appointment_duration,
            'reason' => $this->appointment_reason,
            'status' => $this->appointment_status,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita creada',
            'text' => 'La cita se ha creado correctamente',
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
                'name' => 'Crear Cita',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">


        <livewire:pages.admin.appointments.appointmentmanager />



        {{-- <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información de la Cita
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información de la cita.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.appointments.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Guardar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card> --}}
    </x-container>
</div>
