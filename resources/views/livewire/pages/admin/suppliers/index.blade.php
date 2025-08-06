<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Proveedores');
    }

    public function deleteSupplier(Supplier $supplier)
    {
        if ($supplier->sales->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el proveedor porque tiene pedidos asociados',
            ]);
            return;
        }

        if ($supplier->quotes->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el proveedor porque tiene cotizaciones asociadas',
            ]);
            return;
        }

        try {
            $supplier->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Proveedor eliminado',
                'text' => 'El proveedor se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-supplier-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el proveedor',
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
                'name' => 'Proveedores',
            ],
        ]" />
    </x-slot>

    @can('create-customer')
        <x-slot name="action">
            <x-button info href="{{ route('admin.suppliers.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:supplier-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(supplier_id) {
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
                        @this.call('deleteSupplier', supplier_id);
                    }
                });
            }
        </script>
    @endpush
</div>
