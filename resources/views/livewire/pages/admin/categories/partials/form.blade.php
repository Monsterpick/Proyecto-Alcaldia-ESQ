<div class="space-y-4">
    <div>
        <x-input label="Nombre" id="name" class="mt-1 block w-full" type="text" wire:model="name" required :value="old('name')" />
    </div>
    <div>
        <x-textarea label="Descripción" id="description" class="mt-1 block w-full" wire:model="description" required :value="old('description')" />
    </div>    
</div>
