<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <input type="hidden" wire:model="tenant_id" />

        <x-select label="Tipo de Pago" id="payment_type_id" class="mt-1 block w-full" type="text" wire:model="payment_type_id" required :options="$paymentTypes->map(function($paymentType) {
                return ['name' => $paymentType->name, 'id' => $paymentType->id];
            })->toArray()" option-label="name" option-value="id" />

        <x-select label="Origen del Pago" id="payment_origin_id" class="mt-1 block w-full" type="text" wire:model="payment_origin_id" required :options="$paymentOrigins->map(function($paymentOrigin) {
                return ['name' => $paymentOrigin->name, 'id' => $paymentOrigin->id];    
            })->toArray()" option-label="name" option-value="id" />

        <x-input label="Monto" id="amount" class="mt-1 block w-full" type="number" wire:model="amount" required />

        <x-input label="Referencia" id="reference" class="mt-1 block w-full" type="text" wire:model="reference" required />

        <x-input label="Fecha de Pago" id="payment_date" class="mt-1 block w-full" type="date" wire:model="payment_date" required />

        <x-input label="Moneda" id="currency" class="mt-1 block w-full" type="text" wire:model="currency" required />
    </div>
    <div>
        <x-input label="Descripción" id="description" class="mt-1 block w-full" type="text" wire:model="description" required />
    </div>    
</div>
