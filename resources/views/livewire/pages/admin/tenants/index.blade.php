<?php

use Livewire\Volt\Component;
use App\Models\Tenant;
use Illuminate\View\View;

new class extends Component {


    public $tenant_id = null;

    public function rendering(View $view)
    {
        $view->title('Tenants');
    }

    public function mount()
    {

    }

    public function deleteTenant(Tenant $tenant)
    {
        // Validar si es el rol principal (ID 1)
        if ($tenant->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el rol principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $tenant->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Tenant eliminado',
                'text' => 'El tenant se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-tenant-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el tenant',
            ]);
        }
    }

    public function openPaymentModal($tenantId)
    {
        if ($tenantId) {
            $this->dispatch('set-tenant-id', $tenantId);
            $this->dispatch('open-payment-modal');
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
                'name' => 'Tenants',
            ],
        ]" />
    </x-slot>

    @can('create-tenant')
        <x-slot name="action">
            <x-button info href="{{ route('admin.tenants.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <div class="w-full px-4">
        <livewire:tenant-table />

        <livewire:pages.admin.tenants.components.payment-modal :tenant_id="$tenant_id" />
    </div>

    


    @push('scripts')
        <script>
            function confirmDelete(tenant_id) {
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
                        @this.call('deleteTenant', tenant_id);
                    }
                });
            }

            function showPaymentModal(tenant_id) {
                console.log(tenant_id);
                @this.call('openPaymentModal', tenant_id);
            }
        </script>
    @endpush
</div>
