<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input label="Nombre del Tipo de Pago" id="name" class="mt-1 block w-full" type="text" wire:model="name" required />
    </div>
    <div>
        <x-input label="Descripción" id="description" class="mt-1 block w-full" type="text" wire:model="description" required />
    </div>
    <div>
        <x-checkbox md rounded="md" primary id="is_active" label="Activo" wire:model="is_active" value="is_active" />
    </div>
</div>
