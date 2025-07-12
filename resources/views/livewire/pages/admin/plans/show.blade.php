<?php

use Livewire\Volt\Component;
use App\Models\Plan;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Plan');
    }

    public $name;
    public $description;
    public $price;
    public $trial_period_days;
    public $active;
    public $plan;

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->price = $plan->price;
        $this->trial_period_days = $plan->trial_period_days;
        $this->active = $plan->active;
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
                'name' => $this->plan->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
                <h1 class="text-2xl font-bold">
                    Información del Plan
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información del plan.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.plans.partials.form', ['showForm' => true, 'editForm' => false])
                
                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
        </x-card>
    </x-container>
</div>
