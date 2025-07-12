<?php

use Livewire\Volt\Component;
use App\Models\Actividad;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Actividades');
    }

    public function deleteActividad(Actividad $actividad)
    {
        // Validar si es el rol principal (ID 1)
        if ($actividad->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la actividad principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $actividad->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Actividad eliminada',
                'text' => 'La actividad se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-actividad-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la actividad',
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
                'name' => 'Actividades',
            ],
        ]" />
    </x-slot>

    @can('create-actividad')
        <x-slot name="action">
            <x-button info href="{{ route('admin.actividads.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:actividad-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(actividad_id) {
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
                        @this.call('deleteActividad', actividad_id);
                    }
                });
            }
        </script>
    @endpush
</div>
