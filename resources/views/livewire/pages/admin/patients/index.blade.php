<?php

use Livewire\Volt\Component;
use App\Models\Patient;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Pacientes');
    }

    public function deletePatient(Patient $patient)
    {
        // Validar si es el rol principal (ID 1)
        if ($patient->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el origen de pago principal del sistema',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $patient->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Paciente eliminado',
                'text' => 'El paciente se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-patient-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el paciente',
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
                'name' => 'Pacientes',
            ],
        ]" />
    </x-slot>

    @can('create-patient')
        <x-slot name="action">
            <x-button info href="{{ route('admin.patients.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:patient-table />

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
                        @this.call('deletePatient', patient_id);
                    }
                });
            }
        </script>
    @endpush
</div>
