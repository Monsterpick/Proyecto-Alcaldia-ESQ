<?php

use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Categorías de Inventario')] class extends Component {
    
    public function with(): array
    {
        return [
            'categories' => Category::where('is_active', true)->get(),
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Categorías de Inventario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-medium">Categorías de Beneficios Sociales</h3>
                    </div>

                    <!-- Grid de Categorías -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($categories as $category)
                        <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            <!-- Icono -->
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                <i class="{{ $category->icon }} text-3xl text-blue-600 dark:text-blue-400"></i>
                            </div>

                            <!-- Nombre -->
                            <h4 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </h4>

                            <!-- Descripción -->
                            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $category->description }}
                            </p>

                            <!-- Botón de Acción -->
                            <a 
                                href="{{ route('admin.products.index', ['category' => $category->id]) }}"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                                <i class="fa-solid fa-eye mr-2"></i>
                                Ver Productos
                            </a>

                            <!-- Badge de Estado -->
                            <div class="absolute right-4 top-4">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <i class="fa-solid fa-check mr-1"></i>
                                    Activo
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($categories->isEmpty())
                    <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center dark:border-gray-700">
                        <i class="fa-solid fa-box-open mb-4 text-6xl text-gray-400"></i>
                        <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No hay categorías</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Ejecuta el seeder para crear las categorías predefinidas.
                        </p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
