<?php

use Livewire\Volt\Component;
use App\Models\Estatus;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Estatuses');
    }

    public function deleteEstatus(Estatus $estatus)
    {
        // Validar si es el estatus principal (ID 1)
        if ($estatus->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el estatus principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $estatus->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Estatus eliminado',
                'text' => 'El estatus se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-estatus-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el estatus',
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
                'name' => 'Estatuses',
            ],
        ]" />
    </x-slot>

    @can('create-estatus')
        <x-slot name="action">
            <x-button info href="{{ route('admin.estatuses.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:estatus-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(estatus_id) {
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
                        @this.call('deleteEstatus', estatus_id);
                    }
                });
            }
        </script>
    @endpush
</div>
