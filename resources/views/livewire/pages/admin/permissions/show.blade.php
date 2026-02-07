<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Permiso');
    }

    public $name;
    public $guard_name;
    public $permission;

    public function mount(Permission $permission)
    {
        $this->permission = $permission;
        $this->name = $permission->name;
        $this->guard_name = $permission->guard_name;
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
                'name' => $this->permission->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
                <h1 class="text-2xl font-bold">
                    Información del Permiso
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información del permiso.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                    @include('livewire.pages.admin.permissions.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
        </x-card>
    </x-container>
</div>
