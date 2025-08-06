<div class="space-y-4">
    <div>
        <x-input label="Nombre" id="name" class="mt-1 block w-full" type="text" wire:model="name" required
            :value="old('name')" />
    </div>
    <div>
        <x-textarea label="Descripción" id="description" class="mt-1 block w-full" wire:model="description" required
            :value="old('description')" />
    </div>


    <div>
        <x-input label="Precio" id="price" class="mt-1 block w-full" wire:model="price" required
            :value="old('price')" placeholder="Precio del producto"  type="number" />
    </div>
    <div>
        <x-datetime-picker label="Fecha de expedición" id="expedition_date" class="mt-1 block w-full" wire:model="expedition_date"
            required :value="old('expedition_date')"  without-time />
    </div>
    <div>
        <x-datetime-picker label="Fecha de expiración" id="expiration_date" class="mt-1 block w-full" wire:model="expiration_date"
            required :value="old('expiration_date')"  without-time />
    </div>
    <div>
        <x-select 
            label="Categoría" 
            placeholder="Seleccione una categoría" 
            wire:model="category_id" 
            required 
            :options="$categories->map(function($category) {
                return ['name' => $category->name, 'id' => $category->id];
            })->toArray()" option-label="name" option-value="id" />
    </div>