<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

new class extends Component {

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';


    public function updatePassword()
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }
        
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('swal', [
            'title' => '¡Exito!',
            'icon' => 'success',
            'text' => 'La contraseña se ha actualizado correctamente',
        ]);
        
    }
}; ?>


<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Contraseña</h1>
                <p class="text-sm text-gray-500">Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerla segura.</p>
                <hr class="my-4 border-gray-200">
            </div>
            
            <form wire:submit.prevent="updatePassword" class="my-6 w-full space-y-6">
                <x-password wire:model="current_password" label="Contraseña actual" type="password" required autofocus autocomplete="current-password" />
                <x-password wire:model="password" label="Nueva contraseña" type="password" required autocomplete="new-password" />
                <x-password wire:model="password_confirmation" label="Confirmar contraseña" type="password" required autocomplete="new-password" />
                
                <x-slot name="footer">
                    <div class="flex justify-end">
                        <x-button info icon="check" wire:click="updatePassword">Guardar</x-button>
                    </div>
                </x-slot>
            </form>
            
        </x-card>
    </x-container>
</div>