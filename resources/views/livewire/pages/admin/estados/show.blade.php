<?php

use Livewire\Volt\Component;
use App\Models\Estado;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Estado');
    }

    public $estado;
    public $estado_nombre;
    public $iso_3166_2;

    public function mount(Estado $estado)
    {
        $this->estado = $estado;
        $this->estado_nombre = $estado->estado;
        $this->iso_3166_2 = $estado->{'iso_3166-2'};
    }

    public function cancel()
    {
        $this->redirect(route('admin.estados.index'), navigate: true);
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
                'name' => 'Estados',
                'route' => route('admin.estados.index'),
            ],
            [
                'name' => $this->estado->estado,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <h1 class="text-2xl font-bold">
                Información del Estado
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Muestra la información del estado.
            </p>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input label="Nombre" id="estado_nombre" class="mt-1 block w-full" type="text"
                        wire:model="estado_nombre" disabled />
                </div>
                <div>
                    <x-input label="ISO 3166-2" id="iso_3166_2" class="mt-1 block w-full" type="text"
                        wire:model="iso_3166_2" disabled />
                </div>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
