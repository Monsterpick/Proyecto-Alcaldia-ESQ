<?php

use Livewire\Volt\Component;
use App\Models\Warehouse;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Almacenes');
    }

    public function deleteWarehouse(Warehouse $warehouse)
    {
        if ($warehouse->inventories->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el almacén porque tiene inventarios asociados',
            ]);
            return;
        }

        try {
            $warehouse->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Almacén eliminado',
                'text' => 'El almacén se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-warehouse-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el almacén',
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
                'name' => 'Almacenes',
            ],
        ]" />
    </x-slot>

    @can('create-customer')
        <x-slot name="action">
            <x-button info href="{{ route('admin.warehouses.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:warehouse-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(warehouse_id) {
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
                        @this.call('deleteWarehouse', warehouse_id);
                    }
                });
            }
        </script>
    @endpush
</div>
