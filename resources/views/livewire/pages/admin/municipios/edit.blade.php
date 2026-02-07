<?php

use Livewire\Volt\Component;
use App\Models\Municipio;
use App\Models\Estado;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Editar Municipio');
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

    public function save() {
        $validated = $this->validate([
            'estado_id' => 'required',
            'nombre_municipio' => 'required',
        ]);

        $this->municipio->update([
            'estado_id' => $this->estado_id,
            'municipio' => $this->nombre_municipio,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Municipio actualizado',
            'text' => 'El municipio se ha actualizado correctamente',
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
                'name' => $this->municipio->municipio,
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
                    Actualice la información del municipio.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.municipios.partials.form', ['showForm' => true, 'editForm' => true])

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
