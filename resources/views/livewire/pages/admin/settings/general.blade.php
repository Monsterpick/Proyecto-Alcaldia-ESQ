<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Setting;

new class extends Component {
    use WithFileUploads;

    // Identidad institucional
    public $name;
    public $razon_social;
    public $rif;
    public $description;
    public $long_description;
    public $actividad;

    // Contacto
    public $telefono_principal;
    public $telefono_secundario;
    public $email_principal;
    public $email_secundario;

    // Ubicación
    public $direccion_fiscal;
    public $oficina_principal;
    public $horario_atencion;
    public $domain;

    // Logo
    public $logo;
    public $temp_logo = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'razon_social' => 'required|string|max:255',
        'rif' => 'required|string|max:20',
        'description' => 'required|string|max:255',
        'long_description' => 'required|string|max:1000',
        'actividad' => 'required|string|max:255',
        'telefono_principal' => 'required|string|max:20',
        'telefono_secundario' => 'nullable|string|max:20',
        'email_principal' => 'required|email|max:255',
        'email_secundario' => 'nullable|email|max:255',
        'direccion_fiscal' => 'required|string|max:500',
        'oficina_principal' => 'required|string|max:255',
        'horario_atencion' => 'required|string|max:255',
        'domain' => 'required|string|max:255',
        'temp_logo' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'required' => 'Este campo es obligatorio.',
        'email' => 'Debe ser un correo válido.',
        'max' => 'No debe exceder :max caracteres.',
        'temp_logo.image' => 'El archivo debe ser una imagen.',
        'temp_logo.max' => 'La imagen no debe exceder 2MB.',
    ];

    public function rendering(View $view)
    {
        $view->title('Datos de la Institución');
    }

    public function mount()
    {
        $this->name = Setting::get('name', '');
        $this->razon_social = Setting::get('razon_social', '');
        $this->rif = Setting::get('rif', '');
        $this->description = Setting::get('description', '');
        $this->long_description = Setting::get('long_description', '');
        $this->actividad = Setting::get('actividad', '');
        $this->telefono_principal = Setting::get('telefono_principal', '');
        $this->telefono_secundario = Setting::get('telefono_secundario', '');
        $this->email_principal = Setting::get('email_principal', '');
        $this->email_secundario = Setting::get('email_secundario', '');
        $this->direccion_fiscal = Setting::get('direccion_fiscal', '');
        $this->oficina_principal = Setting::get('oficina_principal', '');
        $this->horario_atencion = Setting::get('horario_atencion', '');
        $this->domain = Setting::get('domain', '');
        $this->logo = Setting::get('logo', '');
    }

    public function updatedTempLogo()
    {
        $this->validate(['temp_logo' => 'image|max:2048']);
    }

    public function saveLogo()
    {
        if (!$this->temp_logo) return;

        $this->validate(['temp_logo' => 'image|max:2048']);

        $extension = $this->temp_logo->getClientOriginalExtension();
        $path = "images/1_logo.{$extension}";

        if ($this->logo) {
            Storage::disk('public')->delete($this->logo);
        }

        $this->temp_logo->storeAs('', $path, 'public');
        Setting::set('logo', $path);
        $this->logo = $path;
        $this->temp_logo = null;

        // Limpiar cache
        cache()->forget('app_branding');

        // Actualizar navbar en tiempo real
        $this->dispatch('branding-updated', [
            'name' => $this->name,
            'logo' => asset('storage/' . $path),
        ]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Logo actualizado',
            'text' => 'El logo se ha guardado correctamente.',
            'timer' => 3000,
        ]);
    }

    public function removeLogo()
    {
        if ($this->logo) {
            Storage::disk('public')->delete($this->logo);
            Setting::set('logo', '');
            $this->logo = '';
            cache()->forget('app_branding');

            $this->dispatch('branding-updated', [
                'name' => $this->name,
                'logo' => asset('logo-alcaldia-escuque.png'),
            ]);

            $this->dispatch('swal', [
                'icon' => 'info',
                'title' => 'Logo eliminado',
                'text' => 'Se ha restaurado el logo predeterminado.',
                'timer' => 3000,
            ]);
        }
    }

    public function saveSettings()
    {
        try {
            $this->validate();

            $settings = [
                'name' => $this->name,
                'razon_social' => $this->razon_social,
                'rif' => $this->rif,
                'description' => $this->description,
                'long_description' => $this->long_description,
                'actividad' => $this->actividad,
                'telefono_principal' => $this->telefono_principal,
                'telefono_secundario' => $this->telefono_secundario,
                'email_principal' => $this->email_principal,
                'email_secundario' => $this->email_secundario,
                'direccion_fiscal' => $this->direccion_fiscal,
                'oficina_principal' => $this->oficina_principal,
                'horario_atencion' => $this->horario_atencion,
                'domain' => $this->domain,
            ];

            foreach ($settings as $key => $value) {
                Setting::set($key, $value);
            }

            // Limpiar cache de branding
            cache()->forget('app_branding');

            // Actualizar navbar en tiempo real
            $logoUrl = !empty($this->logo) ? asset('storage/' . $this->logo) : asset('logo-alcaldia-escuque.png');
            $this->dispatch('branding-updated', [
                'name' => $this->name,
                'logo' => $logoUrl,
            ]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Configuración guardada',
                'text' => 'Los datos institucionales se han actualizado correctamente.',
                'timer' => 3000,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error de validación',
                'text' => 'Por favor, verifica los campos marcados.',
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage(),
            ]);
        }
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="text-sm font-medium" style="color: var(--color-text-tertiary);">
                        <i class="fa-solid fa-house-chimney mr-1.5"></i> Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-xs mx-2" style="color: var(--color-text-muted);"></i>
                        <span class="text-sm font-medium" style="color: var(--color-text-primary);">Datos Institucionales</span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background-color: var(--color-bg-secondary);">
                <i class="fa-solid fa-building-columns text-2xl" style="color: var(--color-blue-600);"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold" style="color: var(--color-text-primary);">
                Datos Institucionales
            </h1>
            <p class="mt-2 text-sm" style="color: var(--color-text-tertiary);">
                Configura la identidad, contacto y ubicación de tu institución
            </p>
        </div>

        <form wire:submit.prevent="saveSettings">

            <!-- ====== LOGO INSTITUCIONAL ====== -->
            <div class="rounded-2xl p-6 mb-6 border" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary); box-shadow: var(--shadow-md);">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: var(--color-blue-50);">
                        <i class="fa-solid fa-image text-lg" style="color: var(--color-blue-600);"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-base" style="color: var(--color-text-primary);">Logo Institucional</h2>
                        <p class="text-xs" style="color: var(--color-text-tertiary);">Se mostrará en la barra de navegación y el login</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Preview del logo -->
                    <div class="w-36 h-36 rounded-2xl border-2 border-dashed flex items-center justify-center overflow-hidden flex-shrink-0"
                         style="border-color: var(--color-border-secondary); background-color: var(--color-bg-secondary);">
                        @if($temp_logo)
                            <img src="{{ $temp_logo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-contain p-2">
                        @elseif($logo)
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo actual" class="w-full h-full object-contain p-2">
                        @else
                            <div class="text-center p-4">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2" style="color: var(--color-text-muted);"></i>
                                <p class="text-xs" style="color: var(--color-text-muted);">Sin logo</p>
                            </div>
                        @endif
                    </div>

                    <!-- Controles -->
                    <div class="flex-1 space-y-3">
                        <p class="text-sm" style="color: var(--color-text-secondary);">
                            Sube el logo principal de tu institución. Formatos: JPG, PNG, SVG. Máximo 2MB.
                        </p>

                        <div class="flex flex-wrap gap-2">
                            <label class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl cursor-pointer transition-colors"
                                   style="background-color: var(--color-blue-50); color: var(--color-blue-600);"
                                   onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                <i class="fa-solid fa-upload"></i>
                                <span>Seleccionar archivo</span>
                                <input type="file" wire:model="temp_logo" class="hidden" accept="image/*">
                            </label>

                            @if($temp_logo)
                                <button type="button" wire:click="saveLogo"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-xl transition-colors"
                                        style="background-color: var(--color-green-600);">
                                    <i class="fa-solid fa-check"></i> Guardar logo
                                </button>
                            @endif

                            @if($logo)
                                <button type="button" wire:click="removeLogo"
                                        wire:confirm="¿Eliminar el logo actual?"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl transition-colors"
                                        style="color: var(--color-red-600); background-color: var(--color-red-50);">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            @endif
                        </div>

                        <div wire:loading wire:target="temp_logo" class="text-sm" style="color: var(--color-blue-600);">
                            <i class="fa-solid fa-spinner fa-spin mr-1"></i> Cargando imagen...
                        </div>

                        @error('temp_logo')
                            <p class="text-sm" style="color: var(--color-red-600);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ====== IDENTIDAD INSTITUCIONAL ====== -->
            <div class="rounded-2xl p-6 mb-6 border" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary); box-shadow: var(--shadow-md);">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: var(--color-blue-50);">
                        <i class="fa-solid fa-id-card text-lg" style="color: var(--color-blue-600);"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-base" style="color: var(--color-text-primary);">Identidad Institucional</h2>
                        <p class="text-xs" style="color: var(--color-text-tertiary);">Nombre, razón social y datos legales</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input label="Nombre de la institución" wire:model="name" placeholder="Ej: Alcaldía del Municipio Escuque" icon="building-office" />
                    </div>
                    <div>
                        <x-input label="Razón social" wire:model="razon_social" placeholder="Razón social completa" icon="document-text" />
                    </div>
                    <div>
                        <x-input label="RIF" wire:model="rif" placeholder="J-XXXXXXXXX" icon="identification" />
                    </div>
                    <div>
                        <x-input label="Actividad principal" wire:model="actividad" placeholder="Ej: Gestión Pública Municipal" icon="briefcase" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input label="Descripción corta" wire:model="description" placeholder="Breve descripción de la institución" icon="chat-bubble-left" />
                    </div>
                    <div class="md:col-span-2">
                        <x-textarea label="Descripción larga" wire:model="long_description" placeholder="Descripción detallada para la página de login y presentación..." rows="3" />
                    </div>
                </div>
            </div>

            <!-- ====== CONTACTO ====== -->
            <div class="rounded-2xl p-6 mb-6 border" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary); box-shadow: var(--shadow-md);">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: var(--color-green-50);">
                        <i class="fa-solid fa-phone text-lg" style="color: var(--color-green-600);"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-base" style="color: var(--color-text-primary);">Información de Contacto</h2>
                        <p class="text-xs" style="color: var(--color-text-tertiary);">Teléfonos y correos electrónicos</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input label="Teléfono principal" wire:model="telefono_principal" placeholder="0416-0000000" icon="phone" />
                    </div>
                    <div>
                        <x-input label="Teléfono secundario" wire:model="telefono_secundario" placeholder="0414-0000000 (opcional)" icon="phone" />
                    </div>
                    <div>
                        <x-input label="Correo principal" wire:model="email_principal" placeholder="correo@institucion.gob.ve" icon="envelope" />
                    </div>
                    <div>
                        <x-input label="Correo secundario" wire:model="email_secundario" placeholder="otro@institucion.gob.ve (opcional)" icon="envelope" />
                    </div>
                </div>
            </div>

            <!-- ====== UBICACIÓN ====== -->
            <div class="rounded-2xl p-6 mb-6 border" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary); box-shadow: var(--shadow-md);">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: var(--color-orange-50);">
                        <i class="fa-solid fa-location-dot text-lg" style="color: var(--color-orange-600);"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-base" style="color: var(--color-text-primary);">Ubicación y Horario</h2>
                        <p class="text-xs" style="color: var(--color-text-tertiary);">Dirección, oficina y horario de atención</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <x-input label="Dirección fiscal" wire:model="direccion_fiscal" placeholder="Dirección completa de la institución" icon="map-pin" />
                    </div>
                    <div>
                        <x-input label="Oficina principal" wire:model="oficina_principal" placeholder="Nombre de la sede" icon="building-office-2" />
                    </div>
                    <div>
                        <x-input label="Horario de atención" wire:model="horario_atencion" placeholder="Lunes a Viernes 8:00 - 17:00" icon="clock" />
                    </div>
                    <div>
                        <x-input label="Dominio web" wire:model="domain" placeholder="institucion.gob.ve" icon="globe-alt" :disabled="true" />
                    </div>
                </div>
            </div>

            <!-- Tip -->
            <div class="rounded-xl p-4 mb-6 flex items-center gap-3" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-primary);">
                <span class="text-xl flex-shrink-0">💡</span>
                <p class="text-sm" style="color: var(--color-text-secondary);">
                    <strong>Tip:</strong> El nombre y la descripción se muestran en la barra de navegación, login y título de página. Al guardar se actualizan al instante.
                </p>
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-center">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-10 py-3 text-sm font-semibold text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50"
                        style="background-color: var(--color-blue-600);"
                        onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                    <span wire:loading.remove wire:target="saveSettings">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar configuración
                    </span>
                    <span wire:loading wire:target="saveSettings">
                        <i class="fa-solid fa-spinner fa-spin"></i> Guardando...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
