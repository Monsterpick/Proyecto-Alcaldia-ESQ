<?php

use Livewire\Volt\Component;
use App\Models\Municipio;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Municipios');
    }

    public function deleteMunicipio(Municipio $municipio)
    {
        // Validar si es el municipio principal (ID 1)
        if ($municipio->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el municipio principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $municipio->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Municipio eliminado',
                'text' => 'El municipio se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-municipio-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el municipio',
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
                'name' => 'Municipios',
            ],
        ]" />
    </x-slot>

    @can('create-municipio')
        <x-slot name="action">
            <x-button info href="{{ route('admin.municipios.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:municipio-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(municipio_id) {
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
                        @this.call('deleteMunicipio', municipio_id);
                    }
                });
            }
        </script>
    @endpush
</div>
