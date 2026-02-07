<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Usuarios');
    }

    public function deleteUser(User $user)
    {
        // Validar si es el usuario autenticado
        if ($user->id === Auth::id()) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar tu propia cuenta',
            ]);
            return;
        }

        // Validar si es el usuario principal (ID 1)
        if ($user->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el usuario principal del sistema',
            ]);
            return;
        }

        // Validar si tiene el rol Super Admin
        if ($user->hasRole('Super Admin')) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar un usuario con rol Super Admin',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            // Remover roles antes de eliminar
            $user->roles()->detach();
            $user->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Usuario eliminado',
                'text' => 'El usuario se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-user-table-2zxsby-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el usuario',
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
                'name' => 'Usuarios',
            ],
        ]" />
    </x-slot>

    @can('create-user')
        <x-slot name="action">
            <x-button info href="{{ route('admin.users.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    
    <x-container class="w-full px-4">

        <livewire:user-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(user_id) {
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
                        @this.call('deleteUser', user_id);
                    }
                });
            }
        </script>
    @endpush
</div>
