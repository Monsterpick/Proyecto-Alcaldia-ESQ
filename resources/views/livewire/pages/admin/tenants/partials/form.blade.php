@php
    $readonly = $readonly ?? false;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div class="mb-4">
        <x-input label="Nombre" id="name" type="text" wire:model="name" class="mt-1 block w-full" :disabled="$readonly" required />
    </div>

    <div class="mb-4">
        <x-input label="Razón Social" id="razon_social" type="text" wire:model="razon_social"
            class="mt-1 block w-full" :disabled="$readonly" required />
    </div>

    <div class="mb-4">
        <x-maskable label="RIF" id="rif" type="text" wire:model.live="rif" class="mt-1 block w-full" :disabled="$readonly" required
            placeholder="V123456789" maxlength="12" description="Formato: V123456789" mask="A################" />
    </div>

    <div class="mb-4">
        <x-select label="Actividad" placeholder="Seleccione una actividad" wire:model="actividad_id" required
            :options="$actividades->map(function($actividad) {
                return ['name' => $actividad->name, 'id' => $actividad->id];
            })->toArray()" option-label="name" option-value="id" :disabled="$readonly" class="mt-1 block w-full" />
    </div>

    <div class="mb-4">
        <x-maskable label="Teléfono Principal" id="telefono_principal" type="text"
            wire:model.defer="telefono_principal" class="mt-1 block w-full" required placeholder="+584141234567"
            maxlength="15" description="Formato: +584141234567" mask="+################" :disabled="$readonly" />
    </div>

    <div class="mb-4">
        <x-maskable label="Teléfono Secundario" id="telefono_secundario" type="text"
            wire:model="telefono_secundario" class="mt-1 block w-full" placeholder="+584141234567" maxlength="15"
            description="Formato: +584141234567" mask="+################" :disabled="$readonly" />
    </div>

    <div class="mb-4">
        <x-input label="Email Principal" id="email_principal" type="email" wire:model="email_principal"
            class="mt-1 block w-full" required :disabled="$readonly" />
    </div>

    <div class="mb-4">
        <x-input label="Email Secundario" id="email_secundario" type="email" wire:model="email_secundario"
            class="mt-1 block w-full" :disabled="$readonly" />
    </div>
</div>

<h1 class="text-2xl font-bold mb-2">
    Dirección del Tenant
</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
    Registre la dirección del tenant.
</p>
<div class="border-t border-gray-200 dark:border-gray-600"></div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div class="mb-4">
        <x-select label="Estado" placeholder="Seleccione un estado" wire:model.live="estado_id" required
            :options="$estados->map(function($estado) {
                return ['name' => $estado->estado, 'id' => $estado->id];
            })->toArray()" option-label="name" option-value="id" :disabled="$readonly" />
    </div>

    <div class="mb-4">
        <x-select label="Municipio" placeholder="Seleccione un municipio" wire:model.live="municipio_id" required
            :disabled="empty($estado_id) || $readonly" :options="$municipios->map(function ($municipio) {
        return ['name' => $municipio->municipio, 'id' => $municipio->id];
    })->toArray()" option-label="name"
            option-value="id" />
    </div>

    <div class="mb-4">
        <x-select label="Parroquia" placeholder="Seleccione una parroquia" wire:model="parroquia_id" required
            :disabled="empty($municipio_id) || $readonly" :options="$parroquias->map(function ($parroquia) {
        return ['name' => $parroquia->parroquia, 'id' => $parroquia->id];
    })->toArray()" option-label="name"
            option-value="id" />
    </div>

    <div class="mb-4">
        <x-input label="Dirección Fiscal" id="direccion_fiscal" class="card-input" type="text" required
            wire:model="direccion_fiscal" class="mt-1 block w-full" :disabled="$readonly" />
    </div>
</div>

<h1 class="text-2xl font-bold mb-2">
    Dominio del Tenant
</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
    Registre el dominio del tenant. Este debe ser único y no debe contener espacios.
</p>
<div class="border-t border-gray-200 dark:border-gray-600"></div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div class="mb-4">
        <x-input label="Dominio" id="domain" type="text" wire:model="domain" required
            class="no-uppercase mt-1 block w-full" :disabled="$readonly" />
    </div>
</div>

<h1 class="text-2xl font-bold mb-2">
    Responsable del Tenant
</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
    Registre la información del responsable del tenant.
</p>
<div class="border-t border-gray-200 dark:border-gray-600"></div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div class="mb-4">
        <x-input label="Responsable" id="responsable" class="mt-1 block w-full" type="text"
            wire:model="responsable" required :disabled="$readonly" />
    </div>
    <div class="mb-4">
        <x-input label="Cargo Responsable" id="cargo_responsable" class="mt-1 block w-full" type="text" required
            wire:model="cargo_responsable" :disabled="$readonly" />
    </div>
    <div class="mb-4">
        <x-maskable label="Teléfono Responsable" id="telefono_responsable" class="mt-1 block w-full" type="text"
            wire:model="telefono_responsable" required placeholder="+584141234567" maxlength="15"
            description="Formato: +584141234567" mask="+################" :disabled="$readonly" />
    </div>
    <div class="mb-4">
        <x-input label="Email Responsable" id="email_responsable" class="mt-1 block w-full" type="email" required
            wire:model="email_responsable" :disabled="$readonly" />
    </div>
</div>

<h1 class="text-2xl font-bold mb-2">
    Plan del Tenant
</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
    Registre el plan del tenant.
</p>
<div class="border-t border-gray-200 dark:border-gray-600"></div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div class="mb-4">
        <x-select label="Plan" placeholder="Seleccione un plan" wire:model="plan_id" required :options="$planes->map(function($plan) {
                return ['name' => $plan->name, 'id' => $plan->id];
            })->toArray()" option-label="name" option-value="id" :disabled="$readonly" />
    </div>
    <div class="mb-4">
        <x-select label="Estatus" placeholder="Seleccione un estatus" wire:model="estatus_id" required :options="$estatuses->map(function($estatus) {
                return ['name' => $estatus->name, 'id' => $estatus->id];
            })->toArray()" option-label="name" option-value="id" :disabled="$readonly" />
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigated', () => {

            handleUppercase(['#email_principal', '#email_secundario', '#domain', '#email_responsable']);

        });
    </script>
@endpush