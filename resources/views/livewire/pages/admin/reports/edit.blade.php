<?php

use App\Models\Report;
use App\Services\ReportPdfService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new #[Layout('livewire.layout.admin.admin'), Title('Editar Reporte')] class extends Component {

    public Report $report;
    
    #[Validate('required|in:in_process,delivered,not_delivered')]
    public $status = '';
    
    #[Validate('required|string|min:10')]
    public $delivery_detail = '';
    
    #[Validate('required|string|min:10')]
    public $notes = '';
    
    #[Validate('required|date')]
    public $delivery_date = '';
    
    public $showDeliveredWarning = false;
    
    // Items del reporte
    public $items = [];

    public function mount($id)
    {
        $this->report = Report::with(['items.product', 'items.warehouse', 'beneficiary'])->findOrFail($id);
        
        // No permitir edición si ya fue entregado
        if ($this->report->status === 'delivered') {
            session()->flash('error', 'No se puede editar un reporte que ya fue marcado como "Entregado".');
            return $this->redirect(route('admin.reports.show', $id), navigate: true);
        }
        
        // Cargar datos actuales
        $this->status = $this->report->status;
        $this->delivery_detail = $this->report->delivery_detail;
        $this->notes = $this->report->notes;
        $this->delivery_date = $this->report->delivery_date->format('Y-m-d');
        
        // Cargar items
        $this->items = $this->report->items->map(function($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'warehouse_id' => $item->warehouse->id,
                'warehouse_name' => $item->warehouse->name,
                'quantity' => $item->quantity,
                'original_quantity' => $item->quantity,
                'notes' => $item->notes ?? '',
            ];
        })->toArray();
    }
    
    public function updatedStatus($value)
    {
        if ($value === 'delivered') {
            $this->showDeliveredWarning = true;
        } else {
            $this->showDeliveredWarning = false;
        }
    }

    public function update()
    {
        // Verificar nuevamente que no esté entregado
        if ($this->report->status === 'delivered') {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Este reporte ya no puede ser editado.',
            ]);
            return;
        }
        
        $this->validate();
        
        // Validar cantidades de items
        foreach ($this->items as $index => $item) {
            if ($item['quantity'] < 1) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error de Validación',
                    'text' => 'La cantidad debe ser al menos 1 para todos los productos.',
                ]);
                return;
            }
        }
        
        // Guardar el estado anterior del reporte
        $previousStatus = $this->report->status;
        $isChangingToDelivered = ($previousStatus !== 'delivered' && $this->status === 'delivered');
        
        // Actualizar los items
        $totalQuantity = 0;
        foreach ($this->items as $item) {
            $reportItem = \App\Models\ReportItem::find($item['id']);
            if ($reportItem) {
                
                // Si está cambiando a "delivered" por primera vez, crear salida de inventario
                if ($isChangingToDelivered) {
                    // Verificar stock disponible
                    $lastInventory = \App\Models\Inventory::where('product_id', $item['product_id'])
                        ->where('warehouse_id', $item['warehouse_id'])
                        ->latest()
                        ->first();
                    
                    $currentBalance = $lastInventory ? $lastInventory->quantity_balance : 0;
                    
                    if ($currentBalance < $item['quantity']) {
                        $this->dispatch('showAlert', [
                            'icon' => 'warning',
                            'title' => 'Stock Insuficiente',
                            'text' => 'No hay suficiente stock de ' . $item['product_name'] . '. Disponible: ' . $currentBalance,
                        ]);
                        return;
                    }
                    
                    // Crear salida de inventario
                    $newBalance = $currentBalance - $item['quantity'];
                    $inventory = \App\Models\Inventory::create([
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $item['warehouse_id'],
                        'detail' => 'Salida - Reporte ' . $this->report->report_code . ' (Entregado)',
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
                    
                    // Asociar el inventory_id al reportItem
                    $reportItem->update([
                        'inventory_id' => $inventory->id,
                        'quantity' => $item['quantity'],
                        'notes' => $item['notes'],
                    ]);
                } else {
                    // Si NO está cambiando a delivered, solo actualizar los datos sin tocar inventario
                    $reportItem->update([
                        'quantity' => $item['quantity'],
                        'notes' => $item['notes'],
                    ]);
                }
            }
            
            $totalQuantity += $item['quantity'];
        }
        
        // Actualizar el reporte
        $this->report->update([
            'status' => $this->status,
            'delivery_detail' => $this->delivery_detail,
            'notes' => $this->notes,
            'delivery_date' => $this->delivery_date,
            'quantity' => $totalQuantity,
        ]);
        
        // Regenerar PDF del reporte con la información actualizada
        try {
            $reportId = $this->report->id;
            dispatch(function () use ($reportId) {
                $report = \App\Models\Report::find($reportId);
                if ($report) {
                    $pdfService = app(\App\Services\ReportPdfService::class);
                    $pdfService->regeneratePdf($report);
                }
            })->afterResponse();
        } catch (\Exception $e) {
            \Log::error('Error regenerando PDF: ' . $e->getMessage());
            // No interrumpir el flujo
        }
        
        if ($this->status === 'delivered') {
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Reporte Actualizado!',
                'text' => 'Este reporte ha sido marcado como ENTREGADO y ya no podrá ser editado.',
                'timer' => 3000,
            ]);
        } else {
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Reporte Actualizado!',
                'text' => 'Los cambios se han guardado correctamente.',
                'timer' => 3000,
            ]);
        }
        
        return $this->redirect(route('admin.reports.show', $this->report->id), navigate: true);
    }

    public function with(): array
    {
        return [
            'report' => $this->report,
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                <i class="fas fa-edit mr-2"></i>
                Editar Reporte: {{ $report->report_code }}
            </h2>
            <a href="/admin/reports/{{ $report->id }}" wire:navigate>
                <x-button
                    flat
                    label="Volver"
                />
            </a>
        </div>
    </x-slot>

    <x-container class="py-12">
        
        <!-- Información del Reporte -->
        <div class="mb-6 rounded-xl border-2 border-blue-200 bg-blue-50 p-6 dark:border-blue-700 dark:bg-blue-900/20">
            <h3 class="mb-2 text-lg font-bold text-blue-900 dark:text-blue-100">
                <i class="fas fa-info-circle mr-2"></i>
                Información del Reporte
            </h3>
            <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-3">
                <div>
                    <span class="font-semibold">Código:</span> {{ $report->report_code }}
                </div>
                <div>
                    <span class="font-semibold">Beneficiario:</span> {{ $report->beneficiary_full_name }}
                </div>
                <div>
                    <span class="font-semibold">Cantidad Total:</span> {{ $report->quantity }} unidades
                </div>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
            <div class="p-6">
                <form wire:submit="update">
                    
                    <!-- ENTREGAS DEL REPORTE (EDITABLE) -->
                    <div class="mb-8 rounded-xl border-4 border-purple-400 bg-gradient-to-br from-purple-50 to-indigo-50 p-6 shadow-lg dark:border-purple-600 dark:from-purple-900/20 dark:to-indigo-900/20">
                        <h3 class="mb-4 flex items-center text-xl font-bold text-purple-900 dark:text-purple-100">
                            <i class="fas fa-boxes mr-3 text-2xl"></i>
                            Entregas del Reporte (Editar Cantidades)
                        </h3>
                        <p class="mb-6 text-sm font-medium text-purple-800 dark:text-purple-200">
                            <i class="fas fa-edit mr-1"></i>
                            Puede modificar las <strong>cantidades</strong> y <strong>notas</strong> de cada entrega.
                        </p>
                        
                        <div class="space-y-4">
                            @foreach($items as $index => $item)
                            <div class="rounded-lg border-2 border-purple-200 bg-white p-5 dark:border-purple-700 dark:bg-gray-800">
                                <div class="flex items-start gap-4">
                                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-purple-600 text-lg font-bold text-white">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <p class="mb-3 text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $item['product_name'] }}
                                        </p>
                                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-warehouse mr-1"></i>
                                            Almacén: <strong>{{ $item['warehouse_name'] }}</strong>
                                        </p>
                                        
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <x-input
                                                    label="Cantidad *"
                                                    type="number"
                                                    min="1"
                                                    wire:model="items.{{ $index }}.quantity"
                                                    required
                                                    hint="Cantidad a entregar"
                                                />
                                            </div>
                                            <div>
                                                <x-input
                                                    label="Notas (Opcional)"
                                                    wire:model="items.{{ $index }}.notes"
                                                    placeholder="Observaciones de esta entrega..."
                                                />
                                            </div>
                                        </div>
                                        
                                        @if($item['quantity'] != $item['original_quantity'])
                                        <div class="mt-3 rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                <strong>Cantidad original:</strong> {{ $item['original_quantity'] }} 
                                                → <strong>Nueva cantidad:</strong> {{ $item['quantity'] }}
                                                ({{ $item['quantity'] > $item['original_quantity'] ? '+' : '' }}{{ $item['quantity'] - $item['original_quantity'] }})
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- INFORMACIÓN DEL REPORTE -->
                    <div class="mb-8 rounded-xl border-4 border-red-400 bg-gradient-to-br from-red-50 to-pink-50 p-6 shadow-lg dark:border-red-600 dark:from-red-900/20 dark:to-pink-900/20">
                        <h3 class="mb-4 flex items-center text-xl font-bold text-red-900 dark:text-red-100">
                            <i class="fas fa-exclamation-circle mr-3 text-2xl"></i>
                            INFORMACIÓN DEL REPORTE (Editable)
                        </h3>
                        <p class="mb-6 text-sm font-medium text-red-800 dark:text-red-200">
                            <i class="fas fa-asterisk mr-1"></i>
                            Puede editar el estado, información del reporte y las cantidades de las entregas. El beneficiario y los productos no se pueden modificar.
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
                                    wire:model.live="status"
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
                        
                        @if($showDeliveredWarning)
                        <div class="mt-6 rounded-xl border-4 border-yellow-500 bg-yellow-50 p-6 dark:border-yellow-600 dark:bg-yellow-900/30">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle mr-4 mt-1 text-4xl text-yellow-600 dark:text-yellow-400"></i>
                                <div class="flex-1">
                                    <p class="text-xl font-black text-yellow-900 dark:text-yellow-100">
                                        ⚠️ ADVERTENCIA CRÍTICA - ACCIÓN IRREVERSIBLE
                                    </p>
                                    <div class="mt-3 space-y-2 text-sm text-yellow-800 dark:text-yellow-200">
                                        <p class="font-bold">
                                            Está a punto de marcar este reporte como <strong class="underline">"ENTREGADO"</strong>.
                                        </p>
                                        <p class="font-semibold">
                                            ⛔ Una vez guardado con este estado, esta acción <strong class="uppercase underline">NO TIENE VUELTA ATRÁS</strong>.
                                        </p>
                                        <p>
                                            🔒 El reporte quedará bloqueado permanentemente y <strong>NO PODRÁ SER EDITADO NUEVAMENTE</strong> por ningún usuario del sistema.
                                        </p>
                                        <p class="mt-3 rounded-lg bg-yellow-100 p-3 dark:bg-yellow-900/50">
                                            <i class="fas fa-shield-alt mr-1"></i>
                                            Esta es una medida de seguridad para mantener la integridad de los reportes entregados.
                                        </p>
                                        <p class="mt-3 font-bold text-red-700 dark:text-red-400">
                                            ✓ Verifique que TODA la información sea correcta antes de guardar.
                                        </p>
                                    </div>
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
                            wire:click="$parent.redirect('/admin/reports/{{ $report->id }}', { navigate: true })"
                            type="button"
                        />
                        <x-button
                            primary
                            label="{{ $status === 'delivered' ? 'Confirmar y Marcar como Entregado (IRREVERSIBLE)' : 'Guardar Cambios' }}"
                            type="submit"
                            spinner
                            class="{{ $status === 'delivered' ? 'bg-red-600 hover:bg-red-700' : '' }}"
                        />
                    </div>
                </form>
            </div>
        </div>

    </x-container>
</div>
