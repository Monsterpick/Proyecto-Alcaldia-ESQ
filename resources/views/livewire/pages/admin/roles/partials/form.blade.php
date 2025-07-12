<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input label="Nombre" id="name" class="mt-1 block w-full" type="text" wire:model="name" required />
    </div>
    <div>
        <x-input label="Guard Name" id="guard_name" class="mt-1 block w-full" type="text" wire:model="guard_name" required />
    </div>    
</div>

<h1 class="text-2xl font-bold mb-2 mt-4">
    Permisos
</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 py-4">
    Selecciona los permisos del rol.
</p>
<div class="border-t border-gray-200 dark:border-gray-600"></div>
<div class="">
    @foreach ($permissions as $permission)
        <div class="flex items-center mb-2">
            <x-checkbox label="{{ $permission->name }}" id="permission_{{ $permission->id }}" type="checkbox" value="{{ $permission->name }}" wire:model="selectedPermissions"
                class="mt-1 block w-full" />
            
        </div>
    @endforeach
</div>
