<?php

use App\Models\Report;
use App\Services\ReportPdfService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Symfony\Component\HttpFoundation\StreamedResponse;

new #[Layout('livewire.layout.admin.admin'), Title('Detalle del Reporte')] class extends Component {

    public Report $report;

    public function mount($id)
    {
        $this->report = Report::with(['items.product.category', 'items.warehouse', 'beneficiary', 'creator'])
            ->findOrFail($id);
    }
    
    public function downloadPdf()
    {
        try {
            $pdfService = app(ReportPdfService::class);
            
            // Verificar si el PDF existe, si no, generarlo
            if (!$pdfService->pdfExists($this->report)) {
                $pdfService->generatePdf($this->report);
            }
            
            // Registrar la actividad de descarga
            activity('web')
                ->causedBy(auth()->user())
                ->performedOn($this->report)
                ->withProperties([
                    'report_id' => $this->report->id,
                    'report_code' => $this->report->report_code,
                    'parish' => $this->report->parish,
                    'action' => 'download_report_pdf',
                    'download_method' => 'web_system'
                ])
                ->log("Descargó PDF del reporte: {$this->report->report_code}");
            
            return $pdfService->downloadPdf($this->report);
            
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo generar el PDF: ' . $e->getMessage(),
            ]);
        }
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
                <i class="fas fa-file-alt mr-2"></i>
                Detalle del Reporte: {{ $report->report_code }}
            </h2>
            <div class="flex gap-2">
                @if($report->status !== 'delivered')
                <a href="/admin/reports/{{ $report->id }}/edit" wire:navigate>
                    <x-button
                        flat
                        label="Editar"
                    />
                </a>
                @endif
                <a href="/admin/reports" wire:navigate>
                    <x-button
                        flat
                        label="Volver"
                    />
                </a>
            </div>
        </div>
    </x-slot>

    <x-container class="py-12">
        
        <!-- Estado y Código -->
        <div class="mb-6 rounded-xl border-2 border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $report->report_code }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Creado el {{ $report->created_at->format('d/m/Y H:i') }} por {{ $report->creator->name }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Botón Descargar PDF Grande -->
                    <button 
                        wire:click="downloadPdf"
                        wire:loading.attr="disabled"
                        class="group relative inline-flex items-center rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-6 py-3 text-lg font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-red-700 hover:to-red-800 hover:shadow-2xl active:scale-95 disabled:opacity-75 disabled:cursor-not-allowed"
                    >
                        <!-- Ícono con animación -->
                        <i class="fas fa-file-pdf mr-2 transition-transform duration-300 group-hover:scale-110"></i>
                        
                        <!-- Texto normal -->
                        <span wire:loading.remove wire:target="downloadPdf">
                            Descargar PDF
                        </span>
                        
                        <!-- Texto mientras carga -->
                        <span wire:loading wire:target="downloadPdf" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generando PDF...
                        </span>
                        
                        <!-- Efecto de brillo al hover -->
                        <span class="absolute inset-0 rounded-xl bg-white opacity-0 transition-opacity duration-300 group-hover:opacity-10"></span>
                    </button>
                    
                    @if($report->status === 'delivered')
                    <span class="inline-flex items-center rounded-full bg-gradient-to-r from-green-500 to-green-600 px-6 py-3 text-lg font-bold text-white shadow-md">
                        <i class="fas fa-check-circle mr-2"></i>
                        ENTREGADO
                    </span>
                    @elseif($report->status === 'in_process')
                    <span class="inline-flex items-center rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-3 text-lg font-bold text-white shadow-md">
                        <i class="fas fa-clock mr-2"></i>
                        EN PROCESO
                    </span>
                    @else
                    <span class="inline-flex items-center rounded-full bg-gradient-to-r from-red-500 to-red-600 px-6 py-3 text-lg font-bold text-white shadow-md">
                        <i class="fas fa-times-circle mr-2"></i>
                        NO ENTREGADO
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            
            <!-- Información del Beneficiario -->
            <div class="rounded-xl border-2 border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 flex items-center text-lg font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Beneficiario
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre Completo</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $report->beneficiary_full_name }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Documento</label>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ $report->beneficiary_full_cedula }}
                        </p>
                    </div>
                    @if($report->beneficiary_phone)
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</label>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-phone mr-1"></i>
                            {{ $report->beneficiary_phone }}
                        </p>
                    </div>
                    @endif
                    @if($report->beneficiary_email)
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-envelope mr-1"></i>
                            {{ $report->beneficiary_email }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ubicación -->
            <div class="rounded-xl border-2 border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 flex items-center text-lg font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-map-marker-alt mr-2 text-orange-600"></i>
                    Ubicación
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Circuito Comunal</label>
                        <p class="font-semibold text-orange-600">
                            <i class="fas fa-map-marked-alt mr-1"></i>
                            {{ $report->communal_circuit }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado / Municipio / Parroquia</label>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ $report->state }} / {{ $report->municipality }} / {{ $report->parish }}
                        </p>
                    </div>
                    @if($report->sector)
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Sector</label>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ $report->sector }}
                        </p>
                    </div>
                    @endif
                    @if($report->address)
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Dirección</label>
                        <p class="text-gray-900 dark:text-white">
                            {{ $report->address }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Entregas del Reporte -->
        <div class="mt-6 rounded-xl border-4 border-purple-400 bg-white p-6 shadow-lg dark:border-purple-600 dark:bg-gray-800">
            <h3 class="mb-4 flex items-center justify-between text-xl font-bold text-purple-900 dark:text-purple-100">
                <span>
                    <i class="fas fa-boxes mr-2"></i>
                    Entregas del Reporte
                </span>
                <span class="rounded-full bg-purple-600 px-4 py-2 text-white">
                    {{ $report->items->count() }} {{ $report->items->count() == 1 ? 'entrega' : 'entregas' }}
                </span>
            </h3>
            
            <div class="space-y-4">
                @forelse($report->items as $index => $item)
                <div class="rounded-lg border-2 border-purple-200 bg-purple-50 p-4 dark:border-purple-700 dark:bg-purple-900/20">
                    <div class="flex items-start gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-600 text-xl font-bold text-white">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $item->product->name }}
                                </p>
                                <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-3 py-1 text-sm font-bold text-white shadow-sm">
                                    <i class="{{ $item->product->category->icon }} mr-2"></i>
                                    {{ $item->product->category->name }}
                                </span>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-4 text-sm">
                                <span class="text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-warehouse mr-1"></i>
                                    <strong>Almacén:</strong> {{ $item->warehouse->name }}
                                </span>
                                <span class="text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-box mr-1"></i>
                                    <strong>Cantidad:</strong> {{ $item->quantity }}
                                </span>
                            </div>
                            @if($item->notes)
                            <p class="mt-2 italic text-gray-500 dark:text-gray-400">
                                <i class="fas fa-sticky-note mr-1"></i>
                                {{ $item->notes }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500">No hay entregas registradas</p>
                @endforelse
            </div>
        </div>

        <!-- Información del Reporte -->
        <div class="mt-6 rounded-xl border-2 border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 flex items-center text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-info-circle mr-2 text-red-600"></i>
                Información del Reporte
            </h3>
            
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Entrega</label>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $report->delivery_date->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Cantidad Total</label>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $report->quantity }} unidades
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Detalle de la Entrega</label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $report->delivery_detail }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Notas</label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $report->notes }}
                    </p>
                </div>
            </div>
        </div>

    </x-container>
</div>
