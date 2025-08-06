<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Categoría');
    }

    public $name;
    public $description;

    public function mount()
    {
        $this->name = '';
        $this->description = '';
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required|unique:categories,name|max:255|min:3',
            'description' => 'required',
        ]);

        $category = Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Categoría creada',
            'text' => 'La categoría se ha creado correctamente',
        ]);

        $this->redirect(route('admin.categories.index'), navigate: true);
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
                'name' => 'Crear Estado',
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
                    Registre la información del estado.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.estados.partials.form', ['showForm' => true, 'editForm' => false])

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
