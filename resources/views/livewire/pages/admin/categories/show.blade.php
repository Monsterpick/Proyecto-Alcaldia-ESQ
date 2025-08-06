<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Categoría');
    }

    public $category;
    public $name;
    public $description;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function cancel()
    {
        $this->redirect(route('admin.categories.index'), navigate: true);
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
                'name' => 'Categorías',
                'route' => route('admin.categories.index'),
            ],
            [
                'name' => $this->category->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <h1 class="text-2xl font-bold">
                Información de la Categoría
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Muestra la información de la categoría.
            </p>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>

            
            @include('livewire.pages.admin.categories.partials.form', ['showForm' => true, 'editForm' => false])

            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
