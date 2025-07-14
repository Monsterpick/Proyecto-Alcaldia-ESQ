<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input label="Nombre" id="appointment_status_name" class="mt-1 block w-full" type="text" wire:model="appointment_status_name" required />
    </div>
    <div>
        <x-input label="Descripción" id="appointment_status_description" class="mt-1 block w-full" type="text" wire:model="appointment_status_description" required />
    </div>    
</div>
