<?php

use Livewire\Volt\Component;
use App\Models\Estado;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Editar Estado');
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

    public function save() {
        $validated = $this->validate([
            'estado_nombre' => 'required',
            'iso_3166_2' => 'required',
        ]);

        $this->estado->update([
            'estado' => $this->estado_nombre,
            'iso_3166-2' => $this->iso_3166_2,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Estado actualizado',
            'text' => 'El estado se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.estados.index'), navigate: true);
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
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Estado
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información del estado.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.estados.partials.form', ['showForm' => true, 'editForm' => true])

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
