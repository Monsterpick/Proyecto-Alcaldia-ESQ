<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

new class extends Component {

    public $name;
    public $last_name;
    public $phone;
    public $email;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->last_name = Auth::user()->last_name;
        $this->phone = Auth::user()->phone;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::user()->id,
        ]);
        
        $user = Auth::user();
        $user->name = $this->name;
        $user->last_name = $this->last_name;
        $user->phone = $this->phone;
        $user->email = $this->email;
        $user->save();

        $this->dispatch('swal', [
            'title' => '¡Exito!',
            'icon' => 'success',
            'text' => 'El perfil se ha actualizado correctamente',
        ]);
        
    }
}; ?>


<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Mi perfil</h1>
                <p class="text-sm text-gray-500">Actualiza tu nombre y correo electrónico.</p>
                <hr class="my-4 border-gray-200">
            </div>
            
            <form wire:submit.prevent="updateProfileInformation" class="my-6 w-full space-y-6">
                <x-input wire:model="name" label="Nombre" type="text" required autofocus autocomplete="name" />
                <x-input wire:model="last_name" label="Apellido" type="text" required autocomplete="last_name" />
                <x-input wire:model="phone" label="Teléfono" type="text" required autocomplete="phone" />
                <x-input wire:model="email" label="Correo electrónico" type="email" required autocomplete="email" />
                
                <x-slot name="footer">
                    <div class="flex justify-end">
                        <x-button info icon="check" wire:click="updateProfileInformation">Guardar</x-button>
                    </div>
                </x-slot>
            </form>
            
        </x-card>
    </x-container>
</div>