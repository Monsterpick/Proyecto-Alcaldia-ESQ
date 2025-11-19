<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            <i class="fas fa-edit mr-2"></i>Editar Beneficiario
        </h2>
        <a href="{{ route('admin.beneficiaries.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</x-slot>

<x-container class="py-12">
    <form wire:submit.prevent="update" class="space-y-6">
        
        <!-- Información Personal -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-bold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                <i class="fas fa-user"></i>
                Información Personal
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Primer Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="first_name" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Juan">
                    @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Segundo Nombre
                    </label>
                    <input type="text" wire:model="second_name" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Carlos">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Primer Apellido <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="last_name" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Pérez">
                    @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Segundo Apellido
                    </label>
                    <input type="text" wire:model="second_last_name" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: González">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="document_type" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="V">V - Venezolano</option>
                        <option value="E">E - Extranjero</option>
                        <option value="J">J - Jurídico</option>
                        <option value="G">G - Gubernamental</option>
                        <option value="P">P - Pasaporte</option>
                    </select>
                    @error('document_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Cédula <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="cedula" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: 12345678">
                    @error('cedula') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Fecha de Nacimiento <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-500">(Debe ser mayor de 18 años)</span>
                    </label>
                    <input type="date" wire:model="birth_date" 
                           max="{{ now()->subYears(18)->format('Y-m-d') }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    @if($birth_date)
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            <i class="fas fa-check-circle mr-1"></i>
                            Edad: {{ \Carbon\Carbon::parse($birth_date)->age }} años
                        </p>
                    @endif
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Teléfono
                    </label>
                    <input type="text" wire:model="phone" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: 0424-1234567">
                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email" wire:model="email" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: correo@ejemplo.com">
                </div>
            </div>
        </div>

        <!-- Ubicación Geográfica -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-bold text-green-600 dark:text-green-400 mb-4 flex items-center gap-2">
                <i class="fas fa-map-marker-alt"></i>
                Ubicación Geográfica
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Estado
                    </label>
                    <input type="text" value="Trujillo" disabled
                           class="w-full bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-600 dark:text-gray-400">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Municipio
                    </label>
                    <input type="text" value="Escuque" disabled
                           class="w-full bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-600 dark:text-gray-400">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Parroquia <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="parroquia_id" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione parroquia...</option>
                        @foreach($parroquias as $parroquia)
                            <option value="{{ $parroquia->id }}">{{ $parroquia->parroquia }}</option>
                        @endforeach
                    </select>
                    @error('parroquia_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Circuito Comunal -->
        <div class="bg-orange-50 dark:bg-orange-900/20 border-4 border-orange-500 rounded-lg p-6">
            <h3 class="text-lg font-bold text-orange-900 dark:text-orange-400 mb-2 flex items-center gap-2">
                <i class="fas fa-map-marked-alt"></i>
                Circuito Comunal <span class="text-red-500">*</span>
            </h3>
            
            @if($parroquia_id)
                <div class="mb-3 p-2 bg-blue-100 dark:bg-blue-900/30 rounded border border-blue-300 dark:border-blue-700">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>{{ $circuitos->count() }}</strong> Circuitos Comunales disponibles
                    </p>
                </div>

                <!-- Búsqueda con dropdown en tiempo real -->
                <div class="mb-3 relative" x-data="{ open: false }">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        <i class="fas fa-search mr-1"></i>
                        Buscar Circuito Comunal
                    </label>
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.200ms="circuito_search" 
                               @focus="open = true"
                               @click.away="open = false"
                               class="w-full bg-white dark:bg-gray-800 border-2 border-orange-300 dark:border-orange-600 rounded-lg px-4 py-3 pr-20 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500"
                               placeholder="Click para ver todos o escribe para buscar...">
                        <div class="absolute right-3 top-3 flex items-center gap-2">
                            @if($circuito_search)
                                <button type="button" 
                                        wire:click="clearCircuito"
                                        class="text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            @endif
                            <div class="text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown con resultados -->
                    @if($circuitos->count() > 0)
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-orange-300 dark:border-orange-600 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                            
                            <!-- Header del dropdown -->
                            <div class="sticky top-0 bg-orange-100 dark:bg-orange-900/40 px-4 py-2 border-b border-orange-300 dark:border-orange-600">
                                @if($circuito_search)
                                    <p class="text-xs font-semibold text-orange-800 dark:text-orange-300">
                                        <i class="fas fa-filter mr-1"></i>
                                        {{ $circuitos->count() }} resultado(s) para "<span class="font-bold">{{ $circuito_search }}</span>"
                                    </p>
                                @else
                                    <p class="text-xs font-semibold text-orange-800 dark:text-orange-300">
                                        <i class="fas fa-list mr-1"></i>
                                        {{ $circuitos->count() }} Circuitos Comunales disponibles
                                    </p>
                                @endif
                            </div>

                            <!-- Lista de resultados -->
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($circuitos as $circuito)
                                    <div wire:click="selectCircuito({{ $circuito->id }}, '{{ $circuito->codigo }} - {{ $circuito->nombre }}')"
                                         @click="open = false"
                                         class="px-4 py-3 hover:bg-orange-50 dark:hover:bg-orange-900/20 cursor-pointer transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                                                    <i class="fas fa-map-marker-alt text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                                    {{ $circuito->codigo }}
                                                </p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $circuito->nombre }}
                                                </p>
                                                @if($circuito->descripcion)
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                                        {{ $circuito->descripcion }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($circuito_search && $circuitos->count() == 0)
                        <div x-show="open"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-orange-300 dark:border-orange-600 rounded-lg shadow-xl p-4">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-search text-2xl mb-2"></i>
                                <p class="text-sm">No se encontraron circuitos comunales con "<strong>{{ $circuito_search }}</strong>"</p>
                                <p class="text-xs mt-2">Intenta con otro término de búsqueda</p>
                            </div>
                        </div>
                    @endif

                    @if(!$circuito_search)
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Click para ver todos los circuitos o escribe para filtrar
                        </p>
                    @endif
                </div>
            @else
                <p class="text-sm text-orange-700 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30 p-3 rounded">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Primero selecciona una parroquia para ver los circuitos comunales disponibles
                </p>
            @endif
            
            @error('circuito_comunal_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

            <!-- Circuito seleccionado -->
            @if($circuito_comunal_id)
                @php
                    $selectedCircuito = \App\Models\CircuitoComunal::find($circuito_comunal_id);
                @endphp
                @if($selectedCircuito)
                    <div class="mt-3 p-4 bg-green-100 dark:bg-green-900/30 rounded-lg border-2 border-green-400 dark:border-green-700">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-green-800 dark:text-green-300 mb-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Circuito Comunal Seleccionado
                                </p>
                                <p class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                    {{ $selectedCircuito->codigo }}
                                </p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedCircuito->nombre }}
                                </p>
                                @if($selectedCircuito->descripcion)
                                    <p class="text-xs text-gray-700 dark:text-gray-300 mt-1">
                                        {{ $selectedCircuito->descripcion }}
                                    </p>
                                @endif
                                <button type="button" 
                                        wire:click="clearCircuito"
                                        class="mt-2 text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                    <i class="fas fa-times mr-1"></i>Cambiar selección
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Sector -->
        <div class="bg-purple-50 dark:bg-purple-900/20 border-4 border-purple-500 rounded-lg p-6">
            <h3 class="text-lg font-bold text-purple-900 dark:text-purple-400 mb-2 flex items-center gap-2">
                <i class="fas fa-location-dot"></i>
                Sector
            </h3>
            
            @if($parroquia_id)
                <div class="mb-3 p-2 bg-purple-100 dark:bg-purple-900/30 rounded border border-purple-300 dark:border-purple-700">
                    <p class="text-sm text-purple-800 dark:text-purple-300">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>{{ $sectores->count() }}</strong> Sectores disponibles en esta parroquia
                    </p>
                </div>

                <!-- Búsqueda con dropdown en tiempo real -->
                <div class="mb-3 relative" x-data="{ openSector: false }">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        <i class="fas fa-search mr-1"></i>
                        Buscar Sector
                    </label>
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.200ms="sector_search" 
                               @focus="openSector = true"
                               @click.away="openSector = false"
                               class="w-full bg-white dark:bg-gray-800 border-2 border-purple-300 dark:border-purple-600 rounded-lg px-4 py-3 pr-20 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
                               placeholder="Click para ver todos o escribe para buscar...">
                        <div class="absolute right-3 top-3 flex items-center gap-2">
                            @if($sector_search)
                                <button type="button" 
                                        wire:click="clearSector"
                                        class="text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            @endif
                            <div class="text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown con resultados -->
                    @if($sectores->count() > 0)
                        <div x-show="openSector"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-purple-300 dark:border-purple-600 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            
                            <!-- Header del dropdown -->
                            <div class="sticky top-0 bg-purple-100 dark:bg-purple-900/40 px-4 py-2 border-b border-purple-300 dark:border-purple-600">
                                @if($sector_search)
                                    <p class="text-xs font-semibold text-purple-800 dark:text-purple-300">
                                        <i class="fas fa-filter mr-1"></i>
                                        {{ $sectores->count() }} resultado(s) para "<span class="font-bold">{{ $sector_search }}</span>"
                                    </p>
                                @else
                                    <p class="text-xs font-semibold text-purple-800 dark:text-purple-300">
                                        <i class="fas fa-list mr-1"></i>
                                        {{ $sectores->count() }} Sectores disponibles
                                    </p>
                                @endif
                            </div>

                            <!-- Lista de resultados -->
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($sectores as $sectorItem)
                                    <div wire:click="selectSector('{{ $sectorItem }}')"
                                         @click="openSector = false"
                                         class="px-4 py-3 hover:bg-purple-50 dark:hover:bg-purple-900/20 cursor-pointer transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-purple-800 flex items-center justify-center">
                                                    <i class="fas fa-location-dot text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $sectorItem }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($sector_search && $sectores->count() == 0)
                        <div x-show="openSector"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-purple-300 dark:border-purple-600 rounded-lg shadow-xl p-4">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-search text-2xl mb-2"></i>
                                <p class="text-sm">No se encontraron sectores con "<strong>{{ $sector_search }}</strong>"</p>
                                <p class="text-xs mt-2">Intenta con otro término de búsqueda</p>
                            </div>
                        </div>
                    @endif

                    @if(!$sector_search)
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Click para ver todos los sectores o escribe para filtrar
                        </p>
                    @endif
                </div>
            @else
                <p class="text-sm text-purple-700 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 p-3 rounded">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Primero selecciona una parroquia para ver los sectores disponibles
                </p>
            @endif

            <!-- Sector seleccionado -->
            @if($sector)
                <div class="mt-3 p-4 bg-green-100 dark:bg-green-900/30 rounded-lg border-2 border-green-400 dark:border-green-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-green-800 dark:text-green-300 mb-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Sector Seleccionado
                                </p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $sector }}
                                </p>
                            </div>
                        </div>
                        <button type="button" 
                                wire:click="clearSector"
                                class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                            <i class="fas fa-times mr-1"></i>Cambiar
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Dirección -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-400 mb-4 flex items-center gap-2">
                <i class="fas fa-home"></i>
                Dirección Específica
            </h3>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Punto de Referencia
                    </label>
                    <input type="text" wire:model="reference_point" 
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Cerca de la plaza, al lado de...">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Dirección Exacta
                    </label>
                    <textarea wire:model="address" rows="2"
                              class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                              placeholder="Calle, número de casa, sector específico..."></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        Estado del Beneficiario
                    </label>
                    <select wire:model="status" 
                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="active">✅ Activo</option>
                        <option value="inactive">⏸️ Inactivo</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.beneficiaries.index') }}" 
               class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i>Actualizar Beneficiario
            </button>
        </div>
    </form>
</x-container>

@script
<script>
    // Listener para beneficiario actualizado exitosamente
    $wire.on('beneficiaryUpdated', (event) => {
        Swal.fire({
            icon: 'success',
            title: '¡Beneficiario Actualizado!',
            html: `
                <p class="text-gray-700 dark:text-gray-300">
                    Los datos de <strong>${event[0].name}</strong>
                    <br>
                    <span class="text-sm text-gray-500">Cédula: ${event[0].cedula}</span>
                    <br>
                    han sido actualizados correctamente.
                </p>
            `,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3b82f6',
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        });
    });

    // Listener para errores
    $wire.on('showError', (event) => {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: event[0].message,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#ef4444',
        });
    });

    // Mostrar loading mientras se actualiza
    document.querySelector('form').addEventListener('submit', function() {
        Swal.fire({
            title: 'Actualizando...',
            html: 'Por favor espera mientras se guardan los cambios',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });
</script>
@endscript
