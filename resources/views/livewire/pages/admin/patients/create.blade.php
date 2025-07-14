<?php
use Livewire\Volt\Component;
use App\Models\Patient;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public function rendering(View $view)
    {
        $view->title('Crear Paciente');
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

    public function mount() {}

    public function save()
    {
        $validated = $this->validate([
            'bloodType' => 'required',
            'user' => 'required',
            'allergies' => 'required',
            'chronic_conditions' => 'required',
            'surgical_history' => 'required',
            'family_history' => 'required',
            'observations' => 'required',
            'emergency_contact_name' => 'required',
            'emergency_contact_phone' => 'required',
            'emergency_contact_relationship' => 'required',
        ]);

        $patient = Patient::create([
            'bloodType' => $this->bloodType,
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
            'title' => 'Paciente creado',
            'text' => 'El paciente se ha creado correctamente',
        ]);

        $this->redirect(route('admin.patients.index'), navigate: true);
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
                'name' => 'Crear Paciente',
            ],
        ]" />
    </x-slot>


    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit="save">
                <h1 class="text-2xl font-bold">
                    Información del Paciente
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del paciente.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.patients.partials.form', [
                    'showForm' => true,
                    'editForm' => false,
                ])

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
