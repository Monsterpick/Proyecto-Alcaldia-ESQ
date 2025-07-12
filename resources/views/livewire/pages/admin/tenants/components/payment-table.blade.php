<?php

use Livewire\Volt\Component;
use App\Models\Tenant;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;
    
    public $payments = [];
    public $tenant;
    public array $image_path = [];

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
        $this->refreshPayments();
    }

    public function refreshPayments()
    {
        $this->payments = $this->tenant->payments()
            ->with(['paymentType', 'paymentOrigin'])
            ->latest()
            ->take(5)
            ->get();
    }

    #[On('payment-created')]
    #[On('payment-updated')]
    public function loadPayments()
    {
        $this->refreshPayments();
    }

    public function updatedImagePath($value, $key)
    {
        $payment_id = explode('.', $key)[0];
        
        if ($value) {
            $image_url = Storage::disk('public')->put('payments', $value);
            
            $payment = $this->tenant->payments()->find($payment_id);
            if ($payment) {
                $payment->update([
                    'image_path' => $image_url
                ]);

                $this->dispatch('payment-updated');
                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => 'Comprobante subido',
                    'text' => 'El comprobante se ha subido correctamente',
                ]);
                
                $this->refreshPayments();
                $this->reset('image_path');
            }
        }
    }

    public function downloadReceipt($paymentId, $type)
    {
        return redirect()->route('admin.payments.receipt.download', [
            'payment' => $paymentId,
            'type' => $type,
        ]);
    }
}; ?>

<div class="relative overflow-x-auto mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg border dark:border-gray-700">
    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Últimos Pagos Registrados de {{ $tenant->name }}
        </h2>
    </div>
    <table class="w-full text-sm text-left rtl:text-right">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-4 font-medium">Tipo de Pago</th>
                <th scope="col" class="px-6 py-4 font-medium">Origen</th>
                <th scope="col" class="px-6 py-4 font-medium text-right">Monto</th>
                <th scope="col" class="px-6 py-4 font-medium">Referencia</th>
                <th scope="col" class="px-6 py-4 font-medium">Fecha</th>
                <th scope="col" class="px-6 py-4 font-medium">Periodo</th>
                <th scope="col" class="px-6 py-4 font-medium">Comprobante</th>
                <th scope="col" class="px-6 py-4 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            {{ $payment->paymentType->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $payment->paymentOrigin->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-900 dark:text-white">
                        {{ $payment->formatted_amount }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            {{ $payment->reference_number ?: 'Sin referencia' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                        {{ $payment->concept }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div x-data="{ showFileInput: false }" class="space-y-2">
                            @if($payment->image_path)
                                <div class="flex items-start space-x-3">
                                    @php
                                        $extension = pathinfo($payment->image_path, PATHINFO_EXTENSION);
                                        $isPDF = strtolower($extension) === 'pdf';
                                    @endphp
                                    
                                    @if($isPDF)
                                        <div class="flex items-center justify-center w-10 h-10 bg-red-50 rounded-lg">
                                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="relative w-10 h-10 overflow-hidden rounded-lg">
                                            <img 
                                                src="{{ Storage::url($payment->image_path) }}" 
                                                alt="Comprobante"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                    @endif
                                    
                                    <div class="flex flex-col">
                                        <a 
                                            href="{{ Storage::url($payment->image_path) }}" 
                                            target="_blank"
                                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                                        >
                                            Ver comprobante
                                        </a>
                                        <button
                                            type="button"
                                            @click="showFileInput = true"
                                            class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                                        >
                                            Cambiar
                                        </button>
                                    </div>
                                </div>
                            @else
                                <button
                                    type="button"
                                    @click="showFileInput = true"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Subir comprobante
                                </button>
                            @endif

                            <div x-show="showFileInput" x-cloak class="relative">
                                <input 
                                    type="file" 
                                    wire:model="image_path.{{ $payment->id }}" 
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    accept="image/*,.pdf"
                                    @change="showFileInput = false"
                                />
                                <button 
                                    type="button" 
                                    @click="showFileInput = false"
                                    class="absolute top-0 right-0 p-1 text-gray-400 hover:text-gray-500"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            @error('image_path.' . $payment->id) 
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="relative" x-data="{ open: false }">
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <x-button icon="ellipsis-vertical" primary squared />
                                </x-slot>

                                <x-dropdown.item icon="printer" label="Recibo Térmico"
                                    wire:click="downloadReceipt({{ $payment->id }}, 'thermal')" />

                                <x-dropdown.item icon="document" label="Recibo Formal"
                                    wire:click="downloadReceipt({{ $payment->id }}, 'formal')" />
                            </x-dropdown>
                        </div>
                    </td>
                </tr>
            @endforeach

            @if(count($payments) === 0)
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <p class="mt-2 font-medium">No hay pagos registrados</p>
                            <p class="mt-1 text-sm">Los pagos que registres aparecerán aquí</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
