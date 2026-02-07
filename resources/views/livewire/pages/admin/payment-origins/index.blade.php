<?php

use Livewire\Volt\Component;
use App\Models\PaymentOrigin;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Origenes de pago');
    }

    public function deletePaymentOrigin(PaymentOrigin $paymentOrigin)
    {
        // Validar si es el rol principal (ID 1)
        if ($paymentOrigin->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el origen de pago principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $paymentOrigin->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Origen de pago eliminado',
                'text' => 'El origen de pago se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-payment-origin-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el origen de pago',
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
                'name' => 'Origenes de pago',
            ],
        ]" />
    </x-slot>

    @can('create-payment-origin')
        <x-slot name="action">
            <x-button info href="{{ route('admin.payment-origins.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:payment-origin-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(payment_origin_id) {
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
                        @this.call('deletePaymentOrigin', payment_origin_id);
                    }
                });
            }
        </script>
    @endpush
</div>
