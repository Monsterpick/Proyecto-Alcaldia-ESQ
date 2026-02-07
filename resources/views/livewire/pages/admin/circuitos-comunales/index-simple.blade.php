<?php

use Livewire\Volt\Component;
use App\Models\CircuitoComunal;
use App\Models\Parroquia;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Circuitos Comunales')] class extends Component {
    public $currentPage = 1;

    public function setPage($page)
    {
        $this->currentPage = $page;
    }

    public function with(): array
    {
        $parroquias = Parroquia::with('circuitosComunales')->get();
        $currentParroquia = $parroquias->skip($this->currentPage - 1)->first();
        $totalPages = $parroquias->count();

        return [
            'parroquias' => $parroquias,
            'currentParroquia' => $currentParroquia,
            'totalPages' => $totalPages,
            'totalCircuitos' => CircuitoComunal::count(),
        ];
    }
}; ?>
<div class="py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                <i class="fas fa-map-marked-alt mr-2"></i>
                Circuitos Comunales del Municipio Escuque
            </h2>
            <div class="rounded-lg bg-blue-600 px-5 py-2 shadow-lg">
                <span class="text-sm font-bold text-white">
                    <i class="fas fa-map-marked mr-2"></i>Total: {{ $totalCircuitos }} CC
                </span>
            </div>
        </div>
            
        <!-- Navegación entre Parroquias -->
        <div class="mb-6 flex items-center justify-between rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 p-4 shadow-xl">
            <button 
                wire:click="setPage({{ $currentPage > 1 ? $currentPage - 1 : $totalPages }})"
                class="group flex items-center rounded-lg bg-white px-4 py-2 font-semibold text-indigo-700 shadow-md transition-all hover:bg-indigo-50 hover:shadow-lg">
                <i class="fas fa-chevron-left mr-2 transition-transform group-hover:-translate-x-1"></i>
                Anterior
            </button>
            
            <div class="text-center">
                <div class="text-sm font-semibold text-indigo-200">Página {{ $currentPage }} de {{ $totalPages }}</div>
                <div class="text-2xl font-bold text-white">
                    @if($currentParroquia)
                    {{ $currentParroquia->parroquia }}
                    @endif
                </div>
            </div>
            
            <button 
                wire:click="setPage({{ $currentPage < $totalPages ? $currentPage + 1 : 1 }})"
                class="group flex items-center rounded-lg bg-white px-4 py-2 font-semibold text-indigo-700 shadow-md transition-all hover:bg-indigo-50 hover:shadow-lg">
                Siguiente
                <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1"></i>
            </button>
        </div>

        <!-- Circuitos Comunales -->
        @if($currentParroquia && $currentParroquia->circuitosComunales->count() > 0)
        <div class="overflow-hidden rounded-xl bg-white shadow-2xl dark:bg-gray-800">
            <!-- Header de Parroquia -->
            <div class="flex items-center justify-between border-b-4 border-orange-500 bg-gradient-to-r from-orange-600 to-red-600 p-6">
                <h3 class="text-2xl font-bold text-white">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    Circuitos Comunales de la Parroquia {{ $currentParroquia->parroquia }}
                </h3>
                <span class="rounded-full bg-blue-600 px-5 py-2 text-sm font-bold text-white shadow-lg">
                    {{ $currentParroquia->circuitosComunales->count() }} CC
                </span>
            </div>

            <!-- Agrupar por comuna -->
            @php
            $circuitosPorComuna = $currentParroquia->circuitosComunales->groupBy(function($circuito) {
                if ($circuito->descripcion && str_contains($circuito->descripcion, 'Comuna:')) {
                    preg_match('/Comuna:\s*([^|]+)/', $circuito->descripcion, $matches);
                    return trim($matches[1] ?? 'Sin Comuna');
                }
                return 'Sin Comuna';
            });
            @endphp

            @foreach($circuitosPorComuna as $comunaNombre => $circuitos)
            <div class="border-b border-gray-200 p-6 dark:border-gray-700">
                <!-- Header de Comuna -->
                <div class="mb-4 flex items-center justify-between rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 p-4">
                    <h4 class="text-xl font-bold text-white">
                        <i class="fas fa-users mr-2"></i>
                        Comuna: {{ $comunaNombre }}
                    </h4>
                    <span class="rounded-full bg-white px-4 py-1 text-sm font-bold text-purple-700">
                        {{ $circuitos->count() }} CC
                    </span>
                </div>

                <!-- Grid de Circuitos -->
                <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($circuitos as $circuito)
                    <div class="group overflow-hidden rounded-lg border-2 border-gray-200 bg-white transition-all hover:border-blue-500 hover:shadow-xl dark:border-gray-700 dark:bg-gray-800">
                        <div class="p-4">
                            <div class="mb-2 inline-block rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-1 text-sm font-bold text-white">
                                {{ $circuito->codigo }}
                            </div>
                            
                            <div class="mb-3 inline-block rounded-md bg-gradient-to-r from-green-500 to-green-600 px-3 py-1 text-xs font-bold text-white">
                                <i class="fas fa-check-circle mr-1"></i>Activo
                            </div>

                            <h5 class="mb-2 text-base font-bold text-gray-800 dark:text-gray-200">
                                {{ $circuito->nombre }}
                            </h5>

                            @if($circuito->descripcion)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ Str::limit($circuito->descripcion, 100) }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="rounded-xl bg-yellow-50 p-8 text-center dark:bg-yellow-900/20">
            <i class="fas fa-exclamation-triangle mb-3 text-4xl text-yellow-500"></i>
            <h3 class="text-xl font-bold text-yellow-800 dark:text-yellow-200">
                No hay circuitos comunales disponibles
            </h3>
        </div>
        @endif
    </div>
</div>
