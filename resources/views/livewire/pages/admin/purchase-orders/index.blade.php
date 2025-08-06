<?php

use Livewire\Volt\Component;
use App\Models\PurchaseOrder;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Ordenes de Compra');
    }

    public function deletePurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->inventories->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la orden de compra porque tiene inventarios asociados',
            ]);
            return;
        }

        try {
            $purchaseOrder->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Orden de Compra eliminada',
                'text' => 'La orden de compra se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-purchase-order-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la orden de compra',
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
                'name' => 'Ordenes de Compra',
            ],
        ]" />
    </x-slot>

    @can('create-customer')
        <x-slot name="action">
            <x-button info href="{{ route('admin.purchase-orders.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:purchase-order-table />
        
    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(purchase_order_id) {
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
                        @this.call('deletePurchaseOrder', purchase_order_id);
                    }
                });
            }
        </script>
    @endpush
</div>
