<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $beneficiariosHoy;
    public $beneficiariosMes;
    public $totalBeneficiarios;
    public $totalProductos;
    public $productosStockBajo;
    public $productosAgotados;
    public $entradasPendientes;
    public $salidasMes;
    public $totalMovimientosAnio;
    public $productosTop;
    public $categoriasTop;
    public $desgloseProductos;
    public $productosAgotadosLista;
    public $productosStockBajoLista;
    public $mostrarDesglose = false;
    
    // Estadísticas de reportes
    public $totalReportes;
    public $reportesEsteMes;
    public $reportesHoy;

    public function mount()
    {
        try {
            // Beneficiarios del día (de la tabla beneficiaries)
            $this->beneficiariosHoy = Beneficiary::whereDate('created_at', today())->count();

            // Beneficiarios del mes
            $this->beneficiariosMes = Beneficiary::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Total beneficiarios registrados
            $this->totalBeneficiarios = Beneficiary::count();

            // Total productos
            $this->totalProductos = Product::count();

            // Desglose de productos por categoría (optimizado)
            $this->desgloseProductos = DB::table('categories')
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->select(
                    'categories.id as categoria_id',
                    'categories.name as categoria',
                    DB::raw('COUNT(products.id) as total')
                )
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total', 'desc')
                ->get();

            // Productos con stock REAL (suma de entradas - salidas)
            $productosConStock = DB::table('products')
                ->leftJoin('inventories', 'products.id', '=', 'inventories.product_id')
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('COALESCE(SUM(inventories.quantity_in), 0) - COALESCE(SUM(inventories.quantity_out), 0) as total_stock')
                )
                ->groupBy('products.id', 'products.name')
                ->get();

            // Productos con stock bajo (entre 1 y 9 unidades)
            $this->productosStockBajoLista = $productosConStock->filter(function($producto) {
                return $producto->total_stock > 0 && $producto->total_stock < 10;
            });
            $this->productosStockBajo = $this->productosStockBajoLista->count();

            // Productos agotados (0 unidades o sin inventario)
            $this->productosAgotadosLista = $productosConStock->filter(function($producto) {
                return $producto->total_stock <= 0;
            });
            $this->productosAgotados = $this->productosAgotadosLista->count();

            // Entradas del mes (registros de inventario con quantity_in > 0)
            $this->entradasPendientes = Inventory::where('quantity_in', '>', 0)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Salidas del mes (registros de inventario con quantity_out > 0)
            $this->salidasMes = Inventory::where('quantity_out', '>', 0)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Total movimientos del año (todos los registros de inventario)
            $this->totalMovimientosAnio = Inventory::whereYear('created_at', now()->year)->count();

            // Top 5 productos más distribuidos (optimizado)
            $this->productosTop = DB::table('inventories')
                ->join('products', 'inventories.product_id', '=', 'products.id')
                ->where('inventories.quantity_out', '>', 0)
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('COUNT(*) as movimientos_count'),
                    DB::raw('SUM(inventories.quantity_out) as total_movido')
                )
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_movido')
                ->limit(5)
                ->get();

            // Top categorías con más entregas (optimizado)
            $this->categoriasTop = DB::table('inventories')
                ->join('products', 'inventories.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('inventories.quantity_out', '>', 0)
                ->select(
                    'categories.id',
                    'categories.name',
                    DB::raw('COUNT(*) as cantidad'),
                    DB::raw('SUM(inventories.quantity_out) as total_movimientos')
                )
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total_movimientos')
                ->limit(5)
                ->get();

            // Estadísticas de reportes
            $this->totalReportes = Report::count();
            $this->reportesEsteMes = Report::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $this->reportesHoy = Report::whereDate('created_at', today())->count();

        } catch (\Exception $e) {
            // Valores por defecto en caso de error
            $this->beneficiariosHoy = 0;
            $this->beneficiariosMes = 0;
            $this->totalBeneficiarios = 0;
            $this->totalProductos = 0;
            $this->productosStockBajo = 0;
            $this->productosAgotados = 0;
            $this->entradasPendientes = 0;
            $this->salidasMes = 0;
            $this->totalMovimientosAnio = 0;
            $this->productosTop = collect();
            $this->categoriasTop = collect();
            $this->desgloseProductos = collect();
            $this->productosAgotadosLista = collect();
            $this->productosStockBajoLista = collect();
            $this->totalReportes = 0;
            $this->reportesEsteMes = 0;
            $this->reportesHoy = 0;
            
            // Log del error para debugging
            \Log::error('Dashboard Error: ' . $e->getMessage());
        }
    }

    public function toggleDesglose()
    {
        $this->mostrarDesglose = !$this->mostrarDesglose;
    }
}; ?>

<div class="min-h-screen bg-gray-950">
    <x-container class="w-full px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Panel de Control</h1>
            <div class="text-gray-400 text-sm">
                <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Alertas -->
        <div class="space-y-3 mb-6">
            @if($productosAgotados > 0)
            <div class="bg-red-900/20 border border-red-500/50 rounded-lg p-4" x-data="{ open: false }">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        <span class="text-red-400 font-semibold">{{ $productosAgotados }} producto(s) agotado(s)</span>
                    </div>
                    <button type="button" class="text-red-400 hover:text-red-300 transition-transform" :class="{ 'rotate-180': open }">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                </div>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="mt-4 pt-4 border-t border-red-500/30">
                    <div class="space-y-2 max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-red-600 scrollbar-track-gray-800">
                        @foreach($productosAgotadosLista->take(10) as $producto)
                        <div class="flex items-center justify-between p-2 bg-red-950/30 rounded hover:bg-red-950/50 transition-colors">
                            <span class="text-gray-300 text-sm">{{ $producto->name }}</span>
                            <span class="text-red-400 text-xs font-semibold">Stock: {{ number_format($producto->total_stock) }}</span>
                        </div>
                        @endforeach
                        @if($productosAgotados > 10)
                        <p class="text-xs text-red-500 text-center pt-2">Y {{ $productosAgotados - 10 }} más...</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if($productosStockBajo > 0)
            <div class="bg-yellow-900/20 border border-yellow-500/50 rounded-lg p-4" x-data="{ open: false }">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-exclamation-circle text-yellow-500 text-xl"></i>
                        <span class="text-yellow-400 font-semibold">{{ $productosStockBajo }} producto(s) con stock bajo</span>
                    </div>
                    <button type="button" class="text-yellow-400 hover:text-yellow-300 transition-transform" :class="{ 'rotate-180': open }">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                </div>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="mt-4 pt-4 border-t border-yellow-500/30">
                    <div class="space-y-2 max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-yellow-600 scrollbar-track-gray-800">
                        @foreach($productosStockBajoLista->take(10) as $producto)
                        <div class="flex items-center justify-between p-2 bg-yellow-950/30 rounded hover:bg-yellow-950/50 transition-colors">
                            <span class="text-gray-300 text-sm">{{ $producto->name }}</span>
                            <span class="text-yellow-400 text-xs font-semibold">Stock: {{ number_format($producto->total_stock) }} unidades</span>
                        </div>
                        @endforeach
                        @if($productosStockBajo > 10)
                        <p class="text-xs text-yellow-500 text-center pt-2">Y {{ $productosStockBajo - 10 }} más...</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Métricas Principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Beneficiarios Hoy -->
            <div class="bg-gray-900 border-l-4 border-green-500 rounded-lg p-6 hover:bg-gray-850 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-sm font-medium">Beneficiarios Hoy</h3>
                    <i class="fas fa-user-plus text-green-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-green-400 mb-1">{{ $beneficiariosHoy }}</div>
                <p class="text-xs text-gray-500">Registros de hoy</p>
            </div>

            <!-- Beneficiarios Mes -->
            <div class="bg-gray-900 border-l-4 border-blue-500 rounded-lg p-6 hover:bg-gray-850 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-sm font-medium">Beneficiarios del Mes</h3>
                    <i class="fas fa-chart-line text-blue-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-blue-400 mb-1">{{ $beneficiariosMes }}</div>
                <p class="text-xs text-gray-500">Registros este mes</p>
            </div>

            <!-- Total Beneficiarios -->
            <div class="bg-gray-900 border-l-4 border-purple-500 rounded-lg p-6 hover:bg-gray-850 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-sm font-medium">Total Beneficiarios</h3>
                    <i class="fas fa-users text-purple-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-purple-400 mb-1">{{ $totalBeneficiarios }}</div>
                <p class="text-xs text-gray-500">Usuarios registrados</p>
            </div>

            <!-- Total Productos -->
            <div class="bg-gray-900 border-l-4 border-orange-500 rounded-lg p-6 hover:bg-gray-850 transition-colors cursor-pointer" wire:click="toggleDesglose">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-sm font-medium">Total Productos</h3>
                    <i class="fas fa-box text-orange-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-orange-400 mb-1">{{ $totalProductos }}</div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">{{ $productosStockBajo }} con stock bajo</p>
                    <button class="text-xs text-orange-400 hover:text-orange-300">
                        <i class="fas fa-chart-pie mr-1"></i>Ver desglose
                    </button>
                </div>
            </div>
        </div>

        <!-- Métricas Secundarias -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Productos Agotados -->
            @if(Route::has('products.index'))
            <a href="{{ route('products.index') }}" wire:navigate class="block">
            @endif
                <div class="bg-gray-900 border-l-4 border-red-500 rounded-lg p-6 hover:bg-gray-850 transition-colors hover:scale-105 transform duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400 text-sm font-medium">Productos Agotados</h3>
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-red-400 mb-1">{{ $productosAgotados }}</div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Requieren reabastecimiento</p>
                        @if(Route::has('products.index'))
                        <i class="fas fa-arrow-right text-red-400 text-xs"></i>
                        @endif
                    </div>
                </div>
            @if(Route::has('products.index'))
            </a>
            @endif

            <!-- Entradas del Mes -->
            @if(Route::has('inventory-entries.index'))
            <a href="{{ route('inventory-entries.index') }}" wire:navigate class="block" title="Ver todas las entradas de inventario">
            @endif
                <div class="bg-gray-900 border-l-4 border-yellow-500 rounded-lg p-6 hover:bg-gray-850 transition-colors hover:scale-105 transform duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400 text-sm font-medium">Entradas del Mes</h3>
                        <i class="fas fa-arrow-circle-down text-yellow-500 text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-yellow-400 mb-1">{{ $entradasPendientes }}</div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Registros de entrada al inventario</p>
                        @if(Route::has('inventory-entries.index'))
                        <i class="fas fa-arrow-right text-yellow-400 text-xs"></i>
                        @endif
                    </div>
                </div>
            @if(Route::has('inventory-entries.index'))
            </a>
            @endif

            <!-- Salidas del Mes -->
            @if(Route::has('inventory-exits.index'))
            <a href="{{ route('inventory-exits.index') }}" wire:navigate class="block" title="Ver todas las salidas de inventario">
            @endif
                <div class="bg-gray-900 border-l-4 border-green-500 rounded-lg p-6 hover:bg-gray-850 transition-colors hover:scale-105 transform duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400 text-sm font-medium">Salidas del Mes</h3>
                        <i class="fas fa-arrow-circle-up text-green-500 text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-green-400 mb-1">{{ $salidasMes }}</div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Distribuciones de inventario</p>
                        @if(Route::has('inventory-exits.index'))
                        <i class="fas fa-arrow-right text-green-400 text-xs"></i>
                        @endif
                    </div>
                </div>
            @if(Route::has('inventory-exits.index'))
            </a>
            @endif

            <!-- Movimientos del Año -->
            @if(Route::has('inventory-entries.index'))
            <a href="{{ route('inventory-entries.index') }}" wire:navigate class="block" title="Ver todos los movimientos de inventario">
            @endif
                <div class="bg-gray-900 border-l-4 border-blue-500 rounded-lg p-6 hover:bg-gray-850 transition-colors hover:scale-105 transform duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400 text-sm font-medium">Movimientos del Año</h3>
                        <i class="fas fa-chart-bar text-blue-500 text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-blue-400 mb-1">{{ $totalMovimientosAnio }}</div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Entradas + Salidas {{ now()->year }}</p>
                        @if(Route::has('inventory-entries.index'))
                        <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                        @endif
                    </div>
                </div>
            @if(Route::has('inventory-entries.index'))
            </a>
            @endif

            <!-- Reportes de Entregas -->
            @if(Route::has('admin.reports.index'))
            <a href="{{ route('admin.reports.index') }}" wire:navigate class="block" title="Ver todos los reportes de entregas">
            @endif
                <div class="bg-gray-900 border-l-4 border-indigo-500 rounded-lg p-6 hover:bg-gray-850 transition-colors hover:scale-105 transform duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400 text-sm font-medium">Reportes Este Mes</h3>
                        <i class="fas fa-file-alt text-indigo-500 text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-indigo-400 mb-1">{{ $reportesEsteMes }}</div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Total: {{ $totalReportes }} reportes</p>
                        @if(Route::has('admin.reports.index'))
                        <i class="fas fa-arrow-right text-indigo-400 text-xs"></i>
                        @endif
                    </div>
                </div>
            @if(Route::has('admin.reports.index'))
            </a>
            @endif
        </div>

        <!-- Desglose de Productos por Categoría -->
        @if($mostrarDesglose)
        <div class="mb-6 animate-fade-in">
            <div class="bg-gray-900 rounded-lg p-6 border border-orange-500">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-chart-pie text-orange-500 mr-2"></i>
                        Desglose de Productos por Categoría
                    </h2>
                    <button wire:click="toggleDesglose" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($desgloseProductos as $desglose)
                    <div class="bg-gray-800 rounded-lg p-4 border border-gray-700 hover:border-orange-500 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-white font-semibold text-lg">{{ $desglose->categoria }}</h3>
                                <p class="text-gray-400 text-xs">Categoría</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-orange-600/20 flex items-center justify-center">
                                <i class="fas fa-boxes text-orange-500"></i>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Total de Productos</p>
                                <p class="text-3xl font-bold text-white">{{ $desglose->total }}</p>
                            </div>
                            @php
                                $porcentaje = $totalProductos > 0 ? ($desglose->total / $totalProductos) * 100 : 0;
                            @endphp
                            <div class="text-right">
                                <div class="text-2xl font-bold text-orange-400">{{ number_format($porcentaje, 1) }}%</div>
                                <p class="text-xs text-gray-500">del total</p>
                            </div>
                        </div>
                        
                        <div class="w-full bg-gray-700 rounded-full h-2 mb-3">
                            <div class="bg-gradient-to-r from-orange-500 to-orange-400 h-2 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                        </div>
                        
                        @if(Route::has('products.index'))
                        <a href="{{ route('products.index') }}" wire:navigate 
                           class="block w-full bg-orange-600/20 hover:bg-orange-600/30 text-orange-400 hover:text-orange-300 py-2 px-4 rounded-lg text-center text-sm font-medium transition-colors border border-orange-600/30 hover:border-orange-500">
                            <i class="fas fa-eye mr-2"></i>Ver Productos de {{ $desglose->categoria }}
                        </a>
                        @endif
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-box-open text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-500">No hay productos registrados</p>
                    </div>
                    @endforelse
                </div>

                <!-- Resumen Total -->
                <div class="mt-6 pt-4 border-t border-gray-700">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Total Productos</p>
                            <p class="text-2xl font-bold text-orange-400">{{ $totalProductos }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Stock Bajo</p>
                            <p class="text-2xl font-bold text-yellow-400">{{ $productosStockBajo }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Agotados</p>
                            <p class="text-2xl font-bold text-red-400">{{ $productosAgotados }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- GRÁFICOS DE TORTA -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Gráfico 1: Productos Más Entregados -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="text-center mb-4">
                    <h2 class="text-xl font-bold text-white flex items-center justify-center gap-2">
                        <i class="fas fa-chart-pie text-blue-500"></i>
                        Productos Más Entregados
                    </h2>
                    <p class="text-gray-500 text-sm mt-2">Top 5 productos distribuidos</p>
                </div>
                
                <div class="flex justify-center items-center" style="height: 400px;" wire:ignore>
                    <canvas id="chartProductos" width="400" height="400"></canvas>
                </div>
            </div>

            <!-- Gráfico 2: Categorías Más Utilizadas -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="text-center mb-4">
                    <h2 class="text-xl font-bold text-white flex items-center justify-center gap-2">
                        <i class="fas fa-chart-pie text-purple-500"></i>
                        Categorías Más Utilizadas
                    </h2>
                    <p class="text-gray-500 text-sm mt-2">Distribución por categorías</p>
                </div>
                
                <div class="flex justify-center items-center" style="height: 400px;" wire:ignore>
                    <canvas id="chartCategorias" width="400" height="400"></canvas>
                </div>
            </div>
        </div>

        <!-- Tablas Detalladas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tabla Productos -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h3 class="text-lg font-bold text-white mb-4">Detalle de Productos</h3>
                <div class="space-y-3">
                    @forelse($productosTop as $index => $producto)
                    @if(Route::has('inventory-exits.index'))
                    <a href="{{ route('inventory-exits.index') }}" wire:navigate class="block" title="Ver movimientos de {{ $producto->name }}">
                    @endif
                        <div class="p-3 bg-gray-800 rounded-lg hover:bg-gray-750 hover:border-l-4 hover:border-blue-500 transition-all cursor-pointer group">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <span class="text-gray-300 font-medium group-hover:text-white transition-colors">{{ $producto->name }}</span>
                                        <p class="text-xs text-gray-500">{{ $producto->movimientos_count }} transacciones</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <p class="bg-blue-900/50 text-blue-400 px-3 py-1 rounded-full text-sm font-semibold group-hover:bg-blue-800 transition-colors">
                                            {{ number_format($producto->total_movido ?? 0) }} <i class="fas fa-boxes ml-1 text-xs"></i>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">unidades entregadas</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-600 group-hover:text-blue-400 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    @if(Route::has('inventory-exits.index'))
                    </a>
                    @endif
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-box-open text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-500">No hay datos disponibles</p>
                        <p class="text-xs text-gray-600 mt-1">Los datos aparecerán cuando haya movimientos</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Tabla Categorías -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h3 class="text-lg font-bold text-white mb-4">Detalle de Categorías</h3>
                <div class="space-y-3">
                    @forelse($categoriasTop as $index => $categoria)
                    @if(Route::has('inventory-exits.index'))
                    <a href="{{ route('inventory-exits.index') }}" wire:navigate class="block" title="Ver entregas de {{ $categoria->name }}">
                    @endif
                        <div class="p-3 bg-gray-800 rounded-lg hover:bg-gray-750 hover:border-l-4 hover:border-purple-500 transition-all cursor-pointer group">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-purple-800 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <span class="text-gray-300 font-medium group-hover:text-white transition-colors">{{ $categoria->name }}</span>
                                        <p class="text-xs text-gray-500">{{ $categoria->cantidad }} transacciones</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <span class="bg-purple-900/50 text-purple-400 px-3 py-1 rounded-full text-sm font-semibold group-hover:bg-purple-800 transition-colors">
                                            {{ number_format($categoria->total_movimientos ?? 0) }} <i class="fas fa-boxes ml-1 text-xs"></i>
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">unidades distribuidas</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-600 group-hover:text-purple-400 text-xs"></i>
                                </div>
                            </div>
                            <!-- Barra de progreso -->
                            @php
                                $maxCantidad = $categoriasTop->max('cantidad');
                                $porcentaje = $maxCantidad > 0 ? ($categoria->cantidad / $maxCantidad) * 100 : 0;
                            @endphp
                            <div class="w-full bg-gray-700 rounded-full h-1.5">
                                <div class="bg-gradient-to-r from-purple-600 to-purple-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                            </div>
                        </div>
                    @if(Route::has('inventory-exits.index'))
                    </a>
                    @endif
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-layer-group text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-500">No hay datos disponibles</p>
                        <p class="text-xs text-gray-600 mt-1">Las categorías aparecerán cuando haya movimientos</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </x-container>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variables globales para los gráficos
let chartProductos = null;
let chartCategorias = null;

function initCharts() {
    // Datos desde PHP (ya codificados correctamente)
    const productosData = {!! json_encode([
        'labels' => $productosTop->pluck('name')->toArray(),
        'values' => $productosTop->pluck('total_movido')->toArray()
    ]) !!};
    
    const categoriasData = {!! json_encode([
        'labels' => $categoriasTop->pluck('name')->toArray(),
        'values' => $categoriasTop->pluck('total_movimientos')->toArray()
    ]) !!};
    
    // Destruir gráficos existentes si los hay
    if (chartProductos) {
        chartProductos.destroy();
    }
    if (chartCategorias) {
        chartCategorias.destroy();
    }
    
    // GRÁFICO 1: PRODUCTOS - Colores MUY DIFERENCIADOS
    const ctx1 = document.getElementById('chartProductos');
    if (ctx1 && productosData.labels.length > 0) {
        chartProductos = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: productosData.labels,
                datasets: [{
                    data: productosData.values,
                    backgroundColor: [
                        '#3B82F6',  // Azul brillante
                        '#10B981',  // Verde esmeralda
                        '#F59E0B',  // Naranja ámbar
                        '#EF4444',  // Rojo coral
                        '#8B5CF6'   // Morado violeta
                    ],
                    borderWidth: 3,
                    borderColor: '#1F2937',
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { 
                            color: '#9CA3AF', 
                            padding: 15, 
                            font: { size: 12, weight: '500' },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleColor: '#FFFFFF',
                        bodyColor: '#D1D5DB',
                        borderColor: '#374151',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return ` ${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        console.log('✅ Gráfico productos creado');
    }
    
    // GRÁFICO 2: CATEGORÍAS - Colores MUY DIFERENCIADOS
    const ctx2 = document.getElementById('chartCategorias');
    if (ctx2 && categoriasData.labels.length > 0) {
        chartCategorias = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: categoriasData.labels,
                datasets: [{
                    data: categoriasData.values,
                    backgroundColor: [
                        '#A855F7',  // Morado fucsia
                        '#14B8A6',  // Cyan turquesa
                        '#F97316',  // Naranja fuerte
                        '#EC4899',  // Rosa pink
                        '#84CC16'   // Lima verde
                    ],
                    borderWidth: 3,
                    borderColor: '#1F2937',
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { 
                            color: '#9CA3AF', 
                            padding: 15, 
                            font: { size: 12, weight: '500' },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleColor: '#FFFFFF',
                        bodyColor: '#D1D5DB',
                        borderColor: '#374151',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return ` ${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        console.log('✅ Gráfico categorías creado');
    }
}

// Inicializar gráficos en diferentes eventos para asegurar que siempre se muestren
document.addEventListener('DOMContentLoaded', initCharts);
document.addEventListener('livewire:navigated', initCharts);
window.addEventListener('load', initCharts);
</script>
@endpush
