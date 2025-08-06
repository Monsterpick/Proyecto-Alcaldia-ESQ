<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Productos');
    }

    public function deleteProduct(Product $product)
    {
        if($product->inventories->count() > 0){
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'El producto tiene inventarios asociados y no puede ser eliminado',
            ]);
        }

        if($product->purchaseOrders->count() > 0){
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'El producto tiene ordenes de compra asociadas y no puede ser eliminado',
            ]);
        }

        if($product->quotes->count() > 0){
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'El producto tiene cotizaciones asociadas y no puede ser eliminado',
            ]);
        }

        try {
            $product->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Producto eliminado',
                'text' => 'El producto se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-product-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el producto',
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
                'name' => 'Productos',
            ],
        ]" />
    </x-slot>

    @can('create-category')
        <x-slot name="action">
            <x-button info href="{{ route('admin.products.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:product-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(product_id) {
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
                        @this.call('deleteProduct', product_id);
                    }
                });
            }
        </script>
    @endpush
</div>
