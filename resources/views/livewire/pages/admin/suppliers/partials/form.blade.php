<div class="space-y-4">
    <div class="grid grid-cols-2 gap-2">
        <div class="">
            <x-select label="Tipo de documento" placeholder="Seleccione un tipo de documento" wire:model="identity_id"
                required :options="$identity_type->map(function($identity) {
                return ['name' => $identity->name, 'id' => $identity->id];
            })->toArray()" option-label="name" option-value="id" />
        </div>
        <div class="">
            <x-input label="Documento" id="document_number" class="mt-1 block w-full" type="text"
                wire:model="document_number" required :value="old('document_number')" />
        </div>
    </div>
    <div>
        <x-input label="Nombre" id="name" class="mt-1 block w-full" type="text" wire:model="name" required
            :value="old('name')" />
    </div>
    <div>
        <x-input label="Teléfono" id="phone" class="mt-1 block w-full" type="text" wire:model="phone" required
            :value="old('phone')" />
    </div>
    <div>
        <x-input label="Email" id="email" class="mt-1 block w-full" type="email" wire:model="email" required
            :value="old('email')" />
    </div>
    <div>
        <x-input label="Dirección" id="address" class="mt-1 block w-full" type="text" wire:model="address" required
            :value="old('address')" />
    </div>
</div>