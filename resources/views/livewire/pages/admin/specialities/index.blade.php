<?php

use Livewire\Volt\Component;
use App\Models\Speciality;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new #[Layout('layouts.tenancy')]
class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Especialidades');
    }

    public function deleteSpeciality(Speciality $speciality)
    {
        // Validar si es el rol principal (ID 1)
        if ($speciality->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la especialidad principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $speciality->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Especialidad eliminada',
                'text' => 'La especialidad se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-speciality-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la especialidad',
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
                'name' => 'Especialidades',
            ],
        ]" />
    </x-slot>

    @can('create-speciality')
        <x-slot name="action">
            <x-button info href="{{ route('admin.specialities.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:speciality-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(speciality_id) {
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
                        @this.call('deleteSpeciality', speciality_id);
                    }
                });
            }
        </script>
    @endpush
</div>
