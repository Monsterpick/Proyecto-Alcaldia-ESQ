<?php

use Livewire\Volt\Component;
use App\Models\TenantPayment;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Pagos de inquilinos');
    }

    public function deleteTenantPayment(TenantPayment $tenantPayment)
    {

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $tenantPayment->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Pago de inquilino eliminado',
                'text' => 'El pago de inquilino se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-tenant-payment-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el pago de inquilino',
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
                'name' => 'Pagos de inquilinos',
            ],
        ]" />
    </x-slot>

    @can('create-tenant-payment')
        <x-slot name="action">
            <x-button info href="{{ route('admin.tenant-payments.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:tenant-payment-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(tenant_payment_id) {
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
                        @this.call('deleteTenantPayment', tenant_payment_id);
                    }
                });
            }
        </script>
    @endpush
</div>
