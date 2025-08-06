<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Editar Producto');
    }

    public $categories = [];
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $expedition_date;
    public $expiration_date;
    public $product;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->expedition_date = $product->expedition_date;
        $this->expiration_date = $product->expiration_date;
        $this->categories = Category::orderBy('name')->get();
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'expedition_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
        ]);

        $this->product->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'expedition_date' => $this->expedition_date,
            'expiration_date' => $this->expiration_date,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Producto actualizado',
            'text' => 'El producto se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.products.index'), navigate: true);
    }
}; ?>

<div>

    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Productos',
                'route' => route('admin.products.index'),
            ],
            [
                'name' => $this->product->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6 space-y-4">
        <x-card>
            <h1 class="text-2xl font-bold">
                Imágenes del Producto
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Agregue imágenes del producto.
            </p>
            <div class="mb-4">
                <form action="{{ route('admin.products.dropzone.store', $product->id) }}" method="POST" class="dropzone " id="myDropzone" enctype="multipart/form-data">
                    @csrf
                    
                </form>
            </div>
        </x-card>
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Producto
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información del producto.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.products.partials.form', ['showForm' => true, 'editForm' => true])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Actualizar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>

        
    </x-container>

    @push('scripts')
        <script type="module">
            let myDropzone = new Dropzone("#myDropzone", {
            addRemoveLinks: true,
            init: function(){
                let MyDropzone = this;

                let images = @json($product->images);
                images.forEach(function(image){
                    let mockFile = {
                        id: image.id,
                        name: image.path.split('/').pop(),
                        size: image.size,
                    };
                    MyDropzone.displayExistingFile(mockFile, `{{ Storage::url('${image.path}') }}`);
                    MyDropzone.emit("complete", mockFile);
                    MyDropzone.files.push(mockFile);
                });

                this.on("success", function(file, response){
                    file.id = response.id;
                });

                this.on("removedfile", function(file){
                    let imageId = file.id;
                    let imagePath = file.name;

                    axios.delete(`{{ route('admin.images.destroy', ['image' => ':imageId']) }}`.replace(':imageId', imageId))
                        .then(response => {
                            console.log(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                })
            }
        });
        </script>
    @endpush
</div>


