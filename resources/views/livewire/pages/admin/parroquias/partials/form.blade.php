<div class="grid grid-cols-1 gap-4 mt-4">
    <div>
        <x-select 
            label="Estado" 
            id="estado_id" 
            class="mt-1 block w-full" 
            wire:model.live="estado_id" 
            required 
            option-value="id"
            option-label="estado"
            :options="$estados" 
            :disabled="$readonly ?? false"
            placeholder="Seleccione un estado"
        />
    </div>

    <div>
        <x-select 
            label="Municipio" 
            id="municipio_id" 
            class="mt-1 block w-full" 
            wire:model="municipio_id" 
            required 
            option-value="id"
            option-label="municipio"
            :options="$municipios" 
            :disabled="$readonly ?? false || empty($estado_id)"
            :placeholder="empty($estado_id) ? 'Primero seleccione un estado' : 'Seleccione un municipio'"
        />
    </div>

    <div>
        <x-input 
            label="Parroquia" 
            id="nombre_parroquia" 
            class="mt-1 block w-full" 
            type="text" 
            wire:model="nombre_parroquia" 
            required 
            :disabled="$readonly ?? false || empty($municipio_id)"
            placeholder="Ingrese el nombre de la parroquia"
        />
    </div>
</div>
