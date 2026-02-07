<?php

use Livewire\Volt\Component;
use App\Models\Parroquia;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Parroquias');
    }

    public function deleteParroquia(Parroquia $parroquia)
    {
        // Validar si es el rol principal (ID 1)
        if ($parroquia->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la parroquia principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $parroquia->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Parroquia eliminada',
                'text' => 'La parroquia se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-parroquia-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la parroquia',
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
                'name' => 'Parroquias',
            ],
        ]" />
    </x-slot>

    @can('create-parroquia')
        <x-slot name="action">
            <x-button info href="{{ route('admin.parroquias.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:parroquia-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(parroquia_id) {
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
                        @this.call('deleteParroquia', parroquia_id);
                    }
                });
            }
        </script>
    @endpush
</div>
