<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Producto');
    }

    public $name;
    public $description;
    public $categories = [];
    public $expedition_date = '';
    public $expiration_date = '';
    public $price;
    public $category_id;

    public function mount()
    {
        $this->name = '';
        $this->description = '';
        $this->categories = Category::orderBy('name')->get();
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required|unique:categories,name|max:255|min:3',
            'description' => 'required|nullable|max:255|min:3',
            'expedition_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'expedition_date' => $this->expedition_date,
            'expiration_date' => $this->expiration_date,
            'price' => $this->price,
            'category_id' => $this->category_id,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Producto creado',
            'text' => 'El producto se ha creado correctamente',
        ]);

        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.products.index'), navigate: true);
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
                'name' => 'Productos',
                'route' => route('admin.products.index'),
            ],
            [
                'name' => 'Crear Producto',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Producto
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del producto.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.products.partials.form', ['showForm' => true, 'editForm' => false])

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
