<?php

use Livewire\Volt\Component;
use App\Models\Tenant;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Actividad;
use App\Models\Estatus;
use App\Models\Plan;
use Illuminate\View\View;

new class extends Component {
    public $tenant;
    public $name;
    public $razon_social;
    public $rif;
    public $actividad_id;
    public $telefono_principal;
    public $telefono_secundario;
    public $email_principal;
    public $email_secundario;
    public $estado_id;
    public $municipio_id;
    public $parroquia_id;
    public $direccion_fiscal;
    public $domain;
    public $responsable;
    public $cargo_responsable;
    public $telefono_responsable;
    public $email_responsable;
    public $plan_id;
    public $estatus_id;

    // Colecciones para los selects
    public $actividades;
    public $estatuses;
    public $planes;
    public $estados;
    public $municipios;
    public $parroquias;

    public function rendering(View $view)
    {
        $view->title('Tenant');
    }

    public function mount(Tenant $tenant)
    {
        // Cargar las colecciones para los selects primero
        $this->actividades = collect(Actividad::all());
        $this->estatuses = collect(Estatus::all());
        $this->planes = collect(Plan::all());
        $this->estados = collect(Estado::all());
        $this->municipios = collect([]);
        $this->parroquias = collect([]);

        // Cargar datos del tenant
        $this->tenant = $tenant;
        $this->domain = $tenant->id;
        $this->name = $tenant->name;
        $this->razon_social = $tenant->razon_social;
        $this->rif = $tenant->rif;
        $this->actividad_id = $tenant->actividad_id;
        $this->telefono_principal = $tenant->telefono_principal;
        $this->telefono_secundario = $tenant->telefono_secundario;
        $this->email_principal = $tenant->email_principal;
        $this->email_secundario = $tenant->email_secundario;
        $this->responsable = $tenant->responsable;
        $this->cargo_responsable = $tenant->cargo_responsable;
        $this->telefono_responsable = $tenant->telefono_responsable;
        $this->email_responsable = $tenant->email_responsable;
        $this->plan_id = $tenant->plan_id;
        $this->estatus_id = $tenant->estatus_id;

        // Cargar estado y sus dependientes
        $this->estado_id = $tenant->estado_id;
        if ($this->estado_id) {
            $this->municipios = collect(Municipio::where('estado_id', $this->estado_id)->get());
            $this->municipio_id = $tenant->municipio_id;
        }

        // Cargar municipio y sus dependientes
        if ($this->municipio_id) {
            $this->parroquias = collect(Parroquia::where('municipio_id', $this->municipio_id)->get());
            $this->parroquia_id = $tenant->parroquia_id;
        }

        $this->direccion_fiscal = $tenant->direccion_fiscal;
    }

    public function updatedEstadoId($value)
    {
        $this->municipios = collect([]);
        $this->parroquias = collect([]);
        $this->municipio_id = '';
        $this->parroquia_id = '';

        if (!empty($value)) {
            $this->municipios = collect(Municipio::where('estado_id', $value)->get());
        }
    }

    public function updatedMunicipioId($value)
    {
        $this->parroquias = collect([]);
        $this->parroquia_id = '';

        if (!empty($value)) {
            $this->parroquias = collect(Parroquia::where('municipio_id', $value)->get());
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255',
            'rif' => 'required|string|max:15',
            'actividad_id' => 'required|exists:actividads,id',
            'telefono_principal' => 'required|string|max:18',
            'telefono_secundario' => 'nullable|string|max:18',
            'email_principal' => 'required|email|max:255',
            'email_secundario' => 'nullable|email|max:255',
            'estado_id' => 'required|exists:estados,id',
            'municipio_id' => 'required|exists:municipios,id',
            'parroquia_id' => 'required|exists:parroquias,id',
            'direccion_fiscal' => 'required|string|max:500',
            'domain' => 'required|string|max:255|unique:tenants,id,' . $this->tenant->id,
            'responsable' => 'required|string|max:255',
            'cargo_responsable' => 'required|string|max:255',
            'telefono_responsable' => 'required|string|max:18',
            'email_responsable' => 'required|email|max:255',
            'plan_id' => 'required|exists:plans,id',
            'estatus_id' => 'required|exists:estatuses,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'razon_social.required' => 'La razón social es requerida',
            'razon_social.max' => 'La razón social no puede tener más de 255 caracteres',
            'rif.required' => 'El RIF es requerido',
            'rif.regex' => 'El formato del RIF debe ser V123456789',
            'actividad_id.required' => 'La actividad es requerida',
            'actividad_id.exists' => 'La actividad seleccionada no es válida',
            'telefono_principal.required' => 'El teléfono principal es requerido',
            'telefono_principal.regex' => 'El formato del teléfono debe ser +584141234567',
            'telefono_secundario.regex' => 'El formato del teléfono debe ser +584141234567',
            'email_principal.required' => 'El email principal es requerido',
            'email_principal.email' => 'El email principal debe ser una dirección válida',
            'email_secundario.email' => 'El email secundario debe ser una dirección válida',
            'estado_id.required' => 'El estado es requerido',
            'estado_id.exists' => 'El estado seleccionado no es válido',
            'municipio_id.required' => 'El municipio es requerido',
            'municipio_id.exists' => 'El municipio seleccionado no es válido',
            'parroquia_id.required' => 'La parroquia es requerida',
            'parroquia_id.exists' => 'La parroquia seleccionada no es válida',
            'direccion_fiscal.required' => 'La dirección fiscal es requerida',
            'direccion_fiscal.max' => 'La dirección fiscal no puede tener más de 500 caracteres',
            'domain.required' => 'El dominio es requerido',
            'domain.max' => 'El dominio no puede tener más de 255 caracteres',
            'domain.unique' => 'El dominio ya está en uso',
            'responsable.required' => 'El responsable es requerido',
            'responsable.max' => 'El nombre del responsable no puede tener más de 255 caracteres',
            'cargo_responsable.required' => 'El cargo del responsable es requerido',
            'cargo_responsable.max' => 'El cargo del responsable no puede tener más de 255 caracteres',
            'telefono_responsable.required' => 'El teléfono del responsable es requerido',
            'telefono_responsable.regex' => 'El formato del teléfono debe ser +584141234567',
            'email_responsable.required' => 'El email del responsable es requerido',
            'email_responsable.email' => 'El email del responsable debe ser una dirección válida',
            'plan_id.required' => 'El plan es requerido',
            'plan_id.exists' => 'El plan seleccionado no es válido',
            'estatus_id.required' => 'El estatus es requerido',
            'estatus_id.exists' => 'El estatus seleccionado no es válido',
        ];
    }

    public function update()
    {
        $validatedData = $this->validate();

        try {
            // Actualizar el tenant
            $this->tenant->update([
                'id' => $validatedData['domain'],
                'name' => $validatedData['name'],
                'razon_social' => $validatedData['razon_social'],
                'rif' => $validatedData['rif'],
                'actividad_id' => $validatedData['actividad_id'],
                'telefono_principal' => $validatedData['telefono_principal'],
                'telefono_secundario' => $validatedData['telefono_secundario'],
                'email_principal' => $validatedData['email_principal'],
                'email_secundario' => $validatedData['email_secundario'],
                'estado_id' => $validatedData['estado_id'],
                'municipio_id' => $validatedData['municipio_id'],
                'parroquia_id' => $validatedData['parroquia_id'],
                'direccion_fiscal' => $validatedData['direccion_fiscal'],
                'responsable' => $validatedData['responsable'],
                'cargo_responsable' => $validatedData['cargo_responsable'],
                'telefono_responsable' => $validatedData['telefono_responsable'],
                'email_responsable' => $validatedData['email_responsable'],
                'plan_id' => $validatedData['plan_id'],
                'estatus_id' => $validatedData['estatus_id'],
            ]);

            // Actualizar el dominio si cambió
            /* if ($this->tenant->id !== $validatedData['domain']) {
                $this->tenant->domains()->update([
                    'domain' => $validatedData['domain'] . '.' . env('APP_CENTRAL_DOMAIN', 'nevora.app'),
                ]);
            } */

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Tenant actualizado',
                'text' => 'El tenant se ha actualizado correctamente',
            ]);

            $this->redirect(route('admin.tenants.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al actualizar el tenant: ' . $e->getMessage(),
            ]);

            $this->redirect(route('admin.tenants.index'), navigate: true);
        }
    }

    public function cancel()
    {
        $this->redirect(route('admin.tenants.index'), navigate: true);
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
                'name' => 'Tenants',
                'route' => route('admin.tenants.index'),
            ],
            [
                'name' => $this->tenant->domain,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <h1 class="text-2xl font-bold mb-2">
                Información del Tenant
            </h1>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            @include('livewire.pages.admin.tenants.partials.form', ['readonly' => true])
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atrás" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
