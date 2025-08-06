<?php

use Livewire\Volt\Component;
use App\Models\Warehouse;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Almacén');
    }

    public $warehouse;
    public $name;
    public $location;

    public function mount(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
        $this->name = $warehouse->name;
        $this->location = $warehouse->location;
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
                'name' => $this->warehouse->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <h1 class="text-2xl font-bold">
                Información del Almacén
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Muestra la información del almacén.
            </p>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>

            
            @include('livewire.pages.admin.warehouses.partials.form', ['showForm' => true, 'editForm' => false])

            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
