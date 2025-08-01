<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $photo;
    public $image_url;

    public function mount()
    {
        $this->image_url = Auth::user()->image_url;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
    }

    public function updateProfilePhoto()
    {
        $this->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
        
        if ($this->photo) {
            // Eliminar la imagen anterior si existe y no es la imagen por defecto
            if ($this->image_url && !str_contains($this->image_url, 'no_user_image.png')) {
                Storage::disk('public')->delete($this->image_url);
            }

            // Guardar la nueva imagen
            $this->image_url = $this->photo->store('images/users', 'public');

            Auth::user()->update([
                'image_url' => $this->image_url,
            ]);

            $this->dispatch('swal', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'text' => 'La imagen de perfil se ha actualizado correctamente',
            ]);

            // Limpiar la imagen temporal
            $this->photo = null;
        }
    }
}; ?>

<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Imagen de Perfil</h1>
                <p class="text-sm text-gray-500">Por favor, seleccione una imagen para el usuario.</p>
                <hr class="my-4 border-gray-200">
            </div>
            
            <form wire:submit.prevent="updateProfilePhoto" class="my-6 w-full space-y-6">
                <figure class="mb-4 mt-4 relative text-black">
                    <div class="absolute top-8 right-8">
                        <label class="text-black flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                            <i class="fas fa-camera mr-2"></i> Actualizar Imagen
                            <input type="file" wire:model="photo" class="hidden" accept="image/*">
                        </label>
                    </div>
                    <img src="{{ $photo ? $photo->temporaryUrl() : (Storage::url($image_url ?: 'images/no_user_image.png')) }}"
                        alt="Imagen de usuario"
                        class="h-64 w-64 object-cover object-center rounded-lg">
                </figure>

                <div wire:loading wire:target="photo" class="text-sm text-gray-500 dark:text-gray-400">
                    Cargando imagen...
                </div>

                <x-validation-errors />
                
                <x-slot name="footer">
                    <div class="flex justify-end">
                        <x-button info icon="check" label="Guardar" wire:click="updateProfilePhoto">
                        </x-button>
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>