<?php

use Livewire\Volt\Component;
use App\Models\Sale;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Ventas');
    }

    public function deleteSale(Sale $sale)
    {
        if ($sale->inventories->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la venta porque tiene inventarios asociados',
            ]);
            return;
        }

        try {
            $sale->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Venta eliminada',
                'text' => 'La venta se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-sale-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la venta',
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
                'name' => 'Ventas',
            ],
        ]" />
    </x-slot>

    @can('create-customer')
        <x-slot name="action">
            <x-button info href="{{ route('admin.sales.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:sale-table />
        
    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(sale_id) {
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
                        @this.call('deleteSale', sale_id);
                    }
                });
            }
        </script>
    @endpush
</div>
