<?php

use Livewire\Volt\Component;
use App\Models\Speciality;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new #[Layout('layouts.tenancy')]
class extends Component {

    public function rendering(View $view)
    {
        $view->title('Editar Especialidad');
    }

    public $name;
    public $speciality;

    public function mount(Speciality $speciality)
    {
        $this->speciality = $speciality;
        $this->name = $speciality->name;
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
        ]);

        $this->speciality->update([
            'name' => $this->name,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Especialidad actualizada',
            'text' => 'La especialidad se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.specialities.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.specialities.index'), navigate: true);
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
                'name' => 'Especialidades',
                'route' => route('admin.specialities.index'),
            ],
            [
                'name' => $this->speciality->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit="save">
                <h1 class="text-2xl font-bold">
                    Información de la Especialidad
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información de la especialidad.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.specialities.partials.form', ['showForm' => true, 'editForm' => true])

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
