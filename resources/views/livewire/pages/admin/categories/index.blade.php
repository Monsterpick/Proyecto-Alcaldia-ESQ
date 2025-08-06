<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Categorías');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->products->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la categoría porque tiene productos asociados',
            ]);
            return;
        }

        try {
            $category->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Categoría eliminada',
                'text' => 'La categoría se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-category-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la categoría',
            ]);
        }
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
            ],
        ]" />
    </x-slot>

    @can('create-category')
        <x-slot name="action">
            <x-button info href="{{ route('admin.categories.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:category-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(category_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteCategory', category_id);
                    }
                });
            }
        </script>
    @endpush
</div>
