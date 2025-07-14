<?php

use Livewire\Volt\Component;
use App\Models\Appointment;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Citas');
    }

    public function deleteEstado(Appointment $appointment)
    {
        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $appointment->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Cita eliminada',
                'text' => 'La cita se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-appointment-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la cita',
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
                'name' => 'Citas',
            ],
        ]" />
    </x-slot>

    @can('create-appointment')
        <x-slot name="action">
            <x-button info href="{{ route('admin.appointments.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:appointment-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(appointment_id) {
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
                        @this.call('deleteAppointment', appointment_id);
                    }
                });
            }
        </script>
    @endpush
</div>
