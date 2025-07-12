<?php

use Livewire\Volt\Component;
use App\Models\Actividad;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Actividad');
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


    public function cancel()
    {
        $this->redirect(route('admin.actividads.index'), navigate: true);
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
                'route' => route('admin.actividads.index'),
            ],
            [
                'name' => $this->actividad->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
                <h1 class="text-2xl font-bold">
                    Información de la Actividad
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información de la actividad.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.actividads.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
        </x-card>
    </x-container>
</div>
