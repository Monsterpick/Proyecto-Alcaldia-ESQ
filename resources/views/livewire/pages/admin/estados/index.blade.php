<?php

use Livewire\Volt\Component;
use App\Models\Estado;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Estados');
    }

    public function deleteEstado(Estado $estado)
    {
        // Validar si es el estado principal (ID 1)
        if ($estado->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el rol principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $estado->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Estado eliminado',
                'text' => 'El estado se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-estado-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el estado',
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
                'name' => 'Estados',
            ],
        ]" />
    </x-slot>

    @can('create-estado')
        <x-slot name="action">
            <x-button info href="{{ route('admin.estados.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:estado-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(estado_id) {
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
                        @this.call('deleteEstado', estado_id);
                    }
                });
            }
        </script>
    @endpush
</div>
