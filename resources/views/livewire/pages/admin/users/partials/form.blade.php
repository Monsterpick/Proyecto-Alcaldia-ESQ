<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input label="Nombres" id="name" class="mt-1 block w-full" type="text" wire:model="name" required />
    </div>
    <div>
        <x-input label="Apellidos" id="last_name" class="mt-1 block w-full" type="text" wire:model="last_name"
            required />
    </div>
    <div>
        <x-input label="Documento" id="document" class="mt-1 block w-full" type="text" wire:model="document"
            required />
    </div>
    <div>
        <x-input label="Teléfono" id="phone" class="mt-1 block w-full" type="text" wire:model="phone"
            required />
    </div>
    <div>
        <x-input label="Email" id="email" class="mt-1 block w-full" type="email" wire:model="email"
            required />
    </div>

</div>

<h1 class="text-2xl font-bold mt-4">
    Imagen de Perfil
</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 py-4">
    Por favor, seleccione una imagen para el usuario.
</p>
<div class="border-t border-gray-200 dark:border-gray-600"></div>

<figure class="mb-4  mt-4 relative text-black">
    <div class="absolute top-8 right-8">
        <label class="text-black flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
            <i class="fas fa-camera mr-2"></i> Actualizar Imagen
            <input type="file" wire:model="image" class="hidden" accept="image/*">
        </label>
    </div>
    <img src="{{ $image 
        ? $image->temporaryUrl() 
        : ($user && $user->image_url 
            ? Storage::url($user->image_url) 
            : asset('images/user_no_image.png')) }}" 
        alt="Imagen de usuario"
        class="h-64 w-64 object-cover object-center rounded-lg">
</figure>
<x-validation-errors />


@if ($showPassword ?? true)
    @if ($showForm ?? true)
        <h1 class="text-2xl font-bold mt-4">
            Contraseña
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
            Por favor, ingrese una contraseña para el usuario.
        </p>
        <div class="border-t border-gray-200 dark:border-gray-600"></div>
        <div>
            <x-password label="Contraseña" id="password" class="mt-1 block w-full" type="password"
                wire:model="password"
                description="{{ $editForm ? 'Deje en blanco para no actualizar la contraseña.' : '' }}" />
        </div>
        <div>
            <x-password label="Confirmar Contraseña" id="password_confirmation" class="mt-1 block w-full"
                type="password" wire:model="password_confirmation"
                description="{{ $editForm ? 'Deje en blanco para no actualizar la contraseña.' : '' }}" />
        </div>
    @endif
@endif
<h1 class="text-2xl font-bold mt-4">
    Roles
</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 py-4">
    Selecciona los roles del usuario.
</p>
<div class="border-t border-gray-200 dark:border-gray-600"></div>
<div class="">
    @foreach ($roles as $role)
        <div class="flex items-center mb-2">
            <x-checkbox label="{{ $role->name }}" id="role_{{ $role->id }}" type="checkbox"
                value="{{ $role->name }}" wire:model="selectedRoles" class="mt-1 block w-full" />
        </div>
    @endforeach
</div>
