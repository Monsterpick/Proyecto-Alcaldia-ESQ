<?php

use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Beneficiary;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\CircuitoComunal;
use App\Models\Parroquia;
use App\Models\Estado;
use App\Models\Municipio;
use App\Services\ReportPdfService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new #[Layout('livewire.layout.admin.admin'), Title('Crear Reporte de Entrega')] class extends Component {

    // Datos de la salida (vienen por parámetro o se seleccionan manualmente)
    public $inventory_id;
    
    // Ya no se usan directamente (ahora se usan items[])
    public $product_id = '';
    public $warehouse_id = '';
    public $quantity = 1;
    
    public $fromInventoryExit = false; // Indica si viene de una salida de inventario
    
    // Beneficiario seleccionado (opcional, para auto-llenar)
    public $beneficiary_id = '';
    public $searchBeneficiary = '';
    public $showBeneficiaryList = false;
    public $beneficiarySelected = false; // Indica si ya seleccionó un beneficiario

    // Datos obligatorios del beneficiario
    #[Validate('required|string|max:255')]
    public $beneficiary_first_name = '';
    
    #[Validate('required|string|max:255')]
    public $beneficiary_last_name = '';
    
    #[Validate('required|string|max:20')]
    public $beneficiary_cedula = '';
    
    #[Validate('required|string')]
    public $beneficiary_document_type = 'V';
    
    public $beneficiary_birth_date = '';
    
    public $beneficiary_phone = '';
    public $beneficiary_email = '';

    // Ubicación
    public $country = 'Venezuela';
    
    #[Validate('required')]
    public $estado_id = 1;
    
    #[Validate('required')]
    public $municipio_id = 1;
    
    #[Validate('required')]
    public $parroquia_id = '';
    
    public $sector = '';
    public $address = '';
    public $reference_point = '';
    public $latitude = '';
    public $longitude = '';

    // Circuito Comunal (OBLIGATORIO)
    #[Validate('required')]
    public $circuito_comunal_id = '';

    // Información del reporte
    #[Validate('required|string|min:10')]
    public $delivery_detail = '';
    
    #[Validate('required|date')]
    public $delivery_date = '';
    
    #[Validate('required|string|min:10')]
    public $notes = '';
    
    // Estado del reporte
    #[Validate('required|in:in_process,delivered,not_delivered')]
    public $status = 'in_process';
    
    // Múltiples entregas
    public $items = [];
    public $currentItemProduct = '';
    public $currentItemWarehouse = '';
    public $currentItemQuantity = 1;
    public $currentItemNotes = '';
    public $currentStockAvailable = null;
    
    // Sectores disponibles del circuito comunal
    public $sectoresDisponibles = [];

    public function mount()
    {
        $this->inventory_id = request()->get('inventory_id');
        $this->product_id = request()->get('product_id', '');
        $this->quantity = request()->get('quantity', 1);
        $this->delivery_date = now()->format('Y-m-d');
        
        // Detectar si viene de una salida de inventario
        if ($this->inventory_id) {
            $this->fromInventoryExit = true;
            $inventory = Inventory::find($this->inventory_id);
            if ($inventory) {
                $this->warehouse_id = $inventory->warehouse_id;
            }
        }
        
        // Establecer almacén por defecto si no se ha establecido
        if (!$this->warehouse_id) {
            $firstWarehouse = Warehouse::first();
            if ($firstWarehouse) {
                $this->warehouse_id = $firstWarehouse->id;
                $this->currentItemWarehouse = $firstWarehouse->id;
            }
        } else {
            $this->currentItemWarehouse = $this->warehouse_id;
        }
    }

    // Validaciones dinámicas para incluir fecha de nacimiento con mayor de 18 años
    public function rules()
    {
        $eighteenYearsAgo = now()->subYears(18)->format('Y-m-d');
        
        return [
            'beneficiary_first_name' => 'required|string|max:255',
            'beneficiary_last_name' => 'required|string|max:255',
            'beneficiary_cedula' => 'required|string|max:20',
            'beneficiary_document_type' => 'required|string',
            'beneficiary_birth_date' => 'nullable|date|before_or_equal:' . $eighteenYearsAgo,
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'parroquia_id' => 'required',
            'circuito_comunal_id' => 'required',
            'delivery_detail' => 'required|string|min:10',
            'delivery_date' => 'required|date',
            'notes' => 'required|string|min:10',
            'status' => 'required|in:in_process,delivered,not_delivered',
        ];
    }

    // Mensajes de validación personalizados
    public function messages()
    {
        return [
            'beneficiary_first_name.required' => 'El nombre del beneficiario es obligatorio',
            'beneficiary_last_name.required' => 'El apellido del beneficiario es obligatorio',
            'beneficiary_cedula.required' => 'La cédula es obligatoria',
            'beneficiary_birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'beneficiary_birth_date.before_or_equal' => '⚠️ El beneficiario debe ser mayor de 18 años',
            'parroquia_id.required' => 'Debe seleccionar una parroquia',
            'circuito_comunal_id.required' => 'Debe seleccionar un Circuito Comunal',
            'delivery_detail.required' => 'El detalle de la entrega es obligatorio',
            'delivery_detail.min' => 'El detalle debe tener al menos 10 caracteres',
            'delivery_date.required' => 'La fecha de entrega es obligatoria',
            'notes.required' => 'Las notas son obligatorias',
            'notes.min' => 'Las notas deben tener al menos 10 caracteres',
        ];
    }

    // Calcular edad del beneficiario
    public function getCalculatedAgeProperty()
    {
        if ($this->beneficiary_birth_date) {
            try {
                $birthDate = \Carbon\Carbon::parse($this->beneficiary_birth_date);
                return $birthDate->age;
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function updatedBeneficiaryId($value)
    {
        if ($value) {
            // Cargar beneficiario con sus relaciones
            $beneficiary = Beneficiary::with(['parroquia', 'circuitoComunal'])->find($value);
            
            if ($beneficiary) {
                // Auto-rellenar datos personales (concatenando segundo nombre y apellido)
                $nombres = trim(($beneficiary->first_name ?? '') . ' ' . ($beneficiary->second_name ?? ''));
                $apellidos = trim(($beneficiary->last_name ?? '') . ' ' . ($beneficiary->second_last_name ?? ''));
                
                $this->beneficiary_first_name = $nombres;
                $this->beneficiary_last_name = $apellidos;
                $this->beneficiary_cedula = $beneficiary->cedula ?? '';
                $this->beneficiary_document_type = $beneficiary->document_type ?? 'V';
                $this->beneficiary_birth_date = $beneficiary->birth_date ? $beneficiary->birth_date->format('Y-m-d') : '';
                $this->beneficiary_phone = $beneficiary->phone ?? '';
                $this->beneficiary_email = $beneficiary->email ?? '';
                
                // Auto-rellenar ubicación
                $this->country = 'Venezuela';
                $this->estado_id = 1; // Trujillo (fijo)
                $this->municipio_id = 1; // Escuque (fijo)
                
                // Auto-rellenar Parroquia y Circuito Comunal desde las relaciones
                if ($beneficiary->parroquia_id) {
                    $this->parroquia_id = $beneficiary->parroquia_id;
                }
                
                if ($beneficiary->circuito_comunal_id) {
                    $this->circuito_comunal_id = $beneficiary->circuito_comunal_id;
                }
                
                // Auto-rellenar dirección
                $this->sector = $beneficiary->sector ?? '';
                $this->address = $beneficiary->address ?? '';
                $this->reference_point = $beneficiary->reference_point ?? '';
                
                // Ocultar lista y marcar como seleccionado
                $this->showBeneficiaryList = false;
                $this->beneficiarySelected = true;
                
                // Limpiar búsqueda
                $this->searchBeneficiary = '';
            }
        }
    }
    
    public function updatedParroquiaId()
    {
        $this->circuito_comunal_id = '';
        $this->sector = '';
        $this->sectoresDisponibles = [];
    }
    
    public function updatedCircuitoComunalId($value)
    {
        if ($value) {
            $circuito = CircuitoComunal::find($value);
            if ($circuito && $circuito->descripcion) {
                // Extraer el sector de la descripción
                if (preg_match('/Sector:\s*([^|]+)/', $circuito->descripcion, $matches)) {
                    $this->sector = trim($matches[1]);
                }
            }
        }
    }
    
    public function updatedSearchBeneficiary()
    {
        if (strlen($this->searchBeneficiary) >= 3) {
            $this->showBeneficiaryList = true;
        } else {
            $this->showBeneficiaryList = false;
        }
    }

    public function searchBeneficiaries()
    {
        $this->showBeneficiaryList = true;
    }
    
    public function updatedCurrentItemProduct()
    {
        $this->updateStockAvailable();
    }
    
    public function updatedCurrentItemWarehouse()
    {
        $this->updateStockAvailable();
    }
    
    private function updateStockAvailable()
    {
        if ($this->currentItemProduct && $this->currentItemWarehouse) {
            $lastInventory = Inventory::where('product_id', $this->currentItemProduct)
                ->where('warehouse_id', $this->currentItemWarehouse)
                ->latest()
                ->first();
            
            $this->currentStockAvailable = $lastInventory ? $lastInventory->quantity_balance : 0;
        } else {
            $this->currentStockAvailable = null;
        }
    }
    
    public function addItem()
    {
        $this->validate([
            'currentItemProduct' => 'required',
            'currentItemWarehouse' => 'required',
            'currentItemQuantity' => 'required|integer|min:1',
        ], [
            'currentItemProduct.required' => 'Debe seleccionar un producto',
            'currentItemWarehouse.required' => 'Debe seleccionar un almacén',
            'currentItemQuantity.required' => 'Debe ingresar una cantidad',
            'currentItemQuantity.min' => 'La cantidad debe ser al menos 1',
        ]);
        
        $product = Product::with('category')->find($this->currentItemProduct);
        $warehouse = Warehouse::find($this->currentItemWarehouse);
        
        if (!$product || !$warehouse) {
            session()->flash('error', 'Producto o almacén no encontrado');
            return;
        }
        
        // Verificar stock
        $lastInventory = Inventory::where('product_id', $this->currentItemProduct)
            ->where('warehouse_id', $this->currentItemWarehouse)
            ->latest()
            ->first();
            
        $availableStock = $lastInventory ? $lastInventory->quantity_balance : 0;
        
        if ($availableStock < $this->currentItemQuantity) {
            session()->flash('error', '❌ Stock insuficiente. Solo hay ' . number_format($availableStock, 0) . ' unidades disponibles en el almacén.');
            $this->dispatch('show-error', message: '❌ Stock insuficiente. Solo hay ' . number_format($availableStock, 0) . ' unidades disponibles.');
            return;
        }
        
        $this->items[] = [
            'product_id' => $this->currentItemProduct,
            'product_name' => $product->name,
            'category_name' => $product->category->name,
            'category_icon' => $product->category->icon,
            'warehouse_id' => $this->currentItemWarehouse,
            'warehouse_name' => $warehouse->name,
            'quantity' => $this->currentItemQuantity,
            'notes' => $this->currentItemNotes,
            'available_stock' => $availableStock,
        ];
        
        // Limpiar campos (mantener warehouse fijo)
        $this->currentItemProduct = '';
        // NO limpiar currentItemWarehouse - se mantiene fijo
        $this->currentItemQuantity = 1;
        $this->currentItemNotes = '';
        
        session()->flash('item_added', 'Entrega agregada correctamente');
    }
    
    public function removeItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Reindexar
            session()->flash('item_removed', 'Entrega eliminada');
        }
    }

    public function save()
    {
        try {
            $this->validate();

            // Verificar que se haya seleccionado un circuito comunal
            if (!$this->circuito_comunal_id) {
                session()->flash('error', 'Debe seleccionar un Circuito Comunal');
                return;
            }
            
            // Verificar que haya al menos una entrega
            if (empty($this->items)) {
                session()->flash('error', 'Debe agregar al menos una entrega al reporte');
                return;
            }

            // Obtener datos del circuito comunal seleccionado
            $circuito = CircuitoComunal::with('parroquia.municipio.estado')->find($this->circuito_comunal_id);
            
            if (!$circuito) {
                session()->flash('error', 'Circuito Comunal no encontrado');
                return;
            }
            
            // Calcular cantidad total
            $totalQuantity = array_sum(array_column($this->items, 'quantity'));
            
            // Crear el reporte (sin inventory_id ni product_id específico ya que hay múltiples)
            $report = Report::create([
                'inventory_id' => null,
                'beneficiary_id' => $this->beneficiary_id ?: null,
                'product_id' => null,
                'created_by' => auth()->id(),
                'report_code' => Report::generateReportCode(),
                'quantity' => $totalQuantity,
                'delivery_detail' => $this->delivery_detail,
                'beneficiary_first_name' => $this->beneficiary_first_name,
                'beneficiary_last_name' => $this->beneficiary_last_name,
                'beneficiary_cedula' => $this->beneficiary_cedula,
                'beneficiary_document_type' => $this->beneficiary_document_type,
                'beneficiary_birth_date' => $this->beneficiary_birth_date ?: null,
                'beneficiary_phone' => $this->beneficiary_phone ?: '',
                'beneficiary_email' => $this->beneficiary_email ?: '',
                'country' => $this->country,
                'state' => $circuito->parroquia->municipio->estado->estado ?? 'Trujillo',
                'municipality' => $circuito->parroquia->municipio->municipio ?? 'Escuque',
                'parish' => $circuito->parroquia->parroquia ?? '',
                'sector' => $this->sector ?: '',
                'address' => $this->address ?: '',
                'reference_point' => $this->reference_point ?: '',
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'communal_circuit' => $circuito->codigo ?? '',
                'delivery_date' => $this->delivery_date,
                'notes' => $this->notes,
                'status' => $this->status,
            ]);

            // Crear los items del reporte
            foreach ($this->items as $item) {
                $inventoryId = null;
                
                // Solo crear salida de inventario si el estado es "delivered"
                if ($this->status === 'delivered') {
                    // Crear salida de inventario para este item
                    $lastInventory = Inventory::where('product_id', $item['product_id'])
                        ->where('warehouse_id', $item['warehouse_id'])
                        ->latest()
                        ->first();

                    $previousBalance = $lastInventory ? $lastInventory->quantity_balance : 0;
                    $newBalance = $previousBalance - $item['quantity'];

                    $inventory = Inventory::create([
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $item['warehouse_id'],
                        'detail' => 'Salida - Reporte ' . $report->report_code . ' (Entregado)',
                        'quantity_in' => 0,
                        'quantity_out' => $item['quantity'],
                        'quantity_balance' => $newBalance,
                        'cost_in' => 0,
                        'cost_out' => 0,
                        'total_in' => 0,
                        'total_out' => 0,
                        'cost_balance' => 0,
                        'total_balance' => 0,
                        'inventoryable_type' => 'App\Models\Product',
                        'inventoryable_id' => $item['product_id'],
                    ]);
                    
                    $inventoryId = $inventory->id;
                }

                // Crear el item del reporte (con o sin inventory_id)
                ReportItem::create([
                    'report_id' => $report->id,
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'inventory_id' => $inventoryId, // null si no está entregado
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?: '',
                ]);
            }

            // Guardar las categorías del reporte (extraídas de los productos)
            $categoryIds = [];
            foreach ($this->items as $item) {
                $product = Product::with('category')->find($item['product_id']);
                if ($product && $product->category_id) {
                    $categoryIds[] = $product->category_id;
                }
            }
            // Eliminar duplicados y sincronizar con la tabla pivot
            $categoryIds = array_unique($categoryIds);
            $report->categories()->sync($categoryIds);

            // Generar PDF del reporte (de forma asíncrona para no bloquear)
            try {
                dispatch(function () use ($report) {
                    $pdfService = app(\App\Services\ReportPdfService::class);
                    $pdfService->generatePdf($report->fresh());
                })->afterResponse();
            } catch (\Exception $e) {
                \Log::error('Error generando PDF: ' . $e->getMessage());
                // No interrumpir el flujo, el PDF se puede generar después
            }

            // Disparar evento SweetAlert
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Reporte Generado con Éxito!',
                'text' => 'Código: ' . $report->report_code . ' | ' . count($this->items) . ' entregas registradas',
                'timer' => 3000,
            ]);
            
            // Esperar un momento para que se muestre el SweetAlert antes de redirigir
            return $this->redirect(route('admin.reports.index'), navigate: true);
            
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo crear el reporte: ' . $e->getMessage(),
            ]);
            \Log::error('Error creating report: ' . $e->getMessage());
            return;
        }
    }

    public function with(): array
    {
        $beneficiariesQuery = Beneficiary::active();
        
        if ($this->searchBeneficiary) {
            $beneficiariesQuery->search($this->searchBeneficiary);
        }

        // Obtener circuitos de la parroquia seleccionada
        $circuitos = collect();
        if ($this->parroquia_id) {
            $circuitos = CircuitoComunal::where('parroquia_id', $this->parroquia_id)
                ->where('is_active', true)
                ->orderBy('codigo')
                ->get();
        }

        return [
            'product' => $this->product_id ? Product::with('category')->find($this->product_id) : null,
            'inventory' => Inventory::find($this->inventory_id),
            'beneficiaries' => $this->showBeneficiaryList ? $beneficiariesQuery->take(10)->get() : collect(),
            'products' => Product::with('category')->where('is_active', true)->get(),
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'estados' => Estado::all(),
            'municipios' => Municipio::where('estado_id', $this->estado_id)->get(),
            'parroquias' => Parroquia::where('municipio_id', $this->municipio_id)->get(),
            'circuitos' => $circuitos,
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            <i class="fas fa-file-alt mr-2"></i>
            {{ __('Crear Reporte de Entrega') }}
        </h2>
    </x-slot>

    <x-container class="py-12">
        
        @if($fromInventoryExit && $product)
        <!-- Información de la Salida -->
        <div class="mb-6 rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4 dark:bg-blue-900/20">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-2xl text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100">
                        Información de la Salida de Inventario
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
                            <div>
                                <span class="font-semibold">Producto:</span> {{ $product->name }}
                            </div>
                            <div>
                                <span class="font-semibold">Categoría:</span> {{ $product->category->name }}
                            </div>
                            <div>
                                <span class="font-semibold">Cantidad:</span> {{ $quantity }} {{ $product->unit_type }}
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-sm font-medium text-blue-800 dark:text-blue-200">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Por favor, complete el reporte con los datos del beneficiario que recibió esta entrega.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Formulario del Reporte -->
        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
            <div class="p-6">
                <form wire:submit="save">
                    
                    <!-- MÚLTIPLES ENTREGAS -->
                    <div class="mb-8 rounded-xl border-4 border-purple-400 bg-gradient-to-br from-purple-50 to-indigo-50 p-6 shadow-lg dark:border-purple-600 dark:from-purple-900/20 dark:to-indigo-900/20">
                        <h3 class="mb-4 flex items-center text-xl font-bold text-purple-900 dark:text-purple-100">
                            <i class="fas fa-boxes mr-3 text-2xl"></i>
                            Entregas del Reporte (Múltiples Productos)
                        </h3>
                        <p class="mb-6 text-sm font-medium text-purple-800 dark:text-purple-200">
                            <i class="fas fa-plus-circle mr-1"></i>
                            Puede agregar <strong>múltiples productos</strong> a este reporte. Agregue cada entrega una por una.
                        </p>
                        
                        <!-- Formulario para agregar entregas -->
                        <div class="mb-6 rounded-lg border-2 border-purple-300 bg-white p-5 dark:border-purple-700 dark:bg-gray-800">
                            <h4 class="mb-4 font-bold text-purple-900 dark:text-purple-100">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Nueva Entrega
                            </h4>
                            
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <x-select
                                        label="Producto *"
                                        wire:model.live="currentItemProduct"
                                        placeholder="Seleccione producto"
                                        :options="$products"
                                        option-label="name"
                                        option-value="id"
                                    />
                                </div>
                                
                                <div>
                                    @php
                                        $currentWarehouse = $warehouses->firstWhere('id', $currentItemWarehouse);
                                    @endphp
                                    <div class="mb-1">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Almacén * <span class="text-xs text-gray-500">(Fijo)</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-lg border-2 border-blue-300 bg-blue-50 px-4 py-3 dark:border-blue-700 dark:bg-blue-900/30">
                                        <i class="fas fa-warehouse text-xl text-blue-600 dark:text-blue-400"></i>
                                        <div class="flex-1">
                                            <p class="font-bold text-blue-900 dark:text-blue-100">
                                                {{ $currentWarehouse->name ?? 'No seleccionado' }}
                                            </p>
                                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                                📍 Todas las entregas serán desde este almacén
                                            </p>
                                        </div>
                                        <i class="fas fa-lock text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Indicador de Stock Disponible (PROMINENTE) -->
                            @if($currentStockAvailable !== null)
                            <div class="my-4 rounded-xl border-3 {{ $currentStockAvailable > 0 ? 'border-green-400 bg-gradient-to-r from-green-50 to-emerald-50 dark:border-green-600 dark:from-green-900/30 dark:to-emerald-900/30' : 'border-red-400 bg-gradient-to-r from-red-50 to-orange-50 dark:border-red-600 dark:from-red-900/30 dark:to-orange-900/30' }} p-4 shadow-lg">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full {{ $currentStockAvailable > 0 ? 'bg-green-600 dark:bg-green-700' : 'bg-red-600 dark:bg-red-700' }} shadow-lg">
                                        <i class="fas fa-box-open text-3xl text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold {{ $currentStockAvailable > 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                            📦 STOCK DISPONIBLE EN ALMACÉN
                                        </p>
                                        <p class="text-3xl font-bold {{ $currentStockAvailable > 0 ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }}">
                                            {{ number_format($currentStockAvailable, 0) }} unidades
                                        </p>
                                        @if($currentStockAvailable == 0)
                                        <p class="mt-1 text-sm font-semibold text-red-700 dark:text-red-300">
                                            ⚠️ No hay stock disponible de este producto
                                        </p>
                                        @endif
                                    </div>
                                    @if($currentStockAvailable > 0)
                                    <div class="text-right">
                                        <span class="inline-flex items-center rounded-full bg-green-600 px-4 py-2 text-sm font-bold text-white shadow-md">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Disponible
                                        </span>
                                    </div>
                                    @else
                                    <div class="text-right">
                                        <span class="inline-flex items-center rounded-full bg-red-600 px-4 py-2 text-sm font-bold text-white shadow-md">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Sin Stock
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <div class="{{ ($currentItemQuantity > $currentStockAvailable && $currentStockAvailable !== null) ? 'rounded-lg border-2 border-red-500 p-3 bg-red-50 dark:bg-red-900/20' : '' }}">
                                        <x-input
                                            label="Cantidad *"
                                            type="number"
                                            min="1"
                                            max="{{ $currentStockAvailable ?? '' }}"
                                            wire:model.live="currentItemQuantity"
                                            hint="Cantidad a entregar (Máximo: {{ $currentStockAvailable ?? 0 }})"
                                        />
                                        @if($currentItemQuantity > $currentStockAvailable && $currentStockAvailable !== null)
                                        <div class="mt-2 flex items-center gap-2 rounded-md bg-red-100 p-3 dark:bg-red-900/40">
                                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                            <p class="text-sm font-bold text-red-700 dark:text-red-300">
                                                ⚠️ La cantidad excede el stock disponible ({{ $currentStockAvailable }} unidades)
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-end">
                                    <x-button
                                        wire:click="addItem"
                                        primary
                                        label="Agregar"
                                        class="w-full"
                                        type="button"
                                        :disabled="!$currentItemProduct || !$currentItemWarehouse || $currentItemQuantity < 1 || ($currentStockAvailable !== null && $currentItemQuantity > $currentStockAvailable)"
                                    />
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <x-input
                                    label="Notas de esta entrega (opcional)"
                                    wire:model="currentItemNotes"
                                    placeholder="Observaciones sobre esta entrega específica..."
                                />
                            </div>
                        </div>
                        
                        <!-- Lista de entregas agregadas -->
                        @if(count($items) > 0)
                        <div class="rounded-lg border-2 border-green-300 bg-green-50 p-4 dark:border-green-700 dark:bg-green-900/30">
                            <h4 class="mb-4 flex items-center justify-between font-bold text-green-900 dark:text-green-100">
                                <span>
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Entregas Agregadas
                                </span>
                                <span class="rounded-full bg-green-600 px-3 py-1 text-sm text-white">
                                    {{ count($items) }} {{ count($items) == 1 ? 'entrega' : 'entregas' }}
                                </span>
                            </h4>
                            
                            <div class="space-y-3">
                                @foreach($items as $index => $item)
                                <div class="flex items-center justify-between rounded-lg border border-green-200 bg-white p-4 dark:border-green-600 dark:bg-gray-800">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-4">
                                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-600 text-lg font-bold text-white">
                                                {{ $index + 1 }}
                                            </span>
                                            <div>
                                                <div class="flex items-center gap-3">
                                                    <p class="font-bold text-gray-900 dark:text-white">
                                                        {{ $item['product_name'] }}
                                                    </p>
                                                    <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                                        <i class="{{ $item['category_icon'] }} mr-1.5"></i>
                                                        {{ $item['category_name'] }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    <i class="fas fa-warehouse mr-1"></i>
                                                    {{ $item['warehouse_name'] }}
                                                    •
                                                    <i class="fas fa-box mr-1"></i>
                                                    Cantidad: <strong>{{ $item['quantity'] }}</strong>
                                                </p>
                                                @if($item['notes'])
                                                <p class="mt-1 text-xs italic text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-sticky-note mr-1"></i>
                                                    {{ $item['notes'] }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        wire:click="removeItem({{ $index }})"
                                        type="button"
                                        class="ml-4 rounded-lg bg-red-600 px-4 py-2 text-white transition-colors hover:bg-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="rounded-lg border-2 border-dashed border-yellow-400 bg-yellow-50 p-6 text-center dark:border-yellow-600 dark:bg-yellow-900/20">
                            <i class="fas fa-inbox mb-3 text-4xl text-yellow-600 dark:text-yellow-400"></i>
                            <p class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">
                                No hay entregas agregadas
                            </p>
                            <p class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                Agregue al menos una entrega para continuar con el reporte
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Buscar Beneficiario Existente -->
                    <div class="mb-8 rounded-xl border-2 border-blue-300 bg-gradient-to-br from-blue-50 to-indigo-50 p-6 shadow-lg dark:border-blue-700 dark:from-blue-900/20 dark:to-indigo-900/20">
                        <h3 class="mb-4 flex items-center text-xl font-bold text-blue-900 dark:text-blue-100">
                            <i class="fas fa-search mr-3 text-2xl"></i>
                            ¿Buscar Beneficiario Existente? (Opcional)
                        </h3>
                        <p class="mb-6 text-sm font-medium text-blue-800 dark:text-blue-200">
                            <i class="fas fa-info-circle mr-1"></i>
                            Si el beneficiario ya está registrado, escriba su <strong>cédula, nombre o teléfono</strong> para auto-completar sus datos.
                        </p>
                        
                        <div class="relative">
                            <x-input
                                label="Buscar Beneficiario"
                                wire:model.live.debounce.300ms="searchBeneficiary"
                                placeholder="🔍 Cédula, nombre o teléfono..."
                                hint="Escriba al menos 3 caracteres para buscar"
                            />
                            
                            @if($showBeneficiaryList && $beneficiaries->count() > 0)
                            <div class="mt-2 max-h-60 overflow-y-auto rounded-lg border-2 border-blue-300 bg-white shadow-xl dark:border-blue-600 dark:bg-gray-700">
                                @foreach($beneficiaries as $ben)
                                <button
                                    type="button"
                                    wire:click="$set('beneficiary_id', {{ $ben->id }})"
                                    class="flex w-full items-center justify-between border-b border-gray-100 p-4 transition-colors hover:bg-blue-50 dark:border-gray-600 dark:hover:bg-blue-900/30">
                                    <div class="flex-1 text-left">
                                        <div class="font-bold text-gray-900 dark:text-white">
                                            {{ $ben->full_name }}
                                        </div>
                                        <div class="mt-1 flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-id-card mr-1"></i>
                                                {{ $ben->full_cedula }}
                                            </span>
                                            @if($ben->phone)
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $ben->phone }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-blue-600"></i>
                                </button>
                                @endforeach
                            </div>
                            @elseif($beneficiarySelected)
                            <div class="mt-2 rounded-lg border-2 border-dashed border-green-300 bg-green-50 p-4 text-center dark:border-green-600 dark:bg-green-900/30">
                                <i class="fas fa-check-circle mb-2 text-3xl text-green-600 dark:text-green-400"></i>
                                <p class="text-sm font-semibold text-green-700 dark:text-green-300">
                                    ✅ Beneficiario ya seleccionado
                                </p>
                            </div>
                            @elseif($searchBeneficiary && strlen($searchBeneficiary) >= 3 && $beneficiaries->count() === 0)
                            <div class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-4 text-center dark:border-gray-600 dark:bg-gray-800">
                                <i class="fas fa-user-slash mb-2 text-3xl text-gray-400"></i>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    No se encontraron beneficiarios
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Datos del Beneficiario -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-user mr-2"></i>
                            Datos del Beneficiario (OBLIGATORIO)
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <x-select
                                    label="Tipo de Documento *"
                                    wire:model="beneficiary_document_type"
                                    :options="[
                                        ['value' => 'V', 'label' => 'V - Venezolano'],
                                        ['value' => 'E', 'label' => 'E - Extranjero'],
                                        ['value' => 'J', 'label' => 'J - Jurídico'],
                                        ['value' => 'G', 'label' => 'G - Gubernamental'],
                                        ['value' => 'P', 'label' => 'P - Pasaporte'],
                                    ]"
                                    option-label="label"
                                    option-value="value"
                                />
                            </div>
                            
                            <div>
                                <x-input
                                    label="Cédula *"
                                    wire:model="beneficiary_cedula"
                                    placeholder="12345678"
                                    required
                                />
                            </div>
                            
                            <div>
                                <x-input
                                    label="Nombres *"
                                    wire:model="beneficiary_first_name"
                                    placeholder="Nombres del beneficiario"
                                    required
                                />
                            </div>
                            
                            <div>
                                <x-input
                                    label="Apellidos *"
                                    wire:model="beneficiary_last_name"
                                    placeholder="Apellidos del beneficiario"
                                    required
                                />
                            </div>
                            
                            <!-- Fecha de Nacimiento con validación de 18+ años -->
                            <div class="md:col-span-2 lg:col-span-3">
                                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-2 border-purple-300 dark:border-purple-600 rounded-lg p-4">
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                        <i class="fas fa-calendar mr-2 text-purple-600"></i>
                                        Fecha de Nacimiento
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model.blur="beneficiary_birth_date"
                                        max="{{ now()->subYears(18)->format('Y-m-d') }}"
                                        class="w-full bg-white dark:bg-gray-800 border-2 border-purple-300 dark:border-purple-600 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                                    @if($beneficiary_birth_date && $this->calculated_age !== null)
                                        <p class="text-sm mt-2 font-semibold text-purple-700 dark:text-purple-300">
                                            Edad: {{ $this->calculated_age }} años
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <x-input
                                    label="Teléfono"
                                    wire:model="beneficiary_phone"
                                    placeholder="0424-1234567"
                                />
                            </div>
                            
                            <div>
                                <x-input
                                    label="Correo Electrónico"
                                    type="email"
                                    wire:model="beneficiary_email"
                                    placeholder="correo@ejemplo.com"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Ubicación (Basado en Circuito Comunal)
                        </h3>
                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            Los datos de Estado, Municipio y Parroquia se completarán automáticamente al seleccionar el Circuito Comunal.
                        </p>
                        
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <x-select
                                    label="Estado *"
                                    wire:model="estado_id"
                                    :options="$estados"
                                    option-label="estado"
                                    option-value="id"
                                    disabled
                                    hint="Solo disponible: Trujillo"
                                />
                            </div>
                            
                            <div>
                                <x-select
                                    label="Municipio *"
                                    wire:model="municipio_id"
                                    :options="$municipios"
                                    option-label="municipio"
                                    option-value="id"
                                    disabled
                                    hint="Solo disponible: Escuque"
                                />
                            </div>
                            
                            <div>
                                <x-select
                                    label="Parroquia *"
                                    wire:model.live="parroquia_id"
                                    placeholder="Seleccione parroquia"
                                    :options="$parroquias"
                                    option-label="parroquia"
                                    option-value="id"
                                    required
                                    hint="Seleccione para ver los circuitos comunales"
                                />
                            </div>
                            
                            <div>
                                <x-input
                                    label="Sector (Auto-completado desde CC)"
                                    wire:model="sector"
                                    placeholder="Se completará automáticamente..."
                                    readonly
                                    hint="Este campo se completa al seleccionar el Circuito Comunal"
                                />
                            </div>
                            
                            <div class="md:col-span-2">
                                <x-textarea
                                    label="Dirección"
                                    wire:model="address"
                                    placeholder="Dirección completa..."
                                    rows="2"
                                />
                            </div>
                            
                            <div>
                                <x-input
                                    label="Punto de Referencia"
                                    wire:model="reference_point"
                                    placeholder="Cerca de..."
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Circuito Comunal (OBLIGATORIO) -->
                    <div class="mb-8 rounded-xl border-4 border-orange-400 bg-gradient-to-br from-orange-50 to-red-50 p-6 shadow-lg dark:border-orange-600 dark:from-orange-900/20 dark:to-red-900/20">
                        <h3 class="mb-4 flex items-center text-xl font-bold text-orange-900 dark:text-orange-100">
                            <i class="fas fa-map-marked-alt mr-3 text-2xl"></i>
                            Circuito Comunal (OBLIGATORIO)
                        </h3>
                        @if(!$beneficiarySelected && !$parroquia_id)
                        <p class="mb-6 text-sm font-medium text-orange-800 dark:text-orange-200">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Primero seleccione la Parroquia arriba, luego elija el Circuito Comunal específico.
                        </p>
                        @endif
                        
                        @if($parroquia_id && $circuitos->count() > 0)
                        <div class="mb-4 rounded-lg bg-white p-4 shadow-inner dark:bg-gray-800">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                                    Circuitos disponibles en esta parroquia:
                                </span>
                                <span class="rounded-full bg-blue-600 px-3 py-1 text-sm font-bold text-white">
                                    {{ $circuitos->count() }} CC
                                </span>
                            </div>
                        </div>
                        
                        <x-select
                            label="Seleccione el Circuito Comunal *"
                            wire:model.live="circuito_comunal_id"
                            placeholder="Elija un circuito comunal..."
                            :options="$circuitos->map(fn($c) => ['value' => $c->id, 'label' => $c->codigo . ' - ' . $c->nombre])->toArray()"
                            option-label="label"
                            option-value="value"
                            required
                            hint="El sector se completará automáticamente al seleccionar"
                        />
                        
                        @if($circuito_comunal_id)
                        <div class="mt-4 space-y-3">
                            <div class="rounded-lg border-2 border-green-300 bg-green-50 p-4 dark:border-green-700 dark:bg-green-900/30">
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle mr-3 mt-1 text-2xl text-green-600 dark:text-green-400"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold text-green-900 dark:text-green-100">
                                            Circuito Comunal Seleccionado
                                        </p>
                                        <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                            {{ $circuitos->firstWhere('id', $circuito_comunal_id)?->descripcion }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($sector)
                            <div class="rounded-lg border-2 border-blue-300 bg-blue-50 p-4 dark:border-blue-700 dark:bg-blue-900/30">
                                <div class="flex items-start">
                                    <i class="fas fa-map-pin mr-3 mt-1 text-2xl text-blue-600 dark:text-blue-400"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold text-blue-900 dark:text-blue-100">
                                            Sector Auto-completado
                                        </p>
                                        <p class="mt-1 text-lg font-bold text-blue-700 dark:text-blue-300">
                                            {{ $sector }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        @else
                        <div class="rounded-lg border-2 border-dashed border-yellow-400 bg-yellow-50 p-6 text-center dark:border-yellow-600 dark:bg-yellow-900/20">
                            <i class="fas fa-arrow-up mb-3 text-4xl text-yellow-600 dark:text-yellow-400"></i>
                            <p class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">
                                Seleccione una Parroquia primero
                            </p>
                            <p class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                Los Circuitos Comunales se cargarán automáticamente según la Parroquia seleccionada.
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- INFORMACIÓN OBLIGATORIA -->
                    <div class="mb-8 rounded-xl border-4 border-red-400 bg-gradient-to-br from-red-50 to-pink-50 p-6 shadow-lg dark:border-red-600 dark:from-red-900/20 dark:to-pink-900/20">
                        <h3 class="mb-4 flex items-center text-xl font-bold text-red-900 dark:text-red-100">
                            <i class="fas fa-exclamation-circle mr-3 text-2xl"></i>
                            INFORMACIÓN OBLIGATORIA
                        </h3>
                        <p class="mb-6 text-sm font-medium text-red-800 dark:text-red-200">
                            <i class="fas fa-asterisk mr-1"></i>
                            Todos estos campos son obligatorios para generar el reporte correctamente.
                        </p>
                        
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <x-input
                                    label="Fecha de Entrega *"
                                    type="date"
                                    wire:model="delivery_date"
                                    required
                                />
                            </div>
                            
                            <div>
                                <x-select
                                    label="Estado del Reporte *"
                                    wire:model="status"
                                    :options="[
                                        ['value' => 'in_process', 'label' => '⏳ En Proceso'],
                                        ['value' => 'delivered', 'label' => '✅ Entregado'],
                                        ['value' => 'not_delivered', 'label' => '❌ No Entregado'],
                                    ]"
                                    option-label="label"
                                    option-value="value"
                                    required
                                    hint="Estado actual de la entrega"
                                />
                            </div>
                            
                            <div class="md:col-span-2">
                                <x-input
                                    label="Detalle de la Entrega *"
                                    wire:model="delivery_detail"
                                    placeholder="Descripción detallada de la entrega..."
                                    required
                                    hint="Mínimo 10 caracteres"
                                />
                            </div>
                            
                            <div class="md:col-span-2">
                                <x-textarea
                                    label="Notas u Observaciones *"
                                    wire:model="notes"
                                    placeholder="Observaciones importantes, condiciones especiales, etc..."
                                    rows="3"
                                    required
                                    hint="Mínimo 10 caracteres"
                                />
                            </div>
                        </div>
                        
                        @if($status === 'delivered')
                        <div class="mt-4 rounded-lg border-2 border-yellow-400 bg-yellow-50 p-4 dark:border-yellow-600 dark:bg-yellow-900/30">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle mr-3 mt-1 text-2xl text-yellow-600 dark:text-yellow-400"></i>
                                <div class="flex-1">
                                    <p class="font-bold text-yellow-900 dark:text-yellow-100">
                                        ⚠️ ADVERTENCIA IMPORTANTE
                                    </p>
                                    <p class="mt-1 text-sm text-yellow-800 dark:text-yellow-200">
                                        Al marcar este reporte como <strong>"ENTREGADO"</strong>, esta acción <strong class="underline">NO TIENE VUELTA ATRÁS</strong>. Una vez guardado, el reporte NO podrá ser editado nuevamente por medidas de seguridad. Asegúrese de que toda la información sea correcta antes de continuar.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <x-button
                            flat
                            label="Cancelar"
                            wire:click="$parent.redirect('/admin/inventory-exits', { navigate: true })"
                        />
                        <x-button
                            primary
                            label="Guardar Reporte"
                            type="submit"
                            spinner
                        />
                    </div>
                </form>
            </div>
        </div>

    </x-container>
</div>

@script
<script>
    // Escuchar evento de error de stock
    $wire.on('show-error', (event) => {
        window.$wireui.notify({
            title: 'Stock Insuficiente',
            description: event.message || 'No hay suficiente stock disponible',
            icon: 'error',
        });
    });
    
    // Mostrar errores de sesión flash si existen
    @if(session()->has('error'))
        window.$wireui.notify({
            title: 'Error',
            description: '{{ session('error') }}',
            icon: 'error',
        });
    @endif
    
    @if(session()->has('item_added'))
        window.$wireui.notify({
            title: 'Éxito',
            description: '{{ session('item_added') }}',
            icon: 'success',
        });
    @endif
</script>
@endscript
