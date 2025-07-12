<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input label="Nombre" id="name" class="mt-1 block w-full" type="text" wire:model="name" required />
    </div>
    <div>
        <x-input label="Descripción" id="description" class="mt-1 block w-full" type="text" wire:model="description" required />
    </div>
    <div>
        <x-input label="Precio" id="price" class="mt-1 block w-full" type="text" wire:model="price" required />
    </div>
    <div>
        <x-input label="Días de prueba" id="trial_period_days" class="mt-1 block w-full" type="number" wire:model="trial_period_days" required />
    </div>
    <div>
        <x-checkbox label="Activo" id="active" class="mt-1 block w-full" type="checkbox" wire:model="active" required />
    </div>
</div>
