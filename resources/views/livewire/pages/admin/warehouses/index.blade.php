<?php

use App\Models\Warehouse;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Almacén')] class extends Component {
    
    public function with(): array
    {
        return [
            'warehouses' => Warehouse::where('is_active', true)->get(),
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Almacén') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-medium">Almacenes de Beneficios Sociales</h3>
                    </div>

                    <!-- Grid de Almacenes -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        @foreach($warehouses as $warehouse)
                        <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            
                            <!-- Header del Card -->
                            <div class="mb-4 flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Icono -->
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                        <i class="fa-solid fa-warehouse text-3xl text-green-600 dark:text-green-400"></i>
                                    </div>
                                    
                                    <!-- Nombre -->
                                    <div>
                                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            {{ $warehouse->name }}
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fa-solid fa-location-dot mr-1"></i>
                                            {{ $warehouse->location }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Badge de Estado -->
                                <span class="inline-flex items-center rounded-lg bg-green-500 px-4 py-2 text-base font-bold text-white shadow-sm dark:bg-green-600">
                                    <i class="fa-solid fa-check mr-2"></i>
                                    Activo
                                </span>
                            </div>

                            <!-- Descripción -->
                            <p class="mb-4 text-base font-medium text-gray-700 dark:text-gray-200">
                                {{ $warehouse->description }}
                            </p>

                            <!-- Información Adicional -->
                            <div class="mb-4 space-y-2 rounded-lg bg-gray-100 p-4 dark:bg-gray-800">
                                <div class="flex items-center text-base">
                                    <i class="fa-solid fa-user mr-2 w-5 text-blue-600 dark:text-blue-400"></i>
                                    <span class="font-bold text-gray-900 dark:text-white">Responsable:</span>
                                    <span class="ml-2 font-medium text-gray-700 dark:text-gray-200">{{ $warehouse->responsible }}</span>
                                </div>
                                <div class="flex items-center text-base">
                                    <i class="fa-solid fa-phone mr-2 w-5 text-blue-600 dark:text-blue-400"></i>
                                    <span class="font-bold text-gray-900 dark:text-white">Teléfono:</span>
                                    <span class="ml-2 font-medium text-gray-700 dark:text-gray-200">{{ $warehouse->phone }}</span>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="flex space-x-2">
                                <a 
                                    href="{{ route('admin.stock-adjustments.index') }}"
                                    class="inline-flex flex-1 items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    <i class="fa-solid fa-boxes-stacked mr-2"></i>
                                    Ver Stock
                                </a>
                                <a 
                                    href="{{ route('admin.movements.index') }}"
                                    class="inline-flex flex-1 items-center justify-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-500 dark:hover:bg-gray-600">
                                    <i class="fa-solid fa-arrows-rotate mr-2"></i>
                                    Movimientos
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($warehouses->isEmpty())
                    <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center dark:border-gray-700">
                        <i class="fa-solid fa-warehouse mb-4 text-6xl text-gray-400"></i>
                        <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No hay almacenes</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Ejecuta el seeder para crear el almacén predefinido.
                        </p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
