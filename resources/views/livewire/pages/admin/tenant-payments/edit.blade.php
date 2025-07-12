<?php

use Livewire\Volt\Component;
use App\Models\Actividad;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Editar Pago de Tenant');
    }

    public $name;
    public $description;
    public $actividad;

    public function mount(Actividad $actividad)
    {
        $this->actividad = $actividad;
        $this->name = $actividad->name;
        $this->description = $actividad->description;
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $this->actividad->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actividad actualizada',
            'text' => 'La actividad se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.tenant-payments.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.tenant-payments.index'), navigate: true);
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
                'name' => 'Actividades',
                'route' => route('admin.tenant-payments.index'),
            ],
            [
                'name' => $this->actividad->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit="save">
                <h1 class="text-2xl font-bold">
                    Información de la Actividad
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información de la actividad.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.tenant-payments.partials.form', ['showForm' => true, 'editForm' => true])

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
