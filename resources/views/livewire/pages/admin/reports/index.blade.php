<?php

use App\Models\Report;
use App\Models\Beneficiary;
use App\Models\Product;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

new #[Layout('livewire.layout.admin.admin'), Title('Reportes de Entregas')] class extends Component {
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';
    public $filterParroquia = '';
    public $filterCircuit = '';
    public $activeTab = 'all';
    public $filterCategory = null;

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'all') {
            $this->filterCategory = null;
        } else {
            $this->filterCategory = $tab;
        }
        $this->resetPage();
    }

    public function markAsDelivered($reportId)
    {
        $report = Report::with('items.product')->findOrFail($reportId);
        
        // Verificar si ya está entregado
        if ($report->status === 'delivered') {
            $this->dispatch('showAlert', [
                'icon' => 'info',
                'title' => 'Información',
                'text' => 'Este reporte ya está marcado como Entregado.'
            ]);
            return;
        }
        
        // Crear salidas de inventario para cada item
        foreach ($report->items as $item) {
            // Obtener el último balance de inventario para este producto/almacén
            $lastInventory = \App\Models\Inventory::where('product_id', $item->product_id)
                ->where('warehouse_id', $item->warehouse_id)
                ->latest()
                ->first();
            
            $stockActual = $lastInventory ? $lastInventory->quantity_balance : 0;
            
            // Verificar si hay stock suficiente
            if ($stockActual < $item->quantity) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Stock Insuficiente',
                    'text' => "No hay suficiente stock de {$item->product->name}. Stock actual: " . number_format($stockActual) . ", Requerido: " . number_format($item->quantity)
                ]);
                return;
            }
            
            // Obtener precio unitario del último registro
            $costOut = $lastInventory ? $lastInventory->cost_balance : 0;
            
            // Crear salida de inventario
            $inventory = \App\Models\Inventory::create([
                'product_id' => $item->product_id,
                'warehouse_id' => $item->warehouse_id,
                'detail' => "Salida por reporte #{$report->report_code} - Marcado como entregado",
                'quantity_in' => 0,
                'cost_in' => 0,
                'total_in' => 0,
                'quantity_out' => $item->quantity,
                'cost_out' => $costOut,
                'total_out' => $item->quantity * $costOut,
                'quantity_balance' => $stockActual - $item->quantity,
                'cost_balance' => $costOut,
                'total_balance' => ($stockActual - $item->quantity) * $costOut,
                'inventoryable_type' => 'App\\Models\\Report',
                'inventoryable_id' => $report->id,
            ]);
            
            // Actualizar inventory_id en el item
            $item->update(['inventory_id' => $inventory->id]);
        }
        
        // Marcar reporte como entregado
        $report->update(['status' => 'delivered']);
        
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => '¡Entregado!',
            'text' => 'El reporte ha sido marcado como Entregado y se ha registrado la salida de inventario.'
        ]);
        
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Report::with(['items.product.category', 'categories', 'beneficiary', 'creator'])
            ->latest();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterParroquia) {
            $query->where('parish', $this->filterParroquia);
        }

        if ($this->filterCircuit) {
            $query->where('communal_circuit', $this->filterCircuit);
        }

        // Filtro por categoría usando la relación directa
        if ($this->filterCategory) {
            $query->whereHas('categories', function($q) {
                $q->where('categories.id', $this->filterCategory);
            });
        }

        // Obtener circuitos comunales reales con sus códigos
        $circuitosComunales = \App\Models\CircuitoComunal::with('parroquia')
            ->orderBy('codigo')
            ->get();

        // Obtener parroquias únicas
        $parroquias = \App\Models\Parroquia::orderBy('parroquia')->get();

        // Obtener categorías activas
        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Contar reportes por categoría usando la relación directa
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category->id] = Report::whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })->count();
        }

        // Estadísticas
        $stats = [
            'total' => Report::count(),
            'delivered' => Report::delivered()->count(),
            'in_process' => Report::inProcess()->count(),
            'not_delivered' => Report::notDelivered()->count(),
            'this_month' => Report::whereMonth('delivery_date', now()->month)
                ->whereYear('delivery_date', now()->year)
                ->count(),
        ];

        return [
            'reports' => $query->paginate(15),
            'circuitosComunales' => $circuitosComunales,
            'parroquias' => $parroquias,
            'stats' => $stats,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'totalReports' => Report::count(),
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                <i class="fas fa-file-alt mr-2"></i>
                {{ __('Reportes de Entregas') }}
            </h2>
            <a href="{{ route('admin.reports.create') }}" wire:navigate>
                <x-button primary label="Generar Reporte" class="bg-green-600 hover:bg-green-700" />
            </a>
        </div>
    </x-slot>

    <x-container class="py-12">
        
        <!-- Estadísticas -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total Reportes</p>
                        <p class="mt-1 text-3xl font-bold">{{ $stats['total'] }}</p>
                    </div>
                    <i class="fas fa-file-alt text-4xl opacity-50"></i>
                </div>
            </div>

            <div class="rounded-lg bg-gradient-to-br from-green-500 to-green-700 p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">✅ Entregados</p>
                        <p class="mt-1 text-3xl font-bold">{{ $stats['delivered'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl opacity-50"></i>
                </div>
            </div>

            <div class="rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-700 p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">⏳ En Proceso</p>
                        <p class="mt-1 text-3xl font-bold">{{ $stats['in_process'] }}</p>
                    </div>
                    <i class="fas fa-clock text-4xl opacity-50"></i>
                </div>
            </div>

            <div class="rounded-lg bg-gradient-to-br from-red-500 to-red-700 p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">❌ No Entregados</p>
                        <p class="mt-1 text-3xl font-bold">{{ $stats['not_delivered'] }}</p>
                    </div>
                    <i class="fas fa-times-circle text-4xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- TABS POR CATEGORÍA -->
        <div class="mb-6 overflow-hidden rounded-xl border-2 border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex overflow-x-auto" aria-label="Tabs">
                    <!-- Tab: Todos -->
                    <button
                        wire:click="setActiveTab('all')"
                        class="flex items-center whitespace-nowrap border-b-2 px-6 py-4 text-sm font-medium transition-all
                            {{ $activeTab === 'all' 
                                ? 'border-blue-500 bg-blue-50 text-blue-600 dark:border-blue-400 dark:bg-blue-900/30 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300' }}">
                        <i class="fa-solid fa-th-large mr-2 text-lg"></i>
                        <span class="font-semibold">Todos los Reportes</span>
                        <span class="ml-3 rounded-full px-3 py-1 text-xs font-bold
                            {{ $activeTab === 'all' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $totalReports }}
                        </span>
                    </button>

                    <!-- Tab por cada categoría -->
                    @foreach($categories as $cat)
                    <button
                        wire:click="setActiveTab({{ $cat->id }})"
                        class="flex items-center whitespace-nowrap border-b-2 px-6 py-4 text-sm font-medium transition-all
                            {{ $activeTab == $cat->id 
                                ? 'border-blue-500 bg-blue-50 text-blue-600 dark:border-blue-400 dark:bg-blue-900/30 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300' }}">
                        <i class="{{ $cat->icon }} mr-2 text-lg"></i>
                        <span class="font-semibold">{{ $cat->name }}</span>
                        <span class="ml-3 rounded-full px-3 py-1 text-xs font-bold
                            {{ $activeTab == $cat->id ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $categoryCounts[$cat->id] ?? 0 }}
                        </span>
                    </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Filtros -->
        <div class="mb-6 rounded-xl border-2 border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-filter mr-2"></i>
                Filtros de Búsqueda
            </h3>
            
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Búsqueda -->
                <div>
                    <x-input
                        label="Búsqueda General"
                        wire:model.live.debounce.300ms="search"
                        placeholder="🔍 Código, nombre, cédula..."
                    />
                </div>

                <!-- Filtro Estado -->
                <div>
                    <x-select
                        label="Estado del Reporte"
                        wire:model.live="filterStatus"
                        placeholder="Todos los estados"
                        :options="[
                            ['value' => 'all', 'label' => 'Todos los estados'],
                            ['value' => 'delivered', 'label' => '✅ Entregado'],
                            ['value' => 'in_process', 'label' => '⏳ En Proceso'],
                            ['value' => 'not_delivered', 'label' => '❌ No Entregado'],
                        ]"
                        option-label="label"
                        option-value="value"
                    />
                </div>

                <!-- Filtro Parroquia -->
                <div>
                    <x-select
                        label="Parroquia"
                        wire:model.live="filterParroquia"
                        placeholder="Todas las parroquias"
                        :options="$parroquias->map(fn($p) => ['value' => $p->parroquia, 'label' => $p->parroquia])->toArray()"
                        option-label="label"
                        option-value="value"
                    />
                </div>

                <!-- Filtro Circuito Comunal -->
                <div>
                    <x-select
                        label="Circuito Comunal"
                        wire:model.live="filterCircuit"
                        placeholder="Todos los circuitos"
                        :options="$circuitosComunales->map(fn($c) => ['value' => $c->codigo, 'label' => $c->codigo . ' - ' . $c->nombre . ' (' . $c->parroquia->parroquia . ')'])->toArray()"
                        option-label="label"
                        option-value="value"
                    />
                </div>
            </div>

            @if($search || $filterStatus !== 'all' || $filterParroquia || $filterCircuit)
            <div class="mt-4 flex items-center justify-between rounded-lg border-t-2 border-blue-200 bg-blue-50 p-3 dark:border-blue-700 dark:bg-blue-900/20">
                <span class="flex items-center text-sm font-semibold text-blue-700 dark:text-blue-300">
                    <i class="fas fa-filter mr-2"></i>
                    Filtros activos
                </span>
                <button
                    wire:click="$set('search', ''); $set('filterStatus', 'all'); $set('filterParroquia', ''); $set('filterCircuit', '')"
                    class="flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                    <i class="fas fa-times mr-2"></i>
                    Limpiar todos los filtros
                </button>
            </div>
            @endif
        </div>

        <!-- Tabla de Reportes -->
        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Entregas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Beneficiario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Circuito Comunal
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Cantidad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Fecha Entrega
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Categoría
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <!-- Código -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-mono text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $report->report_code }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $report->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Entregas -->
                            <td class="px-6 py-4">
                                @if($report->items->count() > 0)
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full bg-purple-600 px-3 py-1 text-xs font-bold text-white">
                                        {{ $report->items->count() }} {{ $report->items->count() == 1 ? 'entrega' : 'entregas' }}
                                    </span>
                                    <div class="flex -space-x-2">
                                        @foreach($report->items->take(3) as $item)
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white ring-2 ring-white dark:ring-gray-800" title="{{ $item->product->name }}">
                                            {{ substr($item->product->name, 0, 1) }}
                                        </div>
                                        @endforeach
                                        @if($report->items->count() > 3)
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 text-xs font-bold text-white ring-2 ring-white dark:ring-gray-800">
                                            +{{ $report->items->count() - 3 }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <span class="text-sm text-gray-500">Sin entregas</span>
                                @endif
                            </td>

                            <!-- Beneficiario -->
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $report->beneficiary_full_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $report->beneficiary_full_cedula }}
                                    </div>
                                </div>
                            </td>

                            <!-- Circuito Comunal y Parroquia -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-1 text-xs font-bold text-white shadow-md">
                                        <i class="fas fa-map-marked-alt mr-1"></i>
                                        {{ $report->communal_circuit }}
                                    </span>
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-map-pin mr-1 text-orange-500"></i>
                                        {{ $report->parish }}
                                    </span>
                                </div>
                            </td>

                            <!-- Cantidad -->
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-lg bg-blue-100 px-3 py-1 text-sm font-bold text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $report->quantity }}
                                </span>
                            </td>

                            <!-- Fecha Entrega -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="text-sm text-gray-900 dark:text-white">
                                    {{ $report->delivery_date->format('d/m/Y') }}
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                @if($report->status === 'delivered')
                                <span class="inline-flex items-center rounded-full bg-gradient-to-r from-green-500 to-green-600 px-3 py-1 text-xs font-bold text-white shadow-md">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Entregado
                                </span>
                                @elseif($report->status === 'in_process')
                                <span class="inline-flex items-center rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 px-3 py-1 text-xs font-bold text-white shadow-md">
                                    <i class="fas fa-clock mr-1"></i>
                                    En Proceso
                                </span>
                                @else
                                <span class="inline-flex items-center rounded-full bg-gradient-to-r from-red-500 to-red-600 px-3 py-1 text-xs font-bold text-white shadow-md">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    No Entregado
                                </span>
                                @endif
                            </td>

                            <!-- Categoría -->
                            <td class="px-6 py-4">
                                @php
                                    $categories = $report->items->pluck('product.category')->unique('id');
                                @endphp
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($categories as $category)
                                    <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-2.5 py-1 text-xs font-bold text-white shadow-sm">
                                        <i class="{{ $category->icon }} mr-1.5"></i>
                                        {{ $category->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Acciones -->
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="/admin/reports/{{ $report->id }}"
                                        wire:navigate
                                        class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver Detalle
                                    </a>
                                    
                                    @if($report->status !== 'delivered')
                                    <!-- Botón Marcar como Entregado - DESTACADO -->
                                    <button
                                        type="button"
                                        onclick="confirmDelivery({{ $report->id }})"
                                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg transition-all duration-200 hover:from-green-600 hover:to-green-700 hover:shadow-xl hover:scale-105 active:scale-95 border-2 border-green-400 hover:border-green-300">
                                        <i class="fas fa-check-circle text-lg"></i>
                                        <span>✅ Entregado</span>
                                    </button>
                                    
                                    <!-- Botón Editar -->
                                    <a
                                        href="/admin/reports/{{ $report->id }}/edit"
                                        wire:navigate
                                        class="inline-flex items-center rounded-md bg-orange-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-700">
                                        <i class="fas fa-edit mr-1"></i>
                                        Editar
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox mb-4 text-6xl text-gray-400"></i>
                                <p class="text-gray-500 dark:text-gray-400">
                                    No hay reportes registrados todavía
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900">
                {{ $reports->links() }}
            </div>
        </div>

    </x-container>
</div>

@push('scripts')
<script>
function confirmDelivery(reportId) {
    Swal.fire({
        title: '¿Marcar como Entregado?',
        html: `
            <div class="text-left space-y-3">
                <p class="text-gray-600">Esta acción:</p>
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                    <li>Marcará el reporte como <strong class="text-green-600">ENTREGADO</strong></li>
                    <li>Registrará la <strong>salida de inventario</strong></li>
                    <li>Es <strong class="text-red-600">IRREVERSIBLE</strong></li>
                </ul>
                <p class="text-sm text-amber-600 mt-3">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    No podrá editar el reporte después de confirmar
                </p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-check-circle mr-2"></i>Sí, marcar como Entregado',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl shadow-2xl',
            title: 'text-xl font-bold',
            htmlContainer: 'text-left',
            confirmButton: 'px-6 py-3 rounded-lg font-semibold',
            cancelButton: 'px-6 py-3 rounded-lg font-semibold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Procesando...',
                text: 'Registrando entrega y actualizando inventario',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Llamar al método Livewire
            @this.call('markAsDelivered', reportId);
        }
    });
}

// Escuchar evento de SweetAlert desde Livewire
document.addEventListener('livewire:init', () => {
    Livewire.on('showAlert', (data) => {
        Swal.fire({
            icon: data[0].icon,
            title: data[0].title,
            text: data[0].text,
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Entendido',
            customClass: {
                popup: 'rounded-xl shadow-2xl',
                confirmButton: 'px-6 py-3 rounded-lg font-semibold'
            }
        });
    });
});
</script>
@endpush
