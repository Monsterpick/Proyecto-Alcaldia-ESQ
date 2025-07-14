<?php

use Livewire\Volt\Component;
use App\Models\Patient;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use App\Models\BloodType;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Editar Paciente');
    }

    public $blood_type_id;
    public $bloodTypes;
    public $user;
    public $patient;
    public $allergies;
    public $chronic_conditions;
    public $surgical_history;
    public $family_history;
    public $observations;
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $emergency_contact_relationship;
    public $image;

    public function mount(Patient $patient)
    {
        $this->bloodTypes = BloodType::all();

        $this->patient = $patient;
        $this->blood_type_id = $patient->blood_type_id;
        $this->user = $patient->user;
        $this->allergies = $patient->allergies;
        $this->chronic_conditions = $patient->chronic_conditions;
        $this->surgical_history = $patient->surgical_history;
        $this->family_history = $patient->family_history;
        $this->observations = $patient->observations;
        $this->emergency_contact_name = $patient->emergency_contact_name;
        $this->emergency_contact_phone = $patient->emergency_contact_phone;
        $this->emergency_contact_relationship = $patient->emergency_contact_relationship;
        $this->image = $patient->user->image_url;
    }

    public function update()
    {
        $validated = $this->validate([
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'user' => 'required',
            'allergies' => 'nullable|string|max:255',
            'chronic_conditions' => 'nullable|string|max:255',
            'surgical_history' => 'nullable|string|max:255',
            'family_history' => 'nullable|string|max:255',
            'observations' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
        ]);

        $this->patient->update([
            'blood_type_id' => $this->blood_type_id,
            'user' => $this->user,
            'allergies' => $this->allergies,
            'chronic_conditions' => $this->chronic_conditions,
            'surgical_history' => $this->surgical_history,
            'family_history' => $this->family_history,
            'observations' => $this->observations,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Paciente actualizado',
            'text' => 'El paciente se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.patients.edit', ['patient' => $this->patient->id]), navigate: true);
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
        <form wire:submit="update">
            <x-card class="mb-8">
                <div class="lg:flex lg:justify-between lg:items-center">
                    <div class="flex items-center space-x-5">
                        <img src="{{ $this->user->image_url ? Storage::url($this->user->image_url) : asset('images/no_user_image.png') }}"
                            alt="{{ $this->user->name }}" class="h-20 w-20 object-cover object-center rounded-full">

                        <div class="">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $this->user->name }} {{ $this->user->last_name }}
                            </p>
                            <p class="mb-1 text-sm font-semibold text-gray-500 dark:text-gray-400">
                                Documento: {{ $this->user->document  ?? 'N/A' }}
                            </p>

                        </div>
                    </div>
                    <div class="flex items-center space-x-3 mt-6 lg:mt-0">
                        <x-button wire:click="update" info spinner="save" label="Guardar cambios" icon="check"
                            interaction="positive" />
                        <x-button slate outline label="Volver" icon="x-mark" interaction="secondary"
                            wire:click="cancel" />
                    </div>
                </div>
            </x-card>
            {{-- Tabs --}}
            <x-card class="mb-8">
                <x-tabs active="datos-personales">

                    <x-slot name="header">

                            <x-tab-link tab="datos-personales">
                                <i class="fa-solid fa-user mr-2"></i>Datos Personales
                            </x-tab-link>

                            <x-tab-link tab="antecedentes">
                                <i class="fa-solid fa-file-lines mr-2"></i>Antecedentes
                            </x-tab-link>

                            <x-tab-link tab="informacion-general">
                                <i class="fa-solid fa-info mr-2"></i>Información General
                            </x-tab-link>

                            <x-tab-link tab="contacto-emergencia">
                                <i class="fa-solid fa-heart mr-2"></i>Contacto de Emergencia
                            </x-tab-link>
                    
                    </x-slot>
                    {{-- Datos Personales --}}
                    <x-tab-content tab="datos-personales">
                        <x-alert title="Edición de usuario" info class="mb-4">
                            <div class="text-sm">
                                Para editar esta información, dirigete al <a
                                    href="{{ route('admin.users.edit', ['user' => $this->user->id]) }}"
                                    class="text-blue-600" target="_blank">Perfil de usuario</a> asociado a este
                                paciente.
                            </div>
                        </x-alert>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-semibold text-sm">
                                    Teléfono
                                </span>
                                <span class="text-gray-900 dark:text-gray-100 font-semibold text-sm">
                                    {{ $this->user->phone }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-semibold text-sm">
                                    Correo electrónico
                                </span>
                                <span class="text-gray-900 dark:text-gray-100 font-semibold text-sm">
                                    {{ $this->user->email }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-40 font-semibold text-sm">
                                    Dirección
                                </span>
                                <span class="text-gray-900 dark:text-gray-100 font-semibold text-sm">
                                    {{ $this->user->address }}
                                </span>
                            </div>
                        </div>
                    </x-tab-content>
                    {{-- Antecedentes --}}
                    <x-tab-content tab="antecedentes">
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div>
                                <x-textarea label="Alergias conocidas" wire:model="allergies">
                                    {{ old('allergies', $patient->allergies) }}
                                </x-textarea>
                            </div>
                            <div>
                                <x-textarea label="Enfermedades cronicas" wire:model="chronic_conditions">
                                    {{ old('chronic_conditions', $patient->chronic_conditions) }}
                                </x-textarea>
                            </div>
                            <div>
                                <x-textarea label="Antecedentes quirúrgicos" wire:model="surgical_history">
                                    {{ old('surgical_history', $patient->surgical_history) }}
                                </x-textarea>
                            </div>
                            <div>
                                <x-textarea label="Antecedentes familiares" wire:model="family_history">
                                    {{ old('family_history', $patient->family_history) }}
                                </x-textarea>
                            </div>
                        </div>

                    </x-tab-content>
                    {{-- Información General --}}
                    <x-tab-content tab="informacion-general">
                        <x-select label="Tipo de sangre" id="blood_type_id" class="mt-1 block w-full mb-4"
                            wire:model="blood_type_id" name="blood_type_id" required option-value="id"
                            option-label="name" :options="$bloodTypes" :disabled="$readonly ?? false"
                            placeholder="Seleccione un tipo de sangre"
                            selected-value="{{ $this->patient->bloodType->id ?? null }}" />
                        <x-textarea label="Observaciones" wire:model="observations" class="mt-4">
                            {{ old('observations', $patient->observations) }}
                        </x-textarea>
                    </x-tab-content>
                    {{-- Contacto de Emergencia --}}
                    <x-tab-content tab="contacto-emergencia">
                        <div class="space-y-4">
                            <x-input label="Nombre del contacto de emergencia"
                                wire:model="emergency_contact_name" class="mt-4">
                                {{ old('emergency_contact_name', $patient->emergency_contact_name) }}
                            </x-input>
                            <x-input label="Teléfono del contacto de emergencia"
                                wire:model="emergency_contact_phone" class="mt-4">
                                {{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}
                            </x-input>
                            <x-input label="Relación con el contacto" wire:model="emergency_contact_relationship"
                                class="mt-4">
                                {{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}
                            </x-input>

                        </div>
                    </x-tab-content>

                </x-tabs>
            </x-card>

        </form>
    </x-container>
</div>
