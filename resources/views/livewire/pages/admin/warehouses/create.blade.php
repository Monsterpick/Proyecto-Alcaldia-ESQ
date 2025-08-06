<?php

use Livewire\Volt\Component;
use App\Models\Warehouse;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Almacén');
    }

    public $name;
    public $location;

    public function mount()
    {
        $this->name = '';
        $this->location = '';
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3',
            'location' => 'required|max:255|min:3',
        ]);

        $warehouse = Warehouse::create([
            'name' => $this->name,
            'location' => $this->location,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Almacén creado',
            'text' => 'El almacén se ha creado correctamente',
        ]);

        $this->redirect(route('admin.warehouses.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.warehouses.index'), navigate: true);
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
                'name' => 'Almacenes',
                'route' => route('admin.warehouses.index'),
            ],
            [
                'name' => 'Crear Almacén',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Almacén
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del almacén.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.warehouses.partials.form', ['showForm' => true, 'editForm' => false])

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
