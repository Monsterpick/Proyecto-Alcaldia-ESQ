<?php

use Livewire\Volt\Component;
use App\Models\Estatus;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Estatus');
    }

    public $name;
    public $description;
    public $estatus;

    public function mount(Estatus $estatus)
    {
        $this->estatus = $estatus;
        $this->name = $estatus->name;
        $this->description = $estatus->description;
    }


    public function cancel()
    {
        $this->redirect(route('admin.estatuses.index'), navigate: true);
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
                'name' => 'Estatuses',
                'route' => route('admin.estatuses.index'),
            ],
            [
                'name' => $this->estatus->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
                <h1 class="text-2xl font-bold">
                    Información del Estatus
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información del estatus.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.estatuses.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
        </x-card>
    </x-container>
</div>
