<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Crear Permiso');
    }

    public $name;
    public $guard_name;

    public function mount()
    {
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'guard_name' => 'required',
        ]);

        $permission = Permission::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Permiso creado',
            'text' => 'El permiso se ha creado correctamente',
        ]);

        $this->redirect(route('admin.permissions.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.permissions.index'), navigate: true);
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
                'name' => 'Permisos',
                'route' => route('admin.permissions.index'),
            ],
            [
                'name' => 'Crear Permiso',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Permiso
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del permiso.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.permissions.partials.form', ['showForm' => true, 'editForm' => false])

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
