<?php

use Livewire\Volt\Component;
use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Municipio');
    }

    public $estado_id;
    public $municipio;
    public $nombre_municipio;
    public $estados;

    public function mount()
    {
        $this->estados = Estado::all();
    }

    public function save() {
        $validated = $this->validate([
            'estado_id' => 'required',
            'nombre_municipio' => 'required',
        ]);

        $municipio = Municipio::create([
            'estado_id' => $this->estado_id,
            'municipio' => $this->nombre_municipio,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Municipio creado',
            'text' => 'El municipio se ha creado correctamente',
        ]);

        $this->redirect(route('admin.municipios.index'), navigate: true);
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
                'name' => 'Crear Municipio',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Municipio
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del municipio.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.municipios.partials.form', ['showForm' => true, 'editForm' => false])

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
