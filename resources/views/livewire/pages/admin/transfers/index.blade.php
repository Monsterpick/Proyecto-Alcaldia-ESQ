<?php

use Livewire\Volt\Component;
use App\Models\Transfer;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Transferencias');
    }

    public function deleteTransfer(Transfer $transfer)
    {
        if ($transfer->inventories->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la transferencia porque tiene inventarios asociados',
            ]);
            return;
        }

        try {
            $transfer->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Transferencia eliminada',
                'text' => 'La transferencia se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-transfer-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la transferencia',
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
                'name' => 'Transferencias',
            ],
        ]" />
    </x-slot>

    @can('create-customer')
        <x-slot name="action">
            <x-button info href="{{ route('admin.transfers.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

       <livewire:transfer-table />
        
    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(transfer_id) {
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
                        @this.call('deleteTransfer', transfer_id);
                    }
                });
            }
        </script>
    @endpush
</div>
