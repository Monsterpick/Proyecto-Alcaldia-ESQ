<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Editar Rol');
    }

    public $name;
    public $guard_name;
    public $permissions = [];
    public $selectedPermissions = [];
    public $role;

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->permissions = Permission::all();
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'guard_name' => 'required',
            'selectedPermissions' => 'required|array|min:1'
        ]);

        $this->role->update([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $this->role->syncPermissions($this->selectedPermissions);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol actualizado',
            'text' => 'El rol se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.roles.index'), navigate: true);
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
                'name' => 'Roles',
                'route' => route('admin.roles.index'),
            ],
            [
                'name' => $this->role->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Rol
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información del rol.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.roles.partials.form', ['showForm' => true, 'editForm' => true])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Actualizar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
