<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
     public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: false);
    }
}; ?>


<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Eliminar cuenta</h1>
                <p class="text-sm text-gray-500">Eliminar su cuenta es una acción irreversible.</p>
                <hr class="my-4 border-gray-200">
            </div>

            <div class="flex justify-end">
                <x-button negative icon="trash" x-on:click="$openModal('simpleModal')" >Eliminar cuenta</x-button>
            </div>

        </x-card>
    </x-container>

    <x-modal name="simpleModal">

        <x-card title="Eliminar cuenta">
            <p class="text-red-500">¿Estás seguro de querer eliminar tu cuenta? Esta acción es irreversible. Por favor, ingrese su contraseña actual para confirmar la eliminación.</p>
    

            <form wire:submit.prevent="deleteUser" class="my-6 w-full space-y-6">

                <x-password wire:model="password" label="Contraseña" type="password" required autocomplete="new-password" />

            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button negative icon="trash" wire:click="deleteUser">Eliminar cuenta</x-button>
                    <x-button slate icon="x-mark" label="Cancelar" x-on:click="close" />
                </div>
            </x-slot>
        </form>
        </x-card>
    
    </x-modal>
</div>