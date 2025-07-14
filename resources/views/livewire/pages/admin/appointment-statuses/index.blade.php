<?php

use Livewire\Volt\Component;
use App\Models\AppointmentStatus;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Estatus de Cita');
    }

    public function deleteAppointmentStatus(AppointmentStatus $appointment_status)
    {
        // Validar si es el estado principal (ID 1)
        if ($appointment_status->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el rol principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $appointment_status->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Estatus de Cita eliminado',
                'text' => 'El estatus de cita se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-appointment-status-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el estatus de cita',
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
                'name' => 'Estatus de Cita',
            ],
        ]" />
    </x-slot>

    @can('create-appointment-status')
        <x-slot name="action">
            <x-button info href="{{ route('admin.appointment-statuses.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:appointment-status-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(appointment_status_id) {
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
                        @this.call('deleteAppointmentStatus', appointment_status_id);
                    }
                });
            }
        </script>
    @endpush
</div>
