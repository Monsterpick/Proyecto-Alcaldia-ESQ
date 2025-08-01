<?php

use Livewire\Volt\Component;
use Illuminate\View\View;
use App\Models\Setting;

new class extends Component {

    public $name;
    public $razon_social;
    public $rif;
    public $direccion_fiscal;
    public $oficina_principal;
    public $horario_atencion;
    public $telefono_principal;
    public $telefono_secundario;
    public $email_principal;
    public $email_secundario;
    public $domain;
    public $actividad;
    public $description;
    public $long_description;

    protected $rules = [
        'name' => 'required|string|max:255',
        'razon_social' => 'required|string|max:255',
        'rif' => 'required|string|max:20',
        'direccion_fiscal' => 'required|string|max:500',
        'oficina_principal' => 'required|string|max:255',
        'horario_atencion' => 'required|string|max:255',
        'telefono_principal' => 'required|string|max:20',
        'telefono_secundario' => 'nullable|string|max:20',
        'email_principal' => 'required|email|max:255',
        'email_secundario' => 'nullable|email|max:255',
        'domain' => 'required|string|max:255',
        'actividad' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'long_description' => 'required|string|max:1000'
    ];

    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'email' => 'El campo :attribute debe ser una dirección de correo válida.',
        'max' => 'El campo :attribute no debe ser mayor a :max caracteres.'
    ];

    public function rendering(View $view)
    {
        $view->title('Datos de la empresa');
    }

    public function mount()
    {
        $this->name = Setting::get('name', 'Empresa');
        $this->razon_social = Setting::get('razon_social', 'Razón social de la empresa');
        $this->rif = Setting::get('rif', 'RIF de la empresa');
        $this->direccion_fiscal = Setting::get('direccion_fiscal', 'Dirección fiscal de la empresa');
        $this->oficina_principal = Setting::get('oficina_principal', 'Oficina principal de la empresa');
        $this->horario_atencion = Setting::get('horario_atencion', 'Horario de atención de la empresa');
        $this->telefono_principal = Setting::get('telefono_principal', 'Teléfono principal de la empresa');
        $this->telefono_secundario = Setting::get('telefono_secundario', 'Teléfono secundario de la empresa');
        $this->email_principal = Setting::get('email_principal', 'Correo electrónico principal de la empresa');
        $this->email_secundario = Setting::get('email_secundario', 'Correo electrónico secundario de la empresa');
        $this->domain = Setting::get('domain', 'Dominio de la empresa');
        $this->actividad = Setting::get('actividad', 'Actividad de la empresa');
        $this->description = Setting::get('description', 'Descripción de la empresa');
        $this->long_description = Setting::get('long_description', 'Descripción larga de la empresa');
        $this->servicios = Setting::get('servicios', 'Servicios de la empresa');
    }

    public function saveSettings() {
        try {
            $this->validate();

            $settings = [
                'name' => $this->name,
                'razon_social' => $this->razon_social,
                'rif' => $this->rif,
                'direccion_fiscal' => $this->direccion_fiscal,
                'oficina_principal' => $this->oficina_principal,
                'horario_atencion' => $this->horario_atencion,
                'telefono_principal' => $this->telefono_principal,
                'telefono_secundario' => $this->telefono_secundario,
                'email_principal' => $this->email_principal,
                'email_secundario' => $this->email_secundario,
                'domain' => $this->domain,
                'actividad' => $this->actividad,
                'description' => $this->description,
                'long_description' => $this->long_description
            ];

            foreach ($settings as $key => $value) {
                Setting::set($key, $value);
            }

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Las configuraciones generales se han guardado correctamente',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error de validación',
                'text' => 'Por favor, verifica los campos del formulario',
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudieron guardar las configuraciones: ' . $e->getMessage(),
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
            'name' => 'Datos de la empresa',
        ],
    ]" />
    </x-slot>

    <x-container class="w-full px-4">
        <x-card title="Datos de la empresa">
            <form wire:submit.prevent="saveSettings">
                <div class="space-y-6">
                    <div>
                        <x-input label="Nombre de la empresa" wire:model.live="name" />
                    </div>
                    <div>
                        <x-input label="Razón social" wire:model.live="razon_social" />
                    </div>
                    <div>
                        <x-input label="RIF" wire:model.live="rif" />
                    </div>
                    <div>
                        <x-input label="Dirección fiscal" wire:model.live="direccion_fiscal" />
                    </div>
                    <div>
                        <x-input label="Descripción" wire:model.live="description" />
                    </div>
                    <div>
                        <x-input label="Descripción larga" wire:model.live="long_description" />
                    </div>
                    <div>
                        <x-input label="Oficina principal" wire:model.live="oficina_principal" />
                    </div>
                    <div>
                        <x-input label="Horario de atención" wire:model.live="horario_atencion" />
                    </div>
                    <div>
                        <x-input label="Teléfono principal" wire:model.live="telefono_principal" />
                    </div>
                    <div>
                        <x-input label="Teléfono secundario" wire:model.live="telefono_secundario" />
                    </div>
                    <div>
                        <x-input label="Correo electrónico principal" wire:model.live="email_principal" />
                    </div>
                    <div>
                        <x-input label="Correo electrónico secundario" wire:model.live="email_secundario" />
                    </div>
                    <div>
                        <x-input label="Actividad" wire:model.live="actividad" />
                    </div>
                    <div>
                        <x-input label="Dominio" wire:model.live="domain" :disabled="true" />
                    </div>
                </div>
                <x-slot name="footer">
                    <div class="flex justify-end">
                        <x-button info wire:click="saveSettings" icon="check" spinner="saveSettings" label="Guardar configuración" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>