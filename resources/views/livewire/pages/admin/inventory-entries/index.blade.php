<?php

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Inventory;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new #[Layout('livewire.layout.admin.admin'), Title('Entrada de Inventario')] class extends Component {

    #[Validate('required')]
    public $product_id = '';

    #[Validate('required')]
    public $warehouse_id = '';

    #[Validate('required|integer|min:1')]
    public $quantity = 1;

    #[Validate('required|string|min:5')]
    public $detail = '';
    
    public $showForm = false;

    // Mensajes de validación personalizados
    protected function messages()
    {
        return [
            'product_id.required' => '⚠️ Debe seleccionar un producto',
            'warehouse_id.required' => '⚠️ Debe seleccionar un almacén',
            'quantity.required' => 'La cantidad es obligatoria',
            'quantity.integer' => 'La cantidad debe ser un número entero',
            'quantity.min' => 'La cantidad debe ser mayor a 0',
            'detail.required' => 'El detalle es obligatorio',
            'detail.min' => 'El detalle debe tener al menos 5 caracteres',
        ];
    }

    public function save()
    {
        try {
            $this->validate();

            $product = Product::find($this->product_id);
            $warehouse = Warehouse::find($this->warehouse_id);

            if (!$product) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Producto no encontrado',
                ]);
                return;
            }

            if (!$warehouse) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Almacén no encontrado',
                ]);
                return;
            }

            $lastInventory = Inventory::where('product_id', $this->product_id)
                ->where('warehouse_id', $this->warehouse_id)
                ->latest()
                ->first();

            $previousBalance = $lastInventory ? $lastInventory->quantity_balance : 0;
            $newBalance = $previousBalance + $this->quantity;

            Inventory::create([
                'product_id' => $this->product_id,
                'warehouse_id' => $this->warehouse_id,
                'detail' => $this->detail,
                'quantity_in' => $this->quantity,
                'quantity_out' => 0,
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

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '✅ Entrada Registrada',
                'html' => "<strong>+{$this->quantity} {$product->unit_type}</strong> de '{$product->name}' ingresados a {$warehouse->name}<br><br>📊 Stock anterior: {$previousBalance}<br>🔹 Stock nuevo: <strong>{$newBalance}</strong>",
                'timer' => 4000,
            ]);

            $this->reset(['product_id', 'warehouse_id', 'quantity', 'detail']);
            $this->showForm = false;
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Las validaciones se muestran automáticamente en el formulario
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo registrar la entrada: ' . $e->getMessage(),
            ]);
            \Log::error('Error en entrada de inventario: ' . $e->getMessage());
        }
    }

    public function with(): array
    {
        return [
            'products' => Product::with('category')->where('is_active', true)->get(),
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'entries' => Inventory::with(['product.category', 'warehouse'])
                ->where('quantity_in', '>', 0)
                ->latest()
                ->take(50)
                ->get(),
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Entrada de Inventario
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
                        <h3 class="text-lg font-medium">Registrar Entrada</h3>
                        <x-button primary label="Nueva Entrada" icon="plus" wire:click="$toggle('showForm')" />
                    </div>

                    @if($showForm)
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-6 dark:border-green-800 dark:bg-green-900/20">
                        <h4 class="mb-4 text-lg font-semibold text-green-900 dark:text-green-100">
                            <i class="fa-solid fa-arrow-down mr-2"></i>Nueva Entrada
                        </h4>
                        
                        <form wire:submit="save">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <x-select label="Almacén *" placeholder="Seleccione almacén" wire:model="warehouse_id" :options="$warehouses" option-label="name" option-value="id" />
                                </div>
                                <div>
                                    <x-select label="Producto *" placeholder="Seleccione producto" wire:model="product_id" :options="$products" option-label="name" option-value="id" />
                                </div>
                                <div>
                                    <x-input label="Cantidad *" type="number" min="1" wire:model="quantity" />
                                </div>
                                <div>
                                    <x-input label="Detalle (Opcional)" placeholder="Motivo de entrada..." wire:model="detail" />
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end space-x-2">
                                <x-button flat label="Cancelar" wire:click="$toggle('showForm')" />
                                <x-button primary label="Guardar Entrada" icon="check" type="submit" spinner />
                            </div>
                        </form>
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Almacén</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @forelse($entries as $entry)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $entry->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="{{ $entry->product->category->icon }} mr-2 text-blue-600"></i>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $entry->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-indigo-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-indigo-600">
                                            <i class="fa-solid fa-warehouse mr-2"></i>
                                            {{ $entry->warehouse->name }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex items-center rounded-lg bg-green-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-green-600">
                                            <i class="fa-solid fa-arrow-down mr-2"></i>
                                            +{{ $entry->quantity_in }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $entry->detail }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <i class="fa-solid fa-arrow-down mb-4 text-6xl text-gray-400"></i>
                                        <p class="text-gray-500 dark:text-gray-400">No hay entradas registradas.</p>
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
