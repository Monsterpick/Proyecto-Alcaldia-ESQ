<div class="space-y-4">
    <div>
        <x-input label="Nombre" id="name" class="mt-1 block w-full" type="text" wire:model="name" required
            :value="old('name')" />
    </div>
    <div>
        <x-input label="Ubicación" id="location" class="mt-1 block w-full" type="text" wire:model="location" required
            :value="old('location')" />
    </div>
</div>