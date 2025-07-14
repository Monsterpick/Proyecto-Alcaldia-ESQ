<?php
use Livewire\Volt\Component;
use App\Models\Speciality;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Especialidad');
    }

    public $name;

    public function mount()
    {
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
        ]);

        $speciality = Speciality::create([
            'name' => $this->name,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Especialidad creada',
            'text' => 'La especialidad se ha creado correctamente',
        ]);

        $this->redirect(route('admin.specialities.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.specialities.index'), navigate: true);
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
                'name' => 'Especialidades',
                'route' => route('admin.specialities.index'),
            ],
            [
                'name' => 'Crear Especialidad',
            ],
        ]" />
    </x-slot>
    

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit="save">
                <h1 class="text-2xl font-bold">
                    Información de la Especialidad
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información de la especialidad.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                    @include('livewire.pages.admin.specialities.partials.form', ['showForm' => true, 'editForm' => false])

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
