<?php

use Livewire\Volt\Component;
use App\Models\Plan;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Crear Plan');
    }

    public $name;
    public $description;
    public $price;
    public $trial_period_days;
    public $active;

    public function mount()
    {
        $this->active = true;
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'trial_period_days' => 'required',
            'active' => 'required',
        ]);

        $plan = Plan::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'trial_period_days' => $this->trial_period_days,
            'active' => $this->active,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Plan creado',
            'text' => 'El plan se ha creado correctamente',
        ]);

        $this->redirect(route('admin.plans.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.plans.index'), navigate: true);
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
                'name' => 'Planes',
                'route' => route('admin.plans.index'),
            ],
            [
                'name' => 'Crear Plan',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Plan
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del plan.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.plans.partials.form', ['showForm' => true, 'editForm' => false])

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
