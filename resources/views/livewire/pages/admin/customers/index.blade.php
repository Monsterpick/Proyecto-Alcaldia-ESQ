<?php

use Livewire\Volt\Component;
use App\Models\Customer;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Clientes');
    }

    public function deleteCustomer(Customer $customer)
    {
        if ($customer->sales->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el cliente porque tiene pedidos asociados',
            ]);
            return;
        }

        if ($customer->quotes->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el cliente porque tiene cotizaciones asociadas',
            ]);
            return;
        }

        try {
            $customer->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Cliente eliminado',
                'text' => 'El cliente se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-customer-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el cliente',
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
                'name' => 'Clientes',
            ],
        ]" />
    </x-slot>

    @can('create-customer')
        <x-slot name="action">
            <x-button info href="{{ route('admin.customers.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:customer-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(customer_id) {
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
                        @this.call('deleteCustomer', customer_id);
                    }
                });
            }
        </script>
    @endpush
</div>
