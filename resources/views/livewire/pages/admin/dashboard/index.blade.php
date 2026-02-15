<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;

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

    // Datos mensuales para gráficas
    public $mesesBeneficiarios = [];
    public $datosBeneficiariosMensuales = [];

    public $mesesMovimientos = [];
    public $datosEntradas = [];
    public $datosSalidas = [];

    public function mount()
    {
        try {
            $this->beneficiariosHoy = Beneficiary::whereDate('created_at', today())->count();
            $this->beneficiariosMes = Beneficiary::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count();
            $this->totalBeneficiarios = Beneficiary::count();
            $this->totalProductos = Product::count();

            $this->desgloseProductos = DB::table('categories')
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->select('categories.id as categoria_id', 'categories.name as categoria', DB::raw('COUNT(products.id) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total', 'desc')->get();

            $productosConStock = DB::table('products')
                ->leftJoin('inventories', 'products.id', '=', 'inventories.product_id')
                ->select('products.id', 'products.name',
                    DB::raw('COALESCE(SUM(inventories.quantity_in), 0) - COALESCE(SUM(inventories.quantity_out), 0) as total_stock'))
                ->groupBy('products.id', 'products.name')->get();

            $this->productosStockBajoLista = $productosConStock->filter(fn($p) => $p->total_stock > 0 && $p->total_stock < 10);
            $this->productosStockBajo = $this->productosStockBajoLista->count();
            $this->productosAgotadosLista = $productosConStock->filter(fn($p) => $p->total_stock <= 0);
            $this->productosAgotados = $this->productosAgotadosLista->count();

            $this->entradasPendientes = Inventory::where('quantity_in', '>', 0)
                ->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $this->salidasMes = Inventory::where('quantity_out', '>', 0)
                ->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $this->totalMovimientosAnio = Inventory::whereYear('created_at', now()->year)->count();

            $this->productosTop = DB::table('inventories')
                ->join('products', 'inventories.product_id', '=', 'products.id')
                ->where('inventories.quantity_out', '>', 0)
                ->select('products.id', 'products.name', DB::raw('COUNT(*) as movimientos_count'), DB::raw('SUM(inventories.quantity_out) as total_movido'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_movido')->limit(5)->get();

            $this->categoriasTop = DB::table('inventories')
                ->join('products', 'inventories.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('inventories.quantity_out', '>', 0)
                ->select('categories.id', 'categories.name', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(inventories.quantity_out) as total_movimientos'))
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total_movimientos')->limit(5)->get();

            $this->totalReportes = Report::count();
            $this->reportesEsteMes = Report::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $this->reportesHoy = Report::whereDate('created_at', today())->count();

            // Cargar datos mensuales para gráficas
            $this->cargarBeneficiariosMensuales();
            $this->cargarMovimientosMensuales();

        } catch (\Exception $e) {
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
            \Log::error('Dashboard Error: ' . $e->getMessage());
        }
    }

    public function cargarBeneficiariosMensuales()
    {
        $this->mesesBeneficiarios = [];
        $this->datosBeneficiariosMensuales = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $this->mesesBeneficiarios[] = ucfirst($fecha->translatedFormat('M'));
            $this->datosBeneficiariosMensuales[] = Beneficiary::whereMonth('created_at', $fecha->month)
                ->whereYear('created_at', $fecha->year)->count();
        }
    }

    public function cargarMovimientosMensuales()
    {
        $this->mesesMovimientos = [];
        $this->datosEntradas = [];
        $this->datosSalidas = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $this->mesesMovimientos[] = ucfirst($fecha->translatedFormat('M'));

            $this->datosEntradas[] = Inventory::where('quantity_in', '>', 0)
                ->whereMonth('created_at', $fecha->month)
                ->whereYear('created_at', $fecha->year)->count();

            $this->datosSalidas[] = Inventory::where('quantity_out', '>', 0)
                ->whereMonth('created_at', $fecha->month)
                ->whereYear('created_at', $fecha->year)->count();
        }
    }

    public function toggleDesglose()
    {
        $this->mostrarDesglose = !$this->mostrarDesglose;
    }

    public function refreshData()
    {
        $this->mount();
    }
}; ?>

<div class="mt-6 page-enter">
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Panel de Control'],
        ]" />
    </x-slot>

    <x-container class="w-full px-4 sm:px-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Panel de Control</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Resumen general del sistema de gestión</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-gray-400 dark:text-gray-500 text-sm">
                    <i class="fas fa-calendar-alt mr-1"></i>{{ now()->format('d/m/Y H:i') }}
                </span>
                <button wire:click="refreshData" class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors" title="Actualizar datos">
                    <i class="fas fa-sync-alt" wire:loading.class="animate-spin" wire:target="refreshData"></i>
                </button>
            </div>
        </div>

        {{-- ===== TARJETAS PRINCIPALES ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">

            {{-- Beneficiarios Hoy --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-5 sm:p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Beneficiarios Hoy</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $beneficiariosHoy }}</h3>
                    <p class="text-green-500 text-xs mt-2 flex items-center gap-1">
                        <i class="fas fa-user-plus"></i> Registros de hoy
                    </p>
                </div>
                <div class="p-3 sm:p-4 bg-green-100 dark:bg-green-900/30 rounded-xl">
                    <i class="fas fa-user-plus text-2xl sm:text-3xl text-green-500 dark:text-green-400"></i>
                </div>
            </div>

            {{-- Beneficiarios del Mes --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-5 sm:p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Beneficiarios del Mes</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $beneficiariosMes }}</h3>
                    <p class="text-blue-500 text-xs mt-2 flex items-center gap-1">
                        <i class="fas fa-chart-line"></i> Este mes
                    </p>
                </div>
                <div class="p-3 sm:p-4 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                    <i class="fas fa-chart-line text-2xl sm:text-3xl text-blue-500 dark:text-blue-400"></i>
                </div>
            </div>

            {{-- Total Beneficiarios --}}
            <a href="{{ route('admin.beneficiaries.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-5 sm:p-6 flex items-center justify-between border-l-4 border-purple-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Total Beneficiarios</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBeneficiarios }}</h3>
                        <p class="text-purple-500 text-xs mt-2 flex items-center gap-1">
                            <i class="fas fa-users"></i> Registrados
                        </p>
                    </div>
                    <div class="p-3 sm:p-4 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                        <i class="fas fa-users text-2xl sm:text-3xl text-purple-500 dark:text-purple-400"></i>
                    </div>
                </div>
            </a>

            {{-- Total Productos --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-5 sm:p-6 flex items-center justify-between border-l-4 border-orange-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer" wire:click="toggleDesglose">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Total Productos</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProductos }}</h3>
                    <p class="text-orange-500 text-xs mt-2 flex items-center gap-1">
                        <i class="fas fa-chart-pie"></i> Ver desglose
                    </p>
                </div>
                <div class="p-3 sm:p-4 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                    <i class="fas fa-box text-2xl sm:text-3xl text-orange-500 dark:text-orange-400"></i>
                </div>
            </div>
        </div>

        {{-- ===== ALERTAS ===== --}}
        <div class="space-y-3 mb-6">
            @if($productosAgotados > 0)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500/50 rounded-xl p-4" x-data="{ open: false }">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-red-100 dark:bg-red-900/40 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                        </div>
                        <span class="text-red-700 dark:text-red-400 font-semibold">{{ $productosAgotados }} producto(s) agotado(s)</span>
                    </div>
                    <i class="fas fa-chevron-down text-red-400 text-sm transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </div>
                <div x-show="open" x-transition class="mt-4 pt-4 border-t border-red-200 dark:border-red-500/30">
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        @foreach($productosAgotadosLista->take(10) as $producto)
                        <div class="flex items-center justify-between p-2 bg-red-100/50 dark:bg-red-950/30 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $producto->name }}</span>
                            <span class="text-red-600 dark:text-red-400 text-xs font-semibold">Stock: {{ number_format($producto->total_stock) }}</span>
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
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-500/50 rounded-xl p-4" x-data="{ open: false }">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900/40 rounded-lg">
                            <i class="fas fa-exclamation-circle text-yellow-500 text-lg"></i>
                        </div>
                        <span class="text-yellow-700 dark:text-yellow-400 font-semibold">{{ $productosStockBajo }} producto(s) con stock bajo</span>
                    </div>
                    <i class="fas fa-chevron-down text-yellow-400 text-sm transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </div>
                <div x-show="open" x-transition class="mt-4 pt-4 border-t border-yellow-200 dark:border-yellow-500/30">
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        @foreach($productosStockBajoLista->take(10) as $producto)
                        <div class="flex items-center justify-between p-2 bg-yellow-100/50 dark:bg-yellow-950/30 rounded-lg">
                            <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $producto->name }}</span>
                            <span class="text-yellow-600 dark:text-yellow-400 text-xs font-semibold">Stock: {{ number_format($producto->total_stock) }} uds</span>
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

        {{-- ===== TARJETAS SECUNDARIAS ===== --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            {{-- Agotados --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 sm:p-5 border-l-4 border-red-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $productosAgotados }}</div>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Agotados</p>
            </div>

            {{-- Entradas --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 sm:p-5 border-l-4 border-yellow-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-arrow-circle-down text-yellow-500 text-lg"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $entradasPendientes }}</div>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Entradas del Mes</p>
            </div>

            {{-- Salidas --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 sm:p-5 border-l-4 border-emerald-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-arrow-circle-up text-emerald-500 text-lg"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $salidasMes }}</div>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Salidas del Mes</p>
            </div>

            {{-- Movimientos del Año --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 sm:p-5 border-l-4 border-cyan-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-chart-bar text-cyan-500 text-lg"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalMovimientosAnio }}</div>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Movimientos {{ now()->year }}</p>
            </div>

            {{-- Reportes --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4 sm:p-5 border-l-4 border-indigo-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-file-alt text-indigo-500 text-lg"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reportesEsteMes }}</div>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Reportes del Mes</p>
            </div>
        </div>

        {{-- ===== DESGLOSE DE PRODUCTOS POR CATEGORÍA ===== --}}
        @if($mostrarDesglose)
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-orange-200 dark:border-orange-500/30">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-chart-pie text-orange-500 mr-2"></i>
                        Desglose por Categoría
                    </h2>
                    <button wire:click="toggleDesglose" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($desgloseProductos as $desglose)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600 hover:border-orange-300 dark:hover:border-orange-500/50 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 dark:text-white font-semibold">{{ $desglose->categoria }}</h3>
                            <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                <i class="fas fa-boxes text-orange-500"></i>
                            </div>
                        </div>
                        @php $porcentaje = $totalProductos > 0 ? ($desglose->total / $totalProductos) * 100 : 0; @endphp
                        <div class="flex items-end justify-between mb-3">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $desglose->total }}</p>
                            <span class="text-orange-500 font-bold">{{ number_format($porcentaje, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-gradient-to-r from-orange-500 to-orange-400 h-2 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-box-open text-gray-300 dark:text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No hay productos registrados</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        {{-- ===== GRÁFICAS ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Gráfica de Línea: Beneficiarios Mensuales --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-500"></i>
                            Beneficiarios Mensuales
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Últimos 12 meses</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ array_sum($datosBeneficiariosMensuales) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total período</p>
                    </div>
                </div>
                <div class="flex justify-center items-center" style="height: 300px;" wire:ignore>
                    <canvas id="lineChart" width="400" height="300"></canvas>
                </div>
            </div>

            {{-- Gráfica de Barras: Movimientos de Inventario --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-chart-bar text-emerald-500"></i>
                            Movimientos de Inventario
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Entradas vs Salidas (12 meses)</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ array_sum($datosEntradas) + array_sum($datosSalidas) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total movimientos</p>
                    </div>
                </div>
                <div class="flex justify-center items-center" style="height: 300px;" wire:ignore>
                    <canvas id="barChart" width="400" height="300"></canvas>
                </div>
            </div>

            {{-- Gráfica de Torta: Productos Más Entregados --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center justify-center gap-2">
                        <i class="fas fa-chart-pie text-purple-500"></i>
                        Productos Más Entregados
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Top 5 productos distribuidos</p>
                </div>
                <div class="flex justify-center items-center" style="height: 320px;" wire:ignore>
                    <canvas id="chartProductos" width="400" height="320"></canvas>
                </div>
            </div>

            {{-- Gráfica Doughnut: Categorías Más Utilizadas --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center justify-center gap-2">
                        <i class="fas fa-chart-pie text-amber-500"></i>
                        Categorías Más Utilizadas
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Distribución por categorías</p>
                </div>
                <div class="flex justify-center items-center" style="height: 320px;" wire:ignore>
                    <canvas id="chartCategorias" width="400" height="320"></canvas>
                </div>
            </div>
        </div>

        {{-- ===== TABLAS DETALLADAS ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Tabla Productos --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-trophy text-blue-500"></i> Top Productos Distribuidos
                </h3>
                <div class="space-y-3">
                    @forelse($productosTop as $index => $producto)
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $producto->name }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $producto->movimientos_count }} transacciones</p>
                                </div>
                            </div>
                            <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ number_format($producto->total_movido ?? 0) }} uds
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-box-open text-gray-300 dark:text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No hay datos disponibles</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Tabla Categorías --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-layer-group text-purple-500"></i> Top Categorías Distribuidas
                </h3>
                <div class="space-y-3">
                    @forelse($categoriasTop as $index => $categoria)
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $categoria->name }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoria->cantidad }} transacciones</p>
                                </div>
                            </div>
                            <span class="bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ number_format($categoria->total_movimientos ?? 0) }} uds
                            </span>
                        </div>
                        @php
                            $maxCantidad = $categoriasTop->max('cantidad');
                            $porcentaje = $maxCantidad > 0 ? ($categoria->cantidad / $maxCantidad) * 100 : 0;
                        @endphp
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-layer-group text-gray-300 dark:text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No hay datos disponibles</p>
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
let chartLine = null, chartBar = null, chartProductos = null, chartCategorias = null;

function initAllCharts() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
    const textColor = isDark ? '#9CA3AF' : '#6B7280';

    // ========== GRÁFICA DE LÍNEA ==========
    const ctxLine = document.getElementById('lineChart');
    if (ctxLine) {
        if (chartLine) chartLine.destroy();
        const mesesB = @json($mesesBeneficiarios);
        const datosB = @json($datosBeneficiariosMensuales);
        chartLine = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: mesesB,
                datasets: [{
                    label: 'Beneficiarios',
                    data: datosB,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                    x: { grid: { display: false }, ticks: { color: textColor } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: isDark ? '#1F2937' : '#fff', titleColor: isDark ? '#fff' : '#111', bodyColor: isDark ? '#D1D5DB' : '#374151', borderColor: isDark ? '#374151' : '#E5E7EB', borderWidth: 1, padding: 12 }
                }
            }
        });
    }

    // ========== GRÁFICA DE BARRAS ==========
    const ctxBar = document.getElementById('barChart');
    if (ctxBar) {
        if (chartBar) chartBar.destroy();
        const mesesM = @json($mesesMovimientos);
        const datosE = @json($datosEntradas);
        const datosS = @json($datosSalidas);
        chartBar = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: mesesM,
                datasets: [
                    { label: 'Entradas', data: datosE, backgroundColor: 'rgba(16, 185, 129, 0.8)', borderRadius: 6 },
                    { label: 'Salidas', data: datosS, backgroundColor: 'rgba(239, 68, 68, 0.8)', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                    x: { grid: { display: false }, ticks: { color: textColor } }
                },
                plugins: {
                    legend: { position: 'top', labels: { color: textColor, usePointStyle: true, pointStyle: 'circle', padding: 15 } },
                    tooltip: { backgroundColor: isDark ? '#1F2937' : '#fff', titleColor: isDark ? '#fff' : '#111', bodyColor: isDark ? '#D1D5DB' : '#374151', borderColor: isDark ? '#374151' : '#E5E7EB', borderWidth: 1, padding: 12 }
                }
            }
        });
    }

    // ========== GRÁFICA PIE: PRODUCTOS ==========
    const ctx1 = document.getElementById('chartProductos');
    const productosData = {!! json_encode(['labels' => $productosTop->pluck('name')->toArray(), 'values' => $productosTop->pluck('total_movido')->toArray()]) !!};
    if (ctx1 && productosData.labels.length > 0) {
        if (chartProductos) chartProductos.destroy();
        chartProductos = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: productosData.labels,
                datasets: [{ data: productosData.values, backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6'], borderWidth: 3, borderColor: isDark ? '#1F2937' : '#FFFFFF', hoverBorderWidth: 4, hoverBorderColor: '#FFFFFF' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor, padding: 15, font: { size: 12 }, usePointStyle: true, pointStyle: 'circle' } },
                    tooltip: { backgroundColor: isDark ? '#1F2937' : '#fff', titleColor: isDark ? '#fff' : '#111', bodyColor: isDark ? '#D1D5DB' : '#374151', borderColor: isDark ? '#374151' : '#E5E7EB', borderWidth: 1, padding: 12,
                        callbacks: { label: function(ctx) { const v = ctx.parsed || 0; const t = ctx.dataset.data.reduce((a,b)=>a+b,0); return ` ${ctx.label}: ${v} (${((v/t)*100).toFixed(1)}%)`; } }
                    }
                }
            }
        });
    }

    // ========== GRÁFICA DOUGHNUT: CATEGORÍAS ==========
    const ctx2 = document.getElementById('chartCategorias');
    const categoriasData = {!! json_encode(['labels' => $categoriasTop->pluck('name')->toArray(), 'values' => $categoriasTop->pluck('total_movimientos')->toArray()]) !!};
    if (ctx2 && categoriasData.labels.length > 0) {
        if (chartCategorias) chartCategorias.destroy();
        chartCategorias = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: categoriasData.labels,
                datasets: [{ data: categoriasData.values, backgroundColor: ['#A855F7','#14B8A6','#F97316','#EC4899','#84CC16'], borderWidth: 3, borderColor: isDark ? '#1F2937' : '#FFFFFF', hoverBorderWidth: 4, hoverBorderColor: '#FFFFFF' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '55%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor, padding: 15, font: { size: 12 }, usePointStyle: true, pointStyle: 'circle' } },
                    tooltip: { backgroundColor: isDark ? '#1F2937' : '#fff', titleColor: isDark ? '#fff' : '#111', bodyColor: isDark ? '#D1D5DB' : '#374151', borderColor: isDark ? '#374151' : '#E5E7EB', borderWidth: 1, padding: 12,
                        callbacks: { label: function(ctx) { const v = ctx.parsed || 0; const t = ctx.dataset.data.reduce((a,b)=>a+b,0); return ` ${ctx.label}: ${v} (${((v/t)*100).toFixed(1)}%)`; } }
                    }
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', initAllCharts);
document.addEventListener('livewire:navigated', initAllCharts);
</script>
@endpush
