<?php

use Livewire\Volt\Component;
use App\Models\Doctor;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new #[Layout('layouts.tenancy')]
class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Doctores');
    }

    public function deleteDoctor(Doctor $doctor)
    {
        // Validar si es el rol principal (ID 1)
        if ($doctor->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el origen de pago principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $doctor->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Doctor eliminado',
                'text' => 'El doctor se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-doctor-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el doctor',
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
                'name' => 'Doctores',
            ],
        ]" />
    </x-slot>

    @can('create-patient')
        <x-slot name="action">
            <x-button info href="{{ route('admin.doctors.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:doctor-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(patient_id) {
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
                        @this.call('deleteDoctor', doctor_id);
                    }
                });
            }
        </script>
    @endpush
</div>
