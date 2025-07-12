<?php

use Livewire\Volt\Component;
use App\Models\PaymentType;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Tipos de pago');
    }

    public function deletePaymentType(PaymentType $paymentType)
    {
        // Validar si es el rol principal (ID 1)
        if ($paymentType->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el tipo de pago principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $paymentType->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Tipo de pago eliminado',
                'text' => 'El tipo de pago se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-payment-type-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el tipo de pago',
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
                'name' => 'Tipos de pago',
            ],
        ]" />
    </x-slot>

    @can('create-payment-type')
        <x-slot name="action">
            <x-button info href="{{ route('admin.payment-types.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:payment-type-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(payment_type_id) {
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
                        @this.call('deletePaymentType', payment_type_id);
                    }
                });
            }
        </script>
    @endpush
</div>
