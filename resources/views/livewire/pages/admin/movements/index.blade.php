<?php

use App\Models\Inventory;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Historial de Movimientos')] class extends Component {

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
    }

    public function delete($id)
    {
        Inventory::find($id)->delete();
        session()->flash('success', 'Movimiento eliminado exitosamente');
    }

    public function with(): array
    {
        $query = Inventory::with(['product.category', 'warehouse'])
            ->latest();

        // Filtro por categoría de producto
        if ($this->filterCategory) {
            $query->whereHas('product', function($q) {
                $q->where('category_id', $this->filterCategory);
            });
        }

        // Obtener categorías activas
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Contar movimientos por categoría
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category->id] = Inventory::whereHas('product', function($q) use ($category) {
                $q->where('category_id', $category->id);
            })->count();
        }

        return [
            'movements' => $query->take(100)->get(),
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'totalMovements' => Inventory::count(),
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Historial de Movimientos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900 dark:text-green-200">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

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
                            <i class="fa-solid fa-clock-rotate-left mr-2 text-lg"></i>
                            <span class="font-semibold">Todos los Movimientos</span>
                            <span class="ml-3 rounded-full px-3 py-1 text-xs font-bold
                                {{ $activeTab === 'all' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                {{ $totalMovements }}
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

            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Historial Completo de Movimientos</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @if($filterCategory)
                                Movimientos filtrados por categoría - Últimos 100 registros
                            @else
                                Últimos 100 movimientos de inventario
                            @endif
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Almacén</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Entrada</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Salida</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Balance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Detalle</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @forelse($movements as $mov)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $mov->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="{{ $mov->product->category->icon }} mr-2 text-blue-600"></i>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $mov->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-indigo-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-indigo-600">
                                            <i class="fa-solid fa-warehouse mr-2"></i>
                                            {{ $mov->warehouse->name }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if($mov->quantity_in > 0)
                                            <span class="text-green-600 dark:text-green-400 font-semibold">+{{ $mov->quantity_in }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if($mov->quantity_out > 0)
                                            <span class="text-red-600 dark:text-red-400 font-semibold">-{{ $mov->quantity_out }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex items-center rounded-lg bg-purple-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-purple-600">
                                            <i class="fa-solid fa-boxes-stacked mr-2"></i>
                                            {{ $mov->quantity_balance }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $mov->detail }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <button 
                                            wire:click="delete({{ $mov->id }})"
                                            wire:confirm="¿Estás seguro de eliminar este movimiento?"
                                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            <i class="fa-solid fa-trash mr-1"></i>
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <i class="fa-solid fa-clock-rotate-left mb-4 text-6xl text-gray-400"></i>
                                        <p class="text-gray-500 dark:text-gray-400">No hay movimientos registrados.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
