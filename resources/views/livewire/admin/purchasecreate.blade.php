<?php

use Livewire\Volt\Component;
use Illuminate\View\View;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Purchase;

new class extends Component {
    
    public $voucher_type = 1;
    public $serie;
    public $correlative;
    public $date;
    public $supplier_id;
    public $warehouse_id;
    public $total = 0;
    public $discount;
    public $tax;
    public $observation;
    public $purchase_order_id;

    public $products = [];

    public $product_id;

    public function boot()
    {
        //Verificar si hay errores de validacion
        $this->withValidator(function($validator){
            if($validator->fails()){
                $errors = $validator->errors()->toArray();

                $html = "<ul class='text-left'>";

                foreach($errors as $error){
                    $html .= "<li>{$error[0]}</li>";
                }

                $html .= "</ul>";

                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Error de validación',
                    'html' => $html,
                ]);
            }
        });
    }



    public function updated($property, $value){
        if($property == 'purchase_order_id'){
            $purchaseOrder = PurchaseOrder::find($value);

            if($purchaseOrder){
                $this->voucher_type = $purchaseOrder->voucher_type;
                $this->supplier_id = $purchaseOrder->supplier_id;


                $this->products = $purchaseOrder->products->map(function($product){
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->pivot->price,
                        'quantity' => $product->pivot->quantity,
                        'subtotal' => $product->pivot->price * $product->pivot->quantity,
                    ];
                })->toArray();
            }
        }
    }

    public function save(){
        $this->validate([
            'voucher_type' => 'required|integer|min:1|max:4',
            'serie' => 'required|string|max:99999999',
            'correlative' => 'required|integer|min:1|max:99999999',
            'date' => 'nullable|date',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ],[
            'voucher_type.required' => 'El tipo de comprobante es requerido',
            'voucher_type.integer' => 'El tipo de comprobante debe ser un número entero',
            'voucher_type.min' => 'El tipo de comprobante debe ser mayor a 0',
            'voucher_type.max' => 'El tipo de comprobante debe ser menor a 5',
            'serie.required' => 'La serie es requerida',
            'serie.string' => 'La serie debe ser un texto',
            'serie.max' => 'La serie debe tener menos de 10 caracteres',
            'supplier_id.required' => 'El proveedor es requerido',
            'supplier_id.exists' => 'El proveedor no existe',
            'total.required' => 'El total es requerido',
            'total.numeric' => 'El total debe ser un número',
            'total.min' => 'El total debe ser mayor a 0',
            'observation.string' => 'La observación debe ser un texto',
            'observation.max' => 'La observación debe tener menos de 255 caracteres',
            'products.*.id.required' => 'El producto es requerido',
            'products.*.id.exists' => 'El producto no existe',
            'products.*.quantity.required' => 'La cantidad es requerida',
            'products.*.quantity.integer' => 'La cantidad debe ser un número entero',
            'products.*.quantity.min' => 'La cantidad debe ser mayor a 0',
            'products.*.price.required' => 'El precio es requerido',
            'products.*.price.numeric' => 'El precio debe ser un número',
            'products.*.price.min' => 'El precio debe ser mayor a 0',
        ],[
            'supplier_id.required' => 'El proveedor es requerido',
            'supplier_id.exists' => 'El proveedor no existe',
            'total.required' => 'El total es requerido',
            'total.numeric' => 'El total debe ser un número',
            'total.min' => 'El total debe ser mayor a 0',
            'observation.string' => 'La observación debe ser un texto',
            'observation.max' => 'La observación debe tener menos de 255 caracteres',
            'products.*.id' => 'Producto',
            'products.*.quantity' => 'Cantidad',
            'products.*.price' => 'Precio',
            'products.*.subtotal' => 'Subtotal',
            'products.*.id.required' => 'El producto es requerido',
            'products.*.id.exists' => 'El producto no existe',
            'products.*.quantity.required' => 'La cantidad es requerida',
            'products.*.quantity.integer' => 'La cantidad debe ser un número entero',
            'products.*.quantity.min' => 'La cantidad debe ser mayor a 0',
            'products.*.price.required' => 'El precio es requerido',
            'products.*.price.numeric' => 'El precio debe ser un número',
            'products.*.price.min' => 'El precio debe ser mayor a 0',
        ]);

        $purchase = Purchase::create([
            'voucher_type' => $this->voucher_type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'purchase_order_id' => $this->purchase_order_id,
            'warehouse_id' => $this->warehouse_id,
            'supplier_id' => $this->supplier_id,
            'total' => $this->total,
            'discount' => $this->discount ?? 0,
            'tax' => $this->tax ?? 0,
            'observation' => $this->observation,
        ]);

        foreach($this->products as $product){
            $purchase->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['price'] * $product['quantity'],
            ]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'La compra se ha creado correctamente',
            'text' => 'La compra se ha creado correctamente',
        ]);

        $this->redirect(route('admin.purchases.index'), navigate: true);
    }

    public function addProduct(){

        $this->validate([
            'product_id' => 'required|exists:products,id',
        ],[
            'product_id.required' => 'El producto es requerido',
            'product_id.exists' => 'El producto no existe',
        ],[
            'product_id' => 'Producto',
        ]);

        $existing = collect($this->products)
            ->firstWhere('id', $this->product_id);

        if($existing){
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Producto ya agregado',
                'text' => 'El producto ya ha sido agregado a la lista',
            ]);

            return;
        }

        $product = Product::find($this->product_id);

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => 0,
            'quantity' => 1,
            'subtotal' => 0,
        ];

        $this->product_id = null;
        
    }
    
}; ?>

<div x-data="{
    products: @entangle('products'),

    total: @entangle('total'),

    removeProduct(index){
        this.products.splice(index, 1);
    },

    init()
    {
        this.$watch('products', (newProducts) => {

            let total = 0;

            newProducts.forEach(product => {
                total += product.price * product.quantity;
            });

            this.total = total;
        });
    }
}">
    <x-card >
        <h1 class="text-2xl font-bold">
            Información de la Orden de Compra
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
            Registre la información de la orden de compra.
        </p>
        <div class="border-t border-gray-200 dark:border-gray-600 mb-4"></div>

        <form wire:submit.prevent="save" >
            <div class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <x-native-select label="Tipo de Comprobante" wire:model="voucher_type">
                    <option value="1">Factura</option>
                    <option value="2">Boleta</option>
                    <option value="3">Nota de Crédito</option>
                    <option value="4">Nota de Débito</option>
                </x-native-select>

                <div class="grid grid-cols-2 gap-2">

                    <x-input label="Serie" wire:model="serie" placeholder="Serie del comprobante" />
                    
                    <x-input label="Correlativo" wire:model="correlative" placeholder="Correlativo del comprobante"  />
                </div>

                <x-input label="Fecha" wire:model="date" type="date" />

                <x-select 
                label="Orden de Compra" 
                wire:model.live="purchase_order_id" 
                :async-data="[
                    'api' => route('api.purchase-orders.index'),
                    'method' => 'POST',
                    ]"
                    option-label="name"
                    option-value="id"
                    option-description="description"
                />

                <div class="col-span-2">
                    <x-select 
                    label="Proveedor" 
                    wire:model="supplier_id" 
                    :async-data="[
                        'api' => route('api.suppliers.index'),
                        'method' => 'POST',
                        ]"
                        option-label="name"
                        option-value="id"
                />
                </div>

                <div class="col-span-2">
                    <x-select 
                    label="Almacenes" 
                    wire:model="warehouse_id" 
                    :async-data="[
                        'api' => route('api.warehouses.index'),
                        'method' => 'POST',
                        ]"
                        option-label="name"
                        option-value="id"
                        option-description="description"
                />
                </div>

                
            </div>
            

            <div class="lg:flex lg:space-x-4">
                <x-select class="flex-1"
                label="Producto" 
                wire:model="product_id" 
                placeholder="Seleccione un producto"
                :async-data="[
                    'api' => route('api.products.index'),
                    'method' => 'POST',
                    ]"
                    option-label="name"
                    option-value="id"
                />
                <div class="flex-shrink-0">
                    <x-button class="w-full lg:mt-6.5 mt-4" label="Agregar" wire:click="addProduct" spinner="addProduct" interaction="positive" icon="plus" />
                </div>
            </div>

            <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-gray-700 border-y bg-blue-50">
                        <th class="py-2 px-4">Producto</th>
                        <th class="py-2 px-4">Cantidad</th>
                        <th class="py-2 px-4">Precio</th>
                        <th class="py-2 px-4">Subtotal</th>
                        <th class="py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(product, index) in products" :key="product.id">
                        <tr class="border-b">
                            <td class="py-2 px-4" x-text="product.name"></td>
                            <td class="py-2 px-4">
                                <x-input 
                                    type="number" 
                                    class="w-20"
                                    x-model="product.quantity" 
                                />
                            </td>
                            <td class="py-2 px-4">
                                <x-input 
                                    type="number" 
                                    class="w-20"
                                    x-model="product.price" 
                                    step="0.01"
                                />
                            </td>
                            <td class="py-2 px-4" x-text="(product.price * product.quantity).toFixed(2)"></td>
                            <td class="py-2 px-4">
                                <x-mini-button 
                                    label="Eliminar" 
                                    x-on:click="removeProduct(index)" 
                                    icon="trash" 
                                    rounded 
                                    red 
                                />
                            </td>
                        </tr>
                    </template>
                    <template x-if="products.length === 0">
                        <tr>
                            <td class="text-center text-gray-500 py-4 dark:text-gray-300" colspan="5">No hay productos agregados</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

            <div class="flex items-center space-x-4 mb-4">
                <x-label>
                    Observaciones
                </x-label>

                <x-input class="flex-1" wire:model="observation" />

                <div class="">
                    Total: <span x-text="total.toFixed(2)"></span>
                </div>

                
            </div>
        </form>
        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <x-button info wire:click="save" spinner="save" label="Guardar" icon="check"
                    interaction="positive" />
                <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
            </div>
        </x-slot>
    </x-card>
</div>
