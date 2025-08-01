<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

new class extends Component {
    use WithFileUploads;

    public $logo;
    public $logo_background_solid;
    public $logo_grey;
    public $logo_horizontal;
    public $logo_horizontal_background_solid;
    public $logo_icon;
    public $logo_icon_grey;

    public $temp_logo = null;
    public $temp_logo_background_solid = null;
    public $temp_logo_grey = null;
    public $temp_logo_horizontal = null;
    public $temp_logo_horizontal_background_solid = null;
    public $temp_logo_icon = null;
    public $temp_logo_icon_grey = null;

    protected $rules = [
        'temp_logo' => 'nullable|image|max:2048',
        'temp_logo_background_solid' => 'nullable|image|max:2048',
        'temp_logo_grey' => 'nullable|image|max:2048',
        'temp_logo_horizontal' => 'nullable|image|max:2048',
        'temp_logo_horizontal_background_solid' => 'nullable|image|max:2048',
        'temp_logo_icon' => 'nullable|image|max:2048',
        'temp_logo_icon_grey' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->logo = Setting::get('logo');
        $this->logo_background_solid = Setting::get('logo_background_solid');
        $this->logo_grey = Setting::get('logo_grey');
        $this->logo_horizontal = Setting::get('logo_horizontal');
        $this->logo_horizontal_background_solid = Setting::get('logo_horizontal_background_solid');
        $this->logo_icon = Setting::get('logo_icon');
        $this->logo_icon_grey = Setting::get('logo_icon_grey');
    }

    public function updatedTempLogo()
    {
        $this->validate(['temp_logo' => 'image|max:2048']);
    }

    public function updatedTempLogoBackgroundSolid()
    {
        $this->validate(['temp_logo_background_solid' => 'image|max:2048']);
    }

    public function updatedTempLogoGrey()
    {
        $this->validate(['temp_logo_grey' => 'image|max:2048']);
    }

    public function updatedTempLogoHorizontal()
    {
        $this->validate(['temp_logo_horizontal' => 'image|max:2048']);
    }

    public function updatedTempLogoHorizontalBackgroundSolid()
    {
        $this->validate(['temp_logo_horizontal_background_solid' => 'image|max:2048']);
    }

    public function updatedTempLogoIcon()
    {
        $this->validate(['temp_logo_icon' => 'image|max:2048']);
    }

    public function updatedTempLogoIconGrey()
    {
        $this->validate(['temp_logo_icon_grey' => 'image|max:2048']);
    }

    protected function getDefaultFilename($type)
    {
        $defaults = [
            'logo' => '1_logo',
            'logo_background_solid' => '2_logo_background_solid',
            'logo_grey' => '3_logo_grey',
            'logo_horizontal' => '4_logo_horizontal',
            'logo_horizontal_background_solid' => '5_logo_horizontal_background_solid',
            'logo_icon' => '6_logo_icon',
            'logo_icon_grey' => '7_logo_icon_grey'
        ];

        return $defaults[$type] ?? $type;
    }

    public function saveLogo($type)
    {
        $tempProperty = "temp_" . $type;
        $property = $type;
        
        if (!$this->{$tempProperty}) {
            return;
        }

        try {
            $this->validate([$tempProperty => 'image|max:2048']);

            // Obtener la extensión del archivo nuevo
            $extension = $this->{$tempProperty}->getClientOriginalExtension();
            
            // Obtener el nombre base del archivo
            $filename = $this->getDefaultFilename($type);
            
            // Construir el path completo
            $path = "images/{$filename}.{$extension}";

            // Si existe un archivo anterior, eliminarlo
            if ($this->{$property}) {
                Storage::disk('public')->delete($this->{$property});
            }

            // Guardar el nuevo archivo con el nombre específico
            $this->{$tempProperty}->storeAs('', $path, 'public');
            
            // Actualizar la base de datos y la propiedad
            Setting::set($property, $path);
            $this->{$property} = $path;
            $this->{$tempProperty} = null;

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'El logo se ha actualizado correctamente',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar el logo: ' . $e->getMessage(),
            ]);
        }
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Logos'],
        ]" />
    </x-slot>

    <x-container class="w-full px-4">
        <x-card title="Logos de la empresa">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Logo Principal -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Logo Principal</h3>
                    <figure class="relative">
                        <div class="absolute top-2 right-2">
                            <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                                <i class="fas fa-camera mr-2"></i> Actualizar
                                <input type="file" wire:model="temp_logo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <img src="{{ $temp_logo ? $temp_logo->temporaryUrl() : ($logo ? Storage::url($logo) : Storage::url('images/placeholder.png')) }}"
                            alt="Logo Principal"
                            class="w-full h-48 object-contain rounded-lg border">
                    </figure>
                    @if($temp_logo)
                        <div class="flex justify-end">
                            <x-button info wire:click="saveLogo('logo')" icon="photo" spinner="saveLogo" label="Guardar Logo" />
                        </div>
                    @endif
                </div>

                <!-- Logo con Fondo Sólido -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Logo con Fondo Sólido</h3>
                    <figure class="relative">
                        <div class="absolute top-2 right-2">
                            <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                                <i class="fas fa-camera mr-2"></i> Actualizar
                                <input type="file" wire:model="temp_logo_background_solid" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <img src="{{ $temp_logo_background_solid ? $temp_logo_background_solid->temporaryUrl() : ($logo_background_solid ? Storage::url($logo_background_solid) : Storage::url('images/placeholder.png')) }}"
                            alt="Logo con Fondo Sólido"
                            class="w-full h-48 object-contain rounded-lg border">
                    </figure>
                    @if($temp_logo_background_solid)
                        <div class="flex justify-end">
                            <x-button info wire:click="saveLogo('logo_background_solid')" icon="photo" spinner="saveLogo" label="Guardar Logo" />
                        </div>
                    @endif
                </div>

                <!-- Logo Gris -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Logo Escala de Grises</h3>
                    <figure class="relative">
                        <div class="absolute top-2 right-2">
                            <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                                <i class="fas fa-camera mr-2"></i> Actualizar
                                <input type="file" wire:model="temp_logo_grey" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <img src="{{ $temp_logo_grey ? $temp_logo_grey->temporaryUrl() : ($logo_grey ? Storage::url($logo_grey) : Storage::url('images/placeholder.png')) }}"
                            alt="Logo Escala de Grises"
                            class="w-full h-48 object-contain rounded-lg border">
                    </figure>
                    @if($temp_logo_grey)
                        <div class="flex justify-end">
                            <x-button info wire:click="saveLogo('logo_grey')" icon="photo" spinner="saveLogo" label="Guardar Logo" />
                        </div>
                    @endif
                </div>

                <!-- Logo Horizontal -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Logo Horizontal</h3>
                    <figure class="relative">
                        <div class="absolute top-2 right-2">
                            <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                                <i class="fas fa-camera mr-2"></i> Actualizar
                                <input type="file" wire:model="temp_logo_horizontal" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <img src="{{ $temp_logo_horizontal ? $temp_logo_horizontal->temporaryUrl() : ($logo_horizontal ? Storage::url($logo_horizontal) : Storage::url('images/placeholder.png')) }}"
                            alt="Logo Horizontal"
                            class="w-full h-48 object-contain rounded-lg border">
                    </figure>
                    @if($temp_logo_horizontal)
                        <div class="flex justify-end">
                            <x-button info wire:click="saveLogo('logo_horizontal')" icon="photo" spinner="saveLogo" label="Guardar Logo" />
                        </div>
                    @endif
                </div>

                <!-- Logo Horizontal con Fondo Sólido -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Logo Horizontal con Fondo Sólido</h3>
                    <figure class="relative">
                        <div class="absolute top-2 right-2">
                            <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                                <i class="fas fa-camera mr-2"></i> Actualizar
                                <input type="file" wire:model="temp_logo_horizontal_background_solid" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <img src="{{ $temp_logo_horizontal_background_solid ? $temp_logo_horizontal_background_solid->temporaryUrl() : ($logo_horizontal_background_solid ? Storage::url($logo_horizontal_background_solid) : Storage::url('images/placeholder.png')) }}"
                            alt="Logo Horizontal con Fondo Sólido"
                            class="w-full h-48 object-contain rounded-lg border">
                    </figure>
                    @if($temp_logo_horizontal_background_solid)
                        <div class="flex justify-end">
                            <x-button info wire:click="saveLogo('logo_horizontal_background_solid')" icon="photo" spinner="saveLogo" label="Guardar Logo" />
                        </div>
                    @endif
                </div>

                <!-- Logo Icono -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Logo Icono</h3>
                    <figure class="relative">
                        <div class="absolute top-2 right-2">
                            <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                                <i class="fas fa-camera mr-2"></i> Actualizar
                                <input type="file" wire:model="temp_logo_icon" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <img src="{{ $temp_logo_icon ? $temp_logo_icon->temporaryUrl() : ($logo_icon ? Storage::url($logo_icon) : Storage::url('images/placeholder.png')) }}"
                            alt="Logo Icono"
                            class="w-full h-48 object-contain rounded-lg border">
                    </figure>
                    @if($temp_logo_icon)
                        <div class="flex justify-end">
                            <x-button info wire:click="saveLogo('logo_icon')" icon="photo" spinner="saveLogo" label="Guardar Logo" />
                        </div>
                    @endif
                </div>

                <!-- Logo Icono Gris -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Logo Icono Gris</h3>
                    <figure class="relative">
                        <div class="absolute top-2 right-2">
                            <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                                <i class="fas fa-camera mr-2"></i> Actualizar
                                <input type="file" wire:model="temp_logo_icon_grey" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <img src="{{ $temp_logo_icon_grey ? $temp_logo_icon_grey->temporaryUrl() : ($logo_icon_grey ? Storage::url($logo_icon_grey) : Storage::url('images/placeholder.png')) }}"
                            alt="Logo Icono Gris"
                            class="w-full h-48 object-contain rounded-lg border">
                    </figure>
                    @if($temp_logo_icon_grey)
                        <div class="flex justify-end">
                            <x-button info wire:click="saveLogo('logo_icon_grey')" icon="photo" spinner="saveLogo" label="Guardar Logo" />
                        </div>
                    @endif
                </div>
            </div>
        </x-card>
    </x-container>
</div>