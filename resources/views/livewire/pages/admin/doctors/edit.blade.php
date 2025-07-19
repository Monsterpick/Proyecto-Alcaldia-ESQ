<?php

use Livewire\Volt\Component;
use App\Models\Doctor;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use App\Models\BloodType;
use App\Models\Speciality;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Editar Doctor');
    }

    public $user;
    public $doctor;
    public $speciality_id;
    public $specialities;
    public $medical_license_number;
    public $medical_college_number;
    public $title;
    public $biography;
    public $image;
    public $is_active;

    public function mount(Doctor $doctor)
    {
        $this->specialities = Speciality::all();

        $this->doctor = $doctor;
        $this->user = $doctor->user;
        $this->speciality_id = $doctor->speciality_id;
        $this->medical_license_number = $doctor->medical_license_number;
        $this->medical_college_number = $doctor->medical_college_number;
        $this->title = $doctor->title;
        $this->biography = $doctor->biography;
        $this->image = $doctor->user->image_url;
        $this->is_active = $doctor->is_active ? '1' : '0';
    }

    public function update()
    {
        $validated = $this->validate([
            'user' => 'required',
            'speciality_id' => 'nullable|exists:specialities,id',
            'medical_license_number' => 'nullable|string|max:255',
            'medical_college_number' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'biography' => 'nullable|string|max:255',
            'is_active' => 'required|in:1,0',
        ]);

        $this->doctor->update([
            'user' => $this->user,
            'speciality_id' => $this->speciality_id,
            'medical_license_number' => $this->medical_license_number,
            'medical_college_number' => $this->medical_college_number,
            'title' => $this->title,
            'biography' => $this->biography,
            'is_active' => (bool) $this->is_active,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Doctor actualizado',
            'text' => 'El doctor se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.doctors.edit', ['doctor' => $this->doctor->id]), navigate: true);
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

    <x-container class="lg:py-0 lg:px-6">
        <form wire:submit="update">
                <x-card>
                <div class="lg:flex lg:justify-between lg:items-center">
                    <div class="flex items-center space-x-5">
                        <img src="{{ $this->user->image_url ? Storage::url($this->user->image_url) : asset('images/user_no_image.png') }}"
                            alt="{{ $this->user->name }}" class="h-20 w-20 object-cover object-center rounded-full">

                        <div class="">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $this->doctor->title ?? '' }} {{ $this->user->name }} {{ $this->user->last_name }}
                            </p>
                            <p class="mb-1 text-sm font-semibold text-gray-500 dark:text-gray-400">
                                Licencia: {{ $this->doctor->medical_license_number ?? 'N/A' }}
                            </p>
                            <p class="mb-1 text-sm font-semibold text-gray-500 dark:text-gray-400">
                                Colegiado: {{ $this->doctor->medical_college_number ?? 'N/A' }}
                            </p>
                            <p class="mb-1 text-sm font-semibold text-gray-500 dark:text-gray-400">
                                Especialidad: {{ $this->doctor->speciality->name ?? 'N/A' }}
                            </p>

                        </div>
                    </div>
                    <div class="flex items-center space-x-3 mt-6 lg:mt-0">
                        <x-button wire:click="update" info spinner="save" label="Guardar cambios" icon="check"
                            interaction="positive" />
                        <x-button slate outline label="Volver" icon="x-mark" interaction="secondary"
                            wire:click="cancel" />
                        <x-button positive label="Gestionar horarios" icon="clock" interaction="secondary"
                            :href="route('admin.doctors.schedules', ['doctor' => $this->doctor->id])" />
                    </div>
        </x-card>

            <x-card class="mb-8">
                <div class="space-y-4">

                    <x-select label="Especialidad" id="speciality_id" class="mt-1 block w-full mb-4"
                        wire:model="speciality_id" name="speciality_id" required option-value="id" option-label="name"
                        :options="$specialities" :disabled="$readonly ?? false" placeholder="Seleccione una especialidad"
                        selected-value="{{ $this->doctor->speciality_id ?? null }}" />


                </div>
                <x-input label="Número de licencia" wire:model="medical_license_number" class="mt-4" />
                <x-input label="Número de colegiado" wire:model="medical_college_number" class="mt-4" />
                <x-input label="Título" wire:model="title" class="mt-4" />
                <x-textarea label="Biografía" wire:model="biography" class="mt-4" />

                <x-native-select label="Estado" wire:model="is_active" class="mt-4" :options="[
                    ['name' => 'Activo', 'value' => '1'],
                    ['name' => 'Inactivo', 'value' => '0'],
                ]" option-label="name" option-value="value" />

            </x-card>


        </form>
    </x-container>

</div>
