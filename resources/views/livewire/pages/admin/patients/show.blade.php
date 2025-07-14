<?php

use Livewire\Volt\Component;
use App\Models\Patient;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Paciente');
    }

    public $bloodType;
    public $user;
    public $allergies;
    public $chronic_conditions;
    public $surgical_history;
    public $family_history;
    public $observations;
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $emergency_contact_relationship;
    public function mount(Patient $patient)
    {
        $this->patient = $patient;
        $this->bloodType = $patient->bloodType;
        $this->user = $patient->user;
        $this->allergies = $patient->allergies;
        $this->chronic_conditions = $patient->chronic_conditions;
        $this->surgical_history = $patient->surgical_history;
        $this->family_history = $patient->family_history;
        $this->observations = $patient->observations;
        $this->emergency_contact_name = $patient->emergency_contact_name;
        $this->emergency_contact_phone = $patient->emergency_contact_phone;
        $this->emergency_contact_relationship = $patient->emergency_contact_relationship;
    }

    public function cancel()
    {
        $this->redirect(route('admin.patients.index'), navigate: true);
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
                'name' => 'Pacientes',
                'route' => route('admin.patients.index'),
            ],
            [
                'name' => $this->patient->user->name,
            ],
        ]" />
    </x-slot>

    <x-container class="py-6 sm:py-2 lg:py-6 md:py-4">
        <div class="card">
            <div class="card-body">
                <h1 class="text-2xl font-bold">
                    Información del Paciente
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información del paciente.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.patients.partials.form', [
                    'showForm' => true,
                    'editForm' => false,
                ])

                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atras" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </div>
        </div>
    </x-container>
</div>
