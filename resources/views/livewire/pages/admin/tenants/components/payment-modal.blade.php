<?php

use Livewire\Volt\Component;
use App\Models\Tenant;
use App\Models\TenantPayment;
use App\Models\PaymentType;
use App\Models\PaymentOrigin;
use Livewire\Attributes\On;
use App\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $tenant_id = null;
    public $showModal = false;
    public $tenant = null;
    public $payments = [];
    public $image_path = null;

    // Estados del formulario
    public $payment = [
        'tenant_id' => '',
        'payment_type_id' => '',
        'payment_origin_id' => '',
        'amount' => '',
        'reference_number' => '',
        'payment_date' => '',
        'period_start' => '',
        'period_end' => '',
        'notes' => '',
        'currency' => '',
    ];

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
        $this->resetPaymentForm();
        $this->loadPayments();
    }

    public function resetPaymentForm()
    {
        $this->payment = [
            'tenant_id' => $this->tenant->id,
            'payment_type_id' => '',
            'payment_origin_id' => '',
            'amount' => '',
            'reference_number' => '',
            'payment_date' => now()->format('Y-m-d'),
            'period_start' => now()->startOfMonth()->format('Y-m-d'),
            'period_end' => now()->endOfMonth()->format('Y-m-d'),
            'notes' => '',
            'currency' => Setting::get('currency_code'),
        ];
    }

    public function loadPayments()
    {
        $this->payments = $this->tenant->payments()->latest()->take(5)->get();
    }

    public function save()
    {
        $this->validate([
            'payment.payment_type_id' => 'required',
            'payment.payment_origin_id' => 'required',
            'payment.amount' => 'required|numeric|min:0',
            'payment.reference_number' => '',
            'payment.payment_date' => 'required|date',
            'payment.period_start' => 'required|date',
            'payment.period_end' => 'required|date|after_or_equal:payment.period_start',
        ]);

        $payment = $this->tenant->payments()->create([...$this->payment, 'status' => 'completed']);

        // Emitir el evento para actualizar la tabla
        $this->dispatch('payment-created');

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Pago registrado',
            'text' => 'El pago se ha registrado correctamente',
        ]);

        // Resetear formulario
        $this->resetPaymentForm();
    }

    public function uploadImage()
    {
        if ($this->image_path) {
            $image_url = Storage::disk('public')->put('payments', $this->image_path);
            $this->payment['image_path'] = $image_url;
        }

        $payment = $this->tenant
            ->payments()
            ->where('id', $this->payment['id'])
            ->update([
                'image_path' => $this->payment['image_path'],
            ]);

        $this->dispatch('payment-updated', $payment->id);
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Comprobante subido',
            'text' => 'El comprobante se ha subido correctamente',
        ]);
    }

    public function getPaymentTypesProperty()
    {
        return PaymentType::all()
            ->map(function ($type) {
                return [
                    'name' => $type->name,
                    'id' => $type->id,
                ];
            })
            ->toArray();
    }

    public function getPaymentOriginsProperty()
    {
        return PaymentOrigin::all()
            ->map(function ($origin) {
                return [
                    'name' => $origin->name,
                    'id' => $origin->id,
                ];
            })
            ->toArray();
    }

    #[On('open-payment-modal')]
    public function onOpenModal()
    {
        $this->showModal = true;
    }

    #[On('set-tenant-id')]
    public function setTenantId(Tenant $tenant)
    {
        $this->tenant = $tenant;
        $this->resetPaymentForm();
        $this->loadPayments();
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function downloadReceipt($paymentId, $type = 'thermal')
    {
        return Redirect::route('payments.receipt.download', [
            'payment' => $paymentId,
            'type' => $type,
        ]);
    }
}; ?>

<div>


    <x-modal wire:model.defer="showModal" name="payment-modal" blur="md" width="screen" align="center">
        <div class="relative w-full bg-white rounded-lg shadow dark:bg-gray-800">
            <!-- Header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Registro de Pagos de: {{ $tenant->name }}
                </h3>

                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    x-on:click="close">
                    <i class="fa-solid fa-xmark"></i>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6">
                @if ($tenant)
                    <!-- Conversor de Moneda -->
                    <div id="currencyConverter" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <div id="loadingMessage" class="text-center text-gray-600 dark:text-gray-400"
                            style="display: none;">
                            Cargando tasa de cambio...
                        </div>
                        <div id="errorMessage" class="text-center text-red-600 dark:text-red-400"
                            style="display: none;">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input label="Monto en USD" placeholder="0.00" id="montoUSD" step="0.01" min="0" />
                            </div>
                            <div>
                                <x-input label="Monto en Bs" placeholder="0.00" id="montoBs" step="0.01" min="0" />
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de pagos -->
                    <div class="border-t border-gray-200 dark:border-gray-600"></div>
                    <form wire:submit="save"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <div class="col-span-1">
                            <x-select label="Tipo de Pago" placeholder="Seleccione el tipo" :options="$this->paymentTypes"
                                option-label="name" option-value="id" wire:model="payment.payment_type_id" />
                        </div>

                        <div class="col-span-1">
                            <x-select label="Origen del Pago" placeholder="Seleccione el origen" :options="$this->paymentOrigins"
                                option-label="name" option-value="id" wire:model="payment.payment_origin_id" />
                        </div>

                        <div class="col-span-1">
                            <x-currency label="Monto" placeholder="0.00" wire:model="payment.amount"
                                prefix="{{ Setting::get('currency_symbol') }}"
                                thousands="{{ Setting::get('thousand_separator') }}"
                                decimal="{{ Setting::get('decimal_separator') }}" />
                        </div>

                        <div class="col-span-1">
                            <x-input label="Número de Referencia" placeholder="Ingrese la referencia"
                                wire:model="payment.reference_number" />
                        </div>

                        <div class="col-span-1">
                            <x-datetime-picker label="Fecha de Pago" placeholder="Seleccione la fecha"
                                wire:model="payment.payment_date" without-time />
                        </div>

                        <div class="col-span-1">
                            <x-datetime-picker label="Inicio del Periodo" placeholder="Fecha inicial"
                                wire:model="payment.period_start" without-time />
                        </div>

                        <div class="col-span-1">
                            <x-datetime-picker label="Fin del Periodo" placeholder="Fecha final"
                                wire:model="payment.period_end" without-time />
                        </div>

                        <div class="col-span-full">
                            <x-textarea label="Notas" placeholder="Notas adicionales" wire:model="payment.notes"
                                rows="2" />
                        </div>

                        <!-- Footer -->
                        <div class="col-span-full flex justify-end space-x-2 ">
                            <x-button wire:click="save" spinner="save" label="Guardar Pago" icon="check"
                                interaction="positive" />
                            <x-button slate label="Cancelar" icon="x-mark" interaction="secondary"
                                wire:click="close" />
                        </div>
                    </form>

                    <!-- Tabla de pagos existentes -->
                    <div>
                        <livewire:pages.admin.tenants.components.payment-table :tenant="$tenant"
                            wire:key="payment-table-{{ $tenant->id }}" />
                    </div>
                @else
                    <p class="text-gray-600">No se ha seleccionado ningún tenant.</p>
                @endif
            </div>
        </div>
    </x-modal>
</div>

@push('scripts')
    <script>
        class CurrencyConverter {
            constructor() {
                this.tasaCambio = 0;
                this.montoUSD = document.getElementById('montoUSD');
                this.montoBs = document.getElementById('montoBs');
                this.loadingMessage = document.getElementById('loadingMessage');
                this.errorMessage = document.getElementById('errorMessage');

                if (this.montoUSD && this.montoBs) {
                    this.init();
                    this.setupEventListeners();
                }
            }

            async obtenerTasaCambio() {
                try {
                    const response = await fetch('https://pydolarve.org/api/v1/dollar?page=bcv');
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    const data = await response.json();
                    if (data && data.monitors && data.monitors.usd && data.monitors.usd.price) {
                        return parseFloat(data.monitors.usd.price);
                    } else {
                        throw new Error('No se encontró el precio del dólar en la respuesta.');
                    }
                } catch (error) {
                    console.error('Error obteniendo la tasa de cambio:', error.message);
                    return 0;
                }
            }

            async init() {
                this.showLoading(true);
                this.tasaCambio = await this.obtenerTasaCambio();
                this.showLoading(false);

                if (this.tasaCambio === 0) {
                    this.showError('No se pudo obtener la tasa de cambio.');
                }
            }

            setupEventListeners() {
                this.montoUSD.addEventListener('input', () => this.convertirABs());
                this.montoBs.addEventListener('input', () => this.convertirAUsd());
            }

            showLoading(show) {
                this.loadingMessage.style.display = show ? 'block' : 'none';
            }

            showError(message) {
                this.errorMessage.textContent = message;
                this.errorMessage.style.display = message ? 'block' : 'none';
            }

            convertirABs() {
                if (!this.montoUSD.value || this.tasaCambio === 0) return;
                this.montoBs.value = (parseFloat(this.montoUSD.value) * this.tasaCambio).toFixed(2);
            }

            convertirAUsd() {
                if (!this.montoBs.value || this.tasaCambio === 0) return;
                this.montoUSD.value = (parseFloat(this.montoBs.value) / this.tasaCambio).toFixed(2);
            }
        }

        // Inicializar el convertidor solo si existe el contenedor
        document.addEventListener('livewire:navigated', () => {
            if (document.getElementById('currencyConverter')) {
                new CurrencyConverter();
            }
        });
    </script>
@endpush
