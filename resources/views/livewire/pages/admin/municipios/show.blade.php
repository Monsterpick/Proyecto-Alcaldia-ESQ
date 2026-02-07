<?php

use Livewire\Volt\Component;
use App\Models\Municipio;
use App\Models\Estado;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Municipio');
    }

    public $municipio;
    public $estados;
    public $estado_id;
    public $nombre_municipio;

    public function mount(Municipio $municipio)
    {
        $this->municipio = $municipio;
        $this->estados = Estado::all();
        $this->estado_id = $municipio->estado_id;
        $this->nombre_municipio = $municipio->municipio;
    }

    public function cancel()
    {
        $this->redirect(route('admin.municipios.index'), navigate: true);
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
                'name' => 'Municipios',
                'route' => route('admin.municipios.index'),
            ],
            [
                'name' => $this->municipio->municipio,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
                <h1 class="text-2xl font-bold">
                    Información del Municipio
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información del municipio.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="font-medium text-gray-700 dark:text-gray-200">Estado</label>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">
                            {{ $municipio->estado->estado }}
                        </p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-700 dark:text-gray-200">Municipio</label>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">
                            {{ $municipio->municipio }}
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-600 mt-4"></div>
                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atrás" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </x-card>
    </x-container>
</div>
