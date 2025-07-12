<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input label="Nombre" id="estado_nombre" class="mt-1 block w-full" type="text" wire:model="estado_nombre" required />
    </div>
    <div>
        <x-input label="ISO 3166-2" id="iso_3166_2" class="mt-1 block w-full" type="text" wire:model="iso_3166_2" required />
    </div>    
</div>
