<?php

use App\Models\Product;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new #[Layout('livewire.layout.admin.admin'), Title('Productos')] class extends Component {

    public $filterCategory = null;
    public $activeTab = 'all';

    #[Validate('required')]
    public $category_id = '';

    #[Validate('required|string|max:255')]
    public $name = '';
    
    // Propiedades para selector de productos existentes
    public $searchProduct = '';
    public $showProductList = false;
    public $selectedExistingProduct = null;
    public $productsFiltered = [];

    #[Validate('required')]
    public $unit_type = 'unidad';

    #[Validate('required|integer|min:0')]
    public $initial_quantity = 0;

    public $description = '';
    public $observation = '';

    public $showForm = false;
    public $showExitForm = false;
    public $exit_product_id = null;
    public $exit_quantity = 1;

    // Descripciones predefinidas por categoría
    public $categoryDescriptions = [
        'Medicamentos' => 'Medicamento para tratamiento médico',
        'Alimentos y Despensa' => 'Producto alimenticio para ayuda social',
        'Educación y Útiles' => 'Material educativo y útiles escolares',
        'Vivienda' => 'Material de construcción y mejora de vivienda',
        'Ayudas técnicas' => 'Ayuda técnica o dispositivo de apoyo social',
    ];

    public function mount()
    {
        $this->description = '';
        $categoryParam = request()->get('category');
        if ($categoryParam) {
            $this->filterCategory = $categoryParam;
            $this->activeTab = $categoryParam;
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'all') {
            $this->filterCategory = null;
        } else {
            $this->filterCategory = $tab;
        }
    }

    public function updatedCategoryId($value)
    {
        if ($value) {
            $category = Category::find($value);
            if ($category && isset($this->categoryDescriptions[$category->name])) {
                $this->description = $this->categoryDescriptions[$category->name];
            }
        }
        // Actualizar lista de productos filtrados cuando cambia la categoría
        $this->updateProductList();
    }
    
    public function updatedSearchProduct()
    {
        $this->updateProductList();
        $this->showProductList = strlen($this->searchProduct) > 0;
    }
    
    public function updateProductList()
    {
        if ($this->category_id && strlen($this->searchProduct) >= 0) {
            $this->productsFiltered = Product::where('category_id', $this->category_id)
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->searchProduct) . '%']);
                })
                ->orderBy('name')
                ->take(10)
                ->get();
        } else {
            $this->productsFiltered = [];
        }
    }
    
    public function selectExistingProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->selectedExistingProduct = $product;
            $this->name = $product->name;
            $this->searchProduct = $product->name;
            $this->unit_type = $product->unit_type;
            $this->description = $product->description;
            $this->observation = $product->observation;
            $this->showProductList = false;
        }
    }
    
    public function clearProductSelection()
    {
        $this->selectedExistingProduct = null;
        $this->name = '';
        $this->searchProduct = '';
        $this->showProductList = false;
    }

    public function save()
    {
        try {
            // Sincronizar el nombre desde el campo de búsqueda si no hay selección
            if (!$this->selectedExistingProduct && strlen($this->searchProduct) > 0) {
                $this->name = $this->searchProduct;
            }
            
            $this->validate();

            // Buscar si ya existe un producto con el MISMO nombre, categoría Y unidad (case-insensitive)
            $existingProduct = Product::whereRaw('LOWER(name) = ?', [strtolower($this->name)])
                ->where('category_id', $this->category_id)
                ->where('unit_type', $this->unit_type)
                ->first();

            if ($existingProduct) {
                // Si existe EXACTAMENTE el mismo producto (nombre + categoría + unidad), sumar al stock
                if ($this->initial_quantity > 0) {
                    $warehouse = \App\Models\Warehouse::first();
                    
                    // Obtener el último balance
                    $lastInventory = \App\Models\Inventory::where('product_id', $existingProduct->id)
                        ->where('warehouse_id', $warehouse->id)
                        ->latest()
                        ->first();

                    $previousBalance = $lastInventory ? $lastInventory->quantity_balance : 0;
                    $newBalance = $previousBalance + $this->initial_quantity;

                    \App\Models\Inventory::create([
                        'product_id' => $existingProduct->id,
                        'warehouse_id' => $warehouse->id,
                        'detail' => 'Adición de stock al producto existente',
                        'quantity_in' => $this->initial_quantity,
                        'quantity_out' => 0,
                        'quantity_balance' => $newBalance,
                        'cost_in' => 0,
                        'cost_out' => 0,
                        'total_in' => 0,
                        'total_out' => 0,
                        'cost_balance' => 0,
                        'total_balance' => 0,
                        'inventoryable_type' => 'App\Models\Product',
                        'inventoryable_id' => $existingProduct->id,
                    ]);
                }

                $this->dispatch('showAlert', [
                    'icon' => 'info',
                    'title' => '📦 Stock Actualizado',
                    'text' => 'El producto ya existe. Se agregaron ' . $this->initial_quantity . ' unidades al stock existente.',
                    'timer' => 4000,
                ]);
                
                $this->reset(['category_id', 'name', 'unit_type', 'initial_quantity', 'description', 'observation', 'showForm', 'searchProduct', 'selectedExistingProduct', 'showProductList', 'productsFiltered']);
                $this->dispatch('refreshTable');
                return;
            }

            // Verificar si existe el mismo nombre pero con DIFERENTE unidad
            $sameNameDifferentUnit = Product::whereRaw('LOWER(name) = ?', [strtolower($this->name)])
                ->where('category_id', $this->category_id)
                ->where('unit_type', '!=', $this->unit_type)
                ->get();

            $warningMessage = '';
            if ($sameNameDifferentUnit->count() > 0) {
                $units = $sameNameDifferentUnit->pluck('unit_type')->unique()->implode(', ');
                $warningMessage = "⚠️ NOTA: Ya existe '{$this->name}' en unidad(es): {$units}. Se registrará como producto SEPARADO en unidad: {$this->unit_type}";
            }

            // Crear el nuevo producto
            $product = Product::create([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'unit_type' => $this->unit_type,
                'description' => $this->description,
                'observation' => $this->observation,
            ]);

            if ($this->initial_quantity > 0) {
                $warehouse = \App\Models\Warehouse::first();
                
                \App\Models\Inventory::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'detail' => 'Stock inicial del producto',
                    'quantity_in' => $this->initial_quantity,
                    'quantity_out' => 0,
                    'quantity_balance' => $this->initial_quantity,
                    'cost_in' => 0,
                    'cost_out' => 0,
                    'total_in' => 0,
                    'total_out' => 0,
                    'cost_balance' => 0,
                    'total_balance' => 0,
                    'inventoryable_type' => 'App\Models\Product',
                    'inventoryable_id' => $product->id,
                ]);
            }

            // SweetAlert con mensaje especial si hay productos con el mismo nombre pero diferente unidad
            if ($warningMessage) {
                $this->dispatch('showAlert', [
                    'icon' => 'warning',
                    'title' => '✅ Producto Registrado',
                    'html' => "<strong>{$product->name}</strong> registrado exitosamente en unidad: <strong>{$product->unit_type}</strong><br><br>{$warningMessage}",
                    'timer' => 6000,
                ]);
            } else {
                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => '✅ Producto Registrado',
                    'text' => "'{$product->name}' registrado exitosamente en unidad: {$product->unit_type}",
                    'timer' => 3000,
                ]);
            }
            
            $this->reset(['category_id', 'name', 'unit_type', 'initial_quantity', 'description', 'observation', 'showForm', 'searchProduct', 'selectedExistingProduct', 'showProductList', 'productsFiltered']);
            $this->dispatch('refreshTable');
            
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo registrar el producto: ' . $e->getMessage(),
            ]);
            \Log::error('Error creating product: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);
            
            if (!$product) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Producto no encontrado',
                ]);
                return;
            }
            
            $productName = $product->name;
            $product->delete();
            
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '🗑️ Producto Eliminado',
                'text' => "'{$productName}' eliminado exitosamente",
                'timer' => 3000,
            ]);
            
            $this->dispatch('refreshTable');
            
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el producto: ' . $e->getMessage(),
            ]);
        }
    }

    public function openExitForm($productId)
    {
        $this->exit_product_id = $productId;
        $this->exit_quantity = 1;
        $this->showExitForm = true;
    }

    public function quickExit()
    {
        try {
            $this->validate([
                'exit_quantity' => 'required|integer|min:1',
            ], [
                'exit_quantity.required' => 'La cantidad es obligatoria',
                'exit_quantity.integer' => 'La cantidad debe ser un número entero',
                'exit_quantity.min' => 'La cantidad debe ser mayor a 0',
            ]);

            $product = Product::find($this->exit_product_id);
            if (!$product) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Producto no encontrado',
                ]);
                return;
            }

            $warehouse = \App\Models\Warehouse::first();
            
            $lastInventory = \App\Models\Inventory::where('product_id', $this->exit_product_id)
                ->where('warehouse_id', $warehouse->id)
                ->latest()
                ->first();

            $previousBalance = $lastInventory ? $lastInventory->quantity_balance : 0;
            
            if ($previousBalance < $this->exit_quantity) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => '⚠️ Stock Insuficiente',
                    'text' => "Stock actual: {$previousBalance} {$product->unit_type}. No se puede retirar {$this->exit_quantity} {$product->unit_type}",
                    'timer' => 5000,
                ]);
                return;
            }

            $newBalance = $previousBalance - $this->exit_quantity;

            \App\Models\Inventory::create([
                'product_id' => $this->exit_product_id,
                'warehouse_id' => $warehouse->id,
                'detail' => 'Salida rápida desde productos',
                'quantity_in' => 0,
                'quantity_out' => $this->exit_quantity,
                'quantity_balance' => $newBalance,
                'cost_in' => 0,
                'cost_out' => 0,
                'total_in' => 0,
                'total_out' => 0,
                'cost_balance' => 0,
                'total_balance' => 0,
                'inventoryable_type' => 'App\Models\Product',
                'inventoryable_id' => $this->exit_product_id,
            ]);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '📤 Salida Registrada',
                'text' => "Se retiraron {$this->exit_quantity} {$product->unit_type} de '{$product->name}'. Stock restante: {$newBalance}",
                'timer' => 4000,
            ]);

            $this->showExitForm = false;
            $this->exit_product_id = null;
            $this->exit_quantity = 1;
            $this->dispatch('refreshTable');
            
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo registrar la salida: ' . $e->getMessage(),
            ]);
        }
    }

    public function getStockForProduct($productId, $warehouseId = 1)
    {
        $inventory = \App\Models\Inventory::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->latest()
            ->first();
        
        return $inventory ? $inventory->quantity_balance : 0;
    }

    public function clearFilter()
    {
        $this->filterCategory = null;
    }

    public function with(): array
    {
        $query = Product::with('category');
        
        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }
        
        // Obtener conteos reales por categoría
        $categoryCounts = Product::select('category_id', \DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->pluck('total', 'category_id');
        
        return [
            'products' => $query->latest()->get(),
            'categories' => Category::where('is_active', true)->get(),
            'warehouse' => \App\Models\Warehouse::first(),
            'selectedCategory' => $this->filterCategory ? Category::find($this->filterCategory) : null,
            'totalProducts' => Product::count(),
            'categoryCounts' => $categoryCounts,
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Mensaje de éxito -->
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900 dark:text-green-200">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-200">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Header con botón -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium">Gestión de Productos</h3>
                            @if($selectedCategory)
                                <div class="mt-2 flex items-center">
                                    <span class="inline-flex items-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-bold text-white shadow-sm dark:bg-blue-600">
                                        <i class="{{ $selectedCategory->icon }} mr-2"></i>
                                        Filtrando por: {{ $selectedCategory->name }}
                                    </span>
                                    <button 
                                        wire:click="clearFilter"
                                        class="ml-2 inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700">
                                        <i class="fa-solid fa-times mr-1"></i>
                                        Quitar filtro
                                    </button>
                                </div>
                            @endif
                        </div>
                        <x-button primary label="Agregar Producto" icon="plus" wire:click="$toggle('showForm')" />
                    </div>

                    <!-- TABS POR CATEGORÍA -->
                    <div class="mb-6">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Tabs">
                                <!-- Tab: Todos -->
                                <button
                                    wire:click="setActiveTab('all')"
                                    class="flex items-center whitespace-nowrap border-b-2 px-4 py-3 text-sm font-medium transition-all
                                        {{ $activeTab === 'all' 
                                            ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' 
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                                    <i class="fa-solid fa-th-large mr-2"></i>
                                    Todos los Productos
                                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-bold
                                        {{ $activeTab === 'all' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $totalProducts }}
                                    </span>
                                </button>

                                <!-- Tab por cada categoría -->
                                @foreach($categories as $cat)
                                <button
                                    wire:click="setActiveTab({{ $cat->id }})"
                                    class="flex items-center whitespace-nowrap border-b-2 px-4 py-3 text-sm font-medium transition-all
                                        {{ $activeTab == $cat->id 
                                            ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' 
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                                    <i class="{{ $cat->icon }} mr-2"></i>
                                    {{ $cat->name }}
                                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-bold
                                        {{ $activeTab == $cat->id ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $categoryCounts[$cat->id] ?? 0 }}
                                    </span>
                                </button>
                                @endforeach
                            </nav>
                        </div>
                    </div>

                    <!-- Formulario de Agregar Producto -->
                    @if($showForm)
                    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-6 dark:border-blue-800 dark:bg-blue-900/20">
                        <h4 class="mb-4 text-lg font-semibold text-blue-900 dark:text-blue-100">
                            <i class="fa-solid fa-plus-circle mr-2"></i>Nuevo Producto
                        </h4>
                        
                        <form wire:submit="save">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                
                                <!-- Categoría -->
                                <div>
                                    <x-select
                                        label="Categoría *"
                                        placeholder="Seleccione una categoría"
                                        wire:model.live="category_id"
                                        :options="$categories"
                                        option-label="name"
                                        option-value="id"
                                    />
                                </div>

                                <!-- Selector Inteligente de Producto -->
                                <div x-data="{ open: @entangle('showProductList') }" class="relative">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre del Artículo *
                                        @if(!$category_id)
                                            <span class="text-xs text-orange-600 dark:text-orange-400 ml-2">
                                                ⚠️ Primero selecciona una categoría
                                            </span>
                                        @endif
                                    </label>
                                    
                                    <!-- Input con búsqueda -->
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            wire:model.live.debounce.300ms="searchProduct"
                                            @focus="if($wire.category_id) { open = true; $wire.updateProductList(); }"
                                            @click.away="open = false"
                                            placeholder="{{ $category_id ? 'Buscar producto existente o escribir nuevo nombre...' : 'Selecciona primero una categoría' }}"
                                            {{ !$category_id ? 'disabled' : '' }}
                                            class="w-full px-4 py-2 border-2 rounded-lg transition-all
                                                {{ !$category_id ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed opacity-50' : 'bg-white dark:bg-gray-800' }}
                                                {{ $selectedExistingProduct ? 'border-green-500 dark:border-green-600' : 'border-gray-300 dark:border-gray-600' }}
                                                focus:ring-2 focus:ring-blue-500 dark:text-white">
                                        
                                        <!-- Icono de búsqueda / check -->
                                        <div class="absolute right-3 top-2.5">
                                            @if($selectedExistingProduct)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-semibold text-green-600 dark:text-green-400">✅ Existente</span>
                                                    <button 
                                                        type="button"
                                                        wire:click="clearProductSelection"
                                                        class="text-red-500 hover:text-red-700 dark:text-red-400">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <i class="fas fa-search text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @error('name') 
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                    @enderror
                                    
                                    <!-- Dropdown de productos existentes -->
                                    @if($category_id && !$selectedExistingProduct)
                                        <div 
                                            x-show="open" 
                                            x-transition
                                            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border-2 border-blue-300 dark:border-blue-600 rounded-lg shadow-xl max-h-64 overflow-y-auto">
                                            
                                            @if(count($productsFiltered) > 0)
                                                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 border-b border-blue-200 dark:border-blue-700">
                                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        {{ count($productsFiltered) }} producto(s) encontrado(s) - Selecciona para añadir stock
                                                    </p>
                                                </div>
                                                
                                                @foreach($productsFiltered as $prod)
                                                    <button 
                                                        type="button"
                                                        wire:click="selectExistingProduct({{ $prod->id }})"
                                                        @click="open = false"
                                                        class="w-full text-left px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 border-b border-gray-100 dark:border-gray-700 transition-colors">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex-1">
                                                                <p class="font-semibold text-gray-900 dark:text-white">
                                                                    {{ $prod->name }}
                                                                </p>
                                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                                                        {{ ucfirst($prod->unit_type) }}
                                                                    </span>
                                                                    <span class="ml-2">
                                                                        Stock: {{ $this->getStockForProduct($prod->id) }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            <i class="fas fa-chevron-right text-blue-500"></i>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            @else
                                                <div class="p-4 text-center">
                                                    @if(strlen($searchProduct) > 0)
                                                        <div class="text-green-600 dark:text-green-400">
                                                            <i class="fas fa-plus-circle text-2xl mb-2"></i>
                                                            <p class="text-sm font-semibold">✨ Producto Nuevo</p>
                                                            <p class="text-xs mt-1">"{{ $searchProduct }}" se creará como nuevo producto</p>
                                                        </div>
                                                    @else
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            <i class="fas fa-search mr-1"></i>
                                                            Escribe para buscar o crear producto nuevo
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <!-- Mensaje informativo -->
                                    @if($selectedExistingProduct)
                                        <div class="mt-2 p-2 bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-600 rounded-lg">
                                            <p class="text-xs font-semibold text-green-700 dark:text-green-300">
                                                ℹ️ La cantidad ingresada se <strong>sumará</strong> al stock existente de este producto
                                            </p>
                                        </div>
                                    @elseif(strlen($searchProduct) > 0 && count($productsFiltered) == 0 && $category_id)
                                        <div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-300 dark:border-blue-600 rounded-lg">
                                            <p class="text-xs font-semibold text-blue-700 dark:text-blue-300">
                                                ✨ Se creará "<strong>{{ $searchProduct }}</strong>" como <strong>producto nuevo</strong>
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tipo de Unidad -->
                                <div>
                                    <x-select
                                        label="Tipo de Unidad *"
                                        placeholder="Seleccione tipo"
                                        wire:model="unit_type"
                                        :options="[
                                            ['value' => 'unidad', 'label' => 'Unidad'],
                                            ['value' => 'caja', 'label' => 'Caja'],
                                            ['value' => 'litro', 'label' => 'Litro'],
                                            ['value' => 'kilo', 'label' => 'Kilo'],
                                            ['value' => 'servicio', 'label' => 'Servicio'],
                                            ['value' => 'paquete', 'label' => 'Paquete'],
                                            ['value' => 'otro', 'label' => 'Otro'],
                                        ]"
                                        option-label="label"
                                        option-value="value"
                                    />
                                </div>

                                <!-- Cantidad Inicial -->
                                <div>
                                    <x-input
                                        label="Cantidad Inicial *"
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                        wire:model="initial_quantity"
                                        hint="Stock inicial del producto"
                                    />
                                </div>

                                <!-- Descripción (Auto-llenada) -->
                                <div>
                                    <x-textarea
                                        label="Descripción (Auto-llenada)"
                                        placeholder="Se llena automáticamente al seleccionar categoría"
                                        wire:model="description"
                                        rows="2"
                                    />
                                </div>

                                <!-- Observación -->
                                <div class="md:col-span-2">
                                    <x-textarea
                                        label="Observación (Opcional)"
                                        placeholder="Información adicional o notas especiales..."
                                        wire:model="observation"
                                        rows="2"
                                    />
                                </div>

                            </div>

                            <!-- Botones -->
                            <div class="mt-4 flex justify-end space-x-2">
                                <x-button flat label="Cancelar" wire:click="$toggle('showForm')" />
                                <x-button primary label="Guardar Producto" icon="check" type="submit" spinner />
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Tabla de Productos con DataTables -->
                    <div class="overflow-x-auto">
                        <table id="productosTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 display nowrap" style="width:100%">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Categoría
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Nombre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Tipo de Unidad
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Cantidad en Stock
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Descripción
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Observación
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @forelse($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="{{ $product->category->icon }} mr-2 text-blue-600"></i>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $product->category->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-blue-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-blue-600">
                                            <i class="fa-solid fa-tag mr-2"></i>
                                            {{ ucfirst($product->unit_type) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @php
                                            $stock = $this->getStockForProduct($product->id, $warehouse?->id ?? 1);
                                        @endphp
                                        <span class="inline-flex items-center rounded-lg px-4 py-2 text-base font-bold shadow-sm
                                            {{ $stock > 10 ? 'bg-green-500 text-white dark:bg-green-600' : 
                                               ($stock > 0 ? 'bg-yellow-500 text-gray-900 dark:bg-yellow-400 dark:text-gray-900' : 
                                               'bg-red-500 text-white dark:bg-red-600') }}">
                                            <i class="fa-solid fa-boxes-stacked mr-2"></i>
                                            {{ $stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($product->description, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->observation ? Str::limit($product->observation, 40) : '-' }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button 
                                                wire:click="openExitForm({{ $product->id }})"
                                                class="inline-flex items-center rounded-md bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                                <i class="fa-solid fa-arrow-up mr-1"></i>
                                                Salida
                                            </button>
                                            <button 
                                                wire:click="delete({{ $product->id }})"
                                                wire:confirm="¿Estás seguro de eliminar este producto?"
                                                class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                <i class="fa-solid fa-trash mr-1"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa-solid fa-box-open mb-4 text-6xl text-gray-400"></i>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            No hay productos registrados. Haz clic en "Agregar Producto" para comenzar.
                                        </p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Modal de Salida Rápida -->
            @if($showExitForm && $exit_product_id)
                @php
                    $product = $products->firstWhere('id', $exit_product_id);
                    $currentStock = $this->getStockForProduct($exit_product_id, $warehouse?->id ?? 1);
                @endphp
                <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showExitForm', false)"></div>
                        
                        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                            <div class="bg-white px-4 pb-4 pt-5 dark:bg-gray-800 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900 sm:mx-0 sm:h-10 sm:w-10">
                                        <i class="fa-solid fa-arrow-up text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <div class="mt-3 w-full text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                                            Salida Rápida de Producto
                                        </h3>
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                <strong>Producto:</strong> {{ $product->name }}<br>
                                                <strong>Stock actual:</strong> 
                                                <span class="inline-flex items-center rounded-lg px-2 py-1 text-sm font-bold
                                                    {{ $currentStock > 10 ? 'bg-green-500 text-white' : 
                                                       ($currentStock > 0 ? 'bg-yellow-500 text-gray-900' : 'bg-red-500 text-white') }}">
                                                    {{ $currentStock }}
                                                </span>
                                            </p>
                                            
                                            <form wire:submit="quickExit">
                                                <x-input
                                                    label="Cantidad a Retirar *"
                                                    type="number"
                                                    min="1"
                                                    max="{{ $currentStock }}"
                                                    wire:model="exit_quantity"
                                                    placeholder="Ingrese cantidad"
                                                />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 dark:bg-gray-900 sm:flex sm:flex-row-reverse sm:px-6">
                                <button 
                                    wire:click="quickExit"
                                    type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-orange-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                                    <i class="fa-solid fa-check mr-2"></i>
                                    Registrar Salida
                                </button>
                                <button 
                                    wire:click="$set('showExitForm', false)"
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let table;
    
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#productosTable')) {
            $('#productosTable').DataTable().destroy();
        }
        
        table = $('#productosTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            pageLength: 10,
            order: [[1, 'asc']], // Ordenar por nombre
            columnDefs: [
                { orderable: false, targets: 6 } // Desactivar ordenamiento en columna Acciones
            ],
            dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4"<"flex-1"f><"flex-none"l>>rtip',
            initComplete: function() {
                console.log('✅ DataTable inicializado');
            }
        });
    }
    
    // Inicializar al cargar
    initDataTable();
    
    // Reinicializar cuando Livewire actualice la tabla
    Livewire.on('refreshTable', () => {
        setTimeout(() => initDataTable(), 100);
    });
    
    // Reinicializar después de acciones de Livewire
    document.addEventListener('livewire:navigated', () => {
        setTimeout(() => initDataTable(), 100);
    });
});
</script>
@endpush
