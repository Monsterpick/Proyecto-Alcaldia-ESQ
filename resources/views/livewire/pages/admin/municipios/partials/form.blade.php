<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-select label="Estado" id="estado_id" class="mt-1 block w-full" type="text" wire:model="estado_id" required :options="$estados->map(function($estado) {
                return ['name' => $estado->estado, 'id' => $estado->id];
            })->toArray()" option-label="name" option-value="id" />
    </div>
    <div>
        <x-input label="Municipio" id="nombre_municipio" class="mt-1 block w-full" type="text" wire:model="nombre_municipio" required />
    </div>    
</div>
