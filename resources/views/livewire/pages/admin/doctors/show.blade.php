<?php

use Livewire\Volt\Component;
use App\Models\Doctor;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new #[Layout('layouts.tenancy')]
class extends Component {

    public function rendering(View $view)
    {
        $view->title('Doctor');
    }

    public $user;
    public $speciality;
    public $medical_license_number;
    public $medical_college_number;
    public $title;
    public $biography;

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $this->user = $doctor->user;
        $this->speciality = $doctor->speciality;
        $this->medical_license_number = $doctor->medical_license_number;
        $this->medical_college_number = $doctor->medical_college_number;
        $this->title = $doctor->title;
        $this->biography = $doctor->biography;
    }


    public function cancel()
    {
        $this->redirect(route('admin.doctors.index'), navigate: true);
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
                'name' => 'Doctores',
                'route' => route('admin.doctors.index'),
            ],
            [
                'name' => $this->doctor->user->name,
            ],
        ]" />
    </x-slot>

    <x-container class="py-6 sm:py-2 lg:py-6 md:py-4">
        <div class="card">
            <div class="card-body">
                <h1 class="text-2xl font-bold">
                    Información del Doctor
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información del doctor.
                </p>

                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.doctors.partials.form', ['showForm' => true, 'editForm' => false])

                <div class="border-t border-gray-200 dark:border-gray-600"></div>
                <div class="flex justify-end space-x-2">
                    
                    <x-button slate label="Atras" icon="x-mark" interaction="secondary" wire:click="cancel" />
                </div>
            </div>
        </div>
    </x-container>
</div>
