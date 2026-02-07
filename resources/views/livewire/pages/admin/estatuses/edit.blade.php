<?php

use Livewire\Volt\Component;
use App\Models\Estatus;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Editar Estatus');
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

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $this->estatus->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Estatus actualizado',
            'text' => 'El estatus se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.estatuses.index'), navigate: true);
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
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Estatus
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información del estatus.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.estatuses.partials.form', ['showForm' => true, 'editForm' => true])

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
