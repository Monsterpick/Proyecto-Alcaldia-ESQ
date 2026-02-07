<?php

use Livewire\Volt\Component;
use Illuminate\View\View;
use App\Models\Transfer;
use App\Models\Product;

new class extends Component {
    
    public $type = 3;
    public $serie = 'TRANS001';
    public $correlative;
    public $date;
    public $origin_warehouse_id;
    public $destination_warehouse_id;
    public $total = 0;
    public $discount;
    public $tax;
    public $observation;

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

    public function mount(){
        //Buscamos el maximo correlativo de las ordenes de compra y le sumamos 1
        $this->correlative = Transfer::max('correlative') + 1;
    }

    public function updated($property, $value){
        if($property === 'type'){
            $this->destination_warehouse_id = null;
        }
        if($property === 'origin_warehouse_id'){
            $this->destination_warehouse_id = null;
        }
    }

    public function save(){
        $this->validate([
            'type' => 'required|integer|min:1|max:4',
            'serie' => 'required|string|max:10',
            'correlative' => 'required|integer|min:1',
            'date' => 'nullable|date',
            'origin_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'required|different:origin_warehouse_id|exists:warehouses,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ],[
            'type.required' => 'El tipo de comprobante es requerido',
            'type.integer' => 'El tipo de comprobante debe ser un número entero',
            'type.min' => 'El tipo de comprobante debe ser mayor a 0',
            'type.max' => 'El tipo de comprobante debe ser menor a 5',
            'serie.required' => 'La serie es requerida',
            'serie.string' => 'La serie debe ser un texto',
            'serie.max' => 'La serie debe tener menos de 10 caracteres',
            'origin_warehouse_id.required' => 'El almacén de origen es requerido',
            'origin_warehouse_id.exists' => 'El almacén de origen no existe',
            'destination_warehouse_id.required' => 'El almacén de destino es requerido',
            'destination_warehouse_id.exists' => 'El almacén de destino no existe',
            'destination_warehouse_id.different' => 'El almacén de destino debe ser diferente al almacén de origen',
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
            'origin_warehouse_id.required' => 'El almacén de origen es requerido',
            'origin_warehouse_id.exists' => 'El almacén de origen no existe',
            'destination_warehouse_id.required' => 'El almacén de destino es requerido',
            'destination_warehouse_id.exists' => 'El almacén de destino no existe',
            'destination_warehouse_id.different' => 'El almacén de destino debe ser diferente al almacén de origen',
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

        $transfer = Transfer::create([
            'type' => $this->type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'origin_warehouse_id' => $this->origin_warehouse_id,
            'destination_warehouse_id' => $this->destination_warehouse_id,
            'total' => $this->total,
            'discount' => $this->discount ?? 0,
            'tax' => $this->tax ?? 0,
            'observation' => $this->observation,
        ]);

        foreach($this->products as $product){
            $transfer->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['price'] * $product['quantity'],
            ]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Transferencia creada',
            'text' => 'La transferencia se ha creado correctamente',
        ]);

        $this->redirect(route('admin.transfers.index'), navigate: true);
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
            'price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
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
            Información de la Transferencia
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
            Registre la información de la transferencia.
        </p>
        <div class="border-t border-gray-200 dark:border-gray-600 mb-4"></div>

        <form wire:submit.prevent="save" >
            <div class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <x-native-select label="Tipo de Transferencia" wire:model.live="type">
                    <option value="1">Ingreso</option>
                    <option value="2">Salida</option>
                    <option value="3">Transferencia</option>
                    <option value="4">Devolución</option>
                </x-native-select>

                <x-input label="Serie" wire:model="serie" placeholder="Serie del comprobante" disabled/>

                <x-input label="Correlativo" wire:model="correlative" placeholder="Correlativo del comprobante" disabled  />

                <x-input label="Fecha" wire:model="date" type="date" />

                <x-select
                    class="col-span-2"
                    label="Almacén de Origen" 
                    wire:model.live="origin_warehouse_id" 
                    :async-data="[
                        'api' => route('api.warehouses.index'),
                        'method' => 'POST',
                        ]"
                        option-label="name"
                        option-value="id"
                />

                <x-select
                    class="col-span-2"
                    label="Almacén Destino" 
                    wire:model="destination_warehouse_id" 
                    :async-data="[
                        'api' => route('api.warehouses.index'),
                        'method' => 'POST',
                        'params' => [
                            'type' => $this->type,
                            'exclude' => $this->origin_warehouse_id,
                        ]
                        ]"
                        option-label="name"
                        option-value="id"
                />
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
