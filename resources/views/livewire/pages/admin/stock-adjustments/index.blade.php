<?php

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Inventory;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new #[Layout('livewire.layout.admin.admin'), Title('Stock')] class extends Component {

    #[Validate('required')]
    public $product_id = '';

    #[Validate('required')]
    public $warehouse_id = '';

    #[Validate('required|integer|min:1')]
    public $quantity = 1;

    #[Validate('required|in:entrada,salida,ajuste')]
    public $type = 'entrada';

    public $detail = '';

    public $showForm = false;

    public function save()
    {
        $this->validate();

        $product = Product::find($this->product_id);
        $warehouse = Warehouse::find($this->warehouse_id);

        // Obtener el último balance
        $lastInventory = Inventory::where('product_id', $this->product_id)
            ->where('warehouse_id', $this->warehouse_id)
            ->latest()
            ->first();

        $previousBalance = $lastInventory ? $lastInventory->quantity_balance : 0;

        // Calcular nuevo balance según el tipo
        if ($this->type === 'entrada') {
            $quantityIn = $this->quantity;
            $quantityOut = 0;
            $newBalance = $previousBalance + $this->quantity;
        } elseif ($this->type === 'salida') {
            $quantityIn = 0;
            $quantityOut = $this->quantity;
            $newBalance = $previousBalance - $this->quantity;
        } else { // ajuste
            $quantityIn = $this->quantity > $previousBalance ? ($this->quantity - $previousBalance) : 0;
            $quantityOut = $this->quantity < $previousBalance ? ($previousBalance - $this->quantity) : 0;
            $newBalance = $this->quantity;
        }

        // Crear registro de inventario
        Inventory::create([
            'product_id' => $this->product_id,
            'warehouse_id' => $this->warehouse_id,
            'detail' => $this->detail ?: ucfirst($this->type) . ' de stock',
            'quantity_in' => $quantityIn,
            'quantity_out' => $quantityOut,
            'quantity_balance' => $newBalance,
            'cost_in' => 0,
            'cost_out' => 0,
            'total_in' => 0,
            'total_out' => 0,
            'cost_balance' => 0,
            'total_balance' => 0,
            'inventoryable_type' => 'App\Models\Product',
            'inventoryable_id' => $this->product_id,
        ]);

        session()->flash('success', 'Ajuste de stock realizado exitosamente');

        $this->reset(['product_id', 'warehouse_id', 'quantity', 'type', 'detail']);
        $this->showForm = false;
    }

    public function with(): array
    {
        return [
            'products' => Product::with('category')->where('is_active', true)->get(),
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'adjustments' => Inventory::with(['product.category', 'warehouse'])
                ->latest()
                ->take(50)
                ->get(),
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Stock
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

            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-medium">Gestión de Stock</h3>
                        <x-button primary label="Nuevo Ajuste" icon="plus" wire:click="$toggle('showForm')" />
                    </div>

                    @if($showForm)
                    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-6 dark:border-blue-800 dark:bg-blue-900/20">
                        <h4 class="mb-4 text-lg font-semibold text-blue-900 dark:text-blue-100">
                            <i class="fa-solid fa-sliders mr-2"></i>Ajuste de Stock
                        </h4>
                        
                        <form wire:submit="save">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                
                                <div>
                                    <x-select
                                        label="Almacén *"
                                        placeholder="Seleccione almacén"
                                        wire:model="warehouse_id"
                                        :options="$warehouses"
                                        option-label="name"
                                        option-value="id"
                                    />
                                </div>

                                <div>
                                    <x-select
                                        label="Producto *"
                                        placeholder="Seleccione producto"
                                        wire:model="product_id"
                                        :options="$products"
                                        option-label="name"
                                        option-value="id"
                                    />
                                </div>

                                <div>
                                    <x-select
                                        label="Tipo de Movimiento *"
                                        wire:model="type"
                                        :options="[
                                            ['value' => 'entrada', 'label' => 'Entrada (+)'],
                                            ['value' => 'salida', 'label' => 'Salida (-)'],
                                            ['value' => 'ajuste', 'label' => 'Ajuste (=)'],
                                        ]"
                                        option-label="label"
                                        option-value="value"
                                    />
                                </div>

                                <div>
                                    <x-input
                                        label="Cantidad *"
                                        type="number"
                                        min="1"
                                        wire:model="quantity"
                                    />
                                </div>

                                <div class="md:col-span-2">
                                    <x-textarea
                                        label="Detalle (Opcional)"
                                        placeholder="Motivo del ajuste..."
                                        wire:model="detail"
                                        rows="2"
                                    />
                                </div>

                            </div>

                            <div class="mt-4 flex justify-end space-x-2">
                                <x-button flat label="Cancelar" wire:click="$toggle('showForm')" />
                                <x-button primary label="Guardar Ajuste" icon="check" type="submit" spinner />
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Tabla de Ajustes -->
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
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @forelse($adjustments as $adj)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $adj->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="{{ $adj->product->category->icon }} mr-2 text-blue-600"></i>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $adj->product->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-indigo-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-indigo-600">
                                            <i class="fa-solid fa-warehouse mr-2"></i>
                                            {{ $adj->warehouse->name }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if($adj->quantity_in > 0)
                                            <span class="text-green-600 dark:text-green-400 font-semibold">+{{ $adj->quantity_in }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if($adj->quantity_out > 0)
                                            <span class="text-red-600 dark:text-red-400 font-semibold">-{{ $adj->quantity_out }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex items-center rounded-lg bg-purple-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-purple-600">
                                            <i class="fa-solid fa-boxes-stacked mr-2"></i>
                                            {{ $adj->quantity_balance }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $adj->detail }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa-solid fa-sliders mb-4 text-6xl text-gray-400"></i>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            No hay ajustes de stock registrados.
                                        </p>
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
