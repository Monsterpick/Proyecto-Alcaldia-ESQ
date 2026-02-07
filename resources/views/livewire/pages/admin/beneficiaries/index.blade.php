<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            <i class="fas fa-user-group mr-2"></i>
            Beneficiarios
        </h2>
        <a href="{{ route('admin.beneficiaries.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Añadir Beneficiario
        </a>
    </div>
</x-slot>

<x-container class="py-12">
    
    <!-- Mensaje Flash -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Beneficiarios</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Activos</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Inactivos</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <i class="fas fa-pause-circle text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Búsqueda -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Buscar
                </label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Nombre, cédula, teléfono, email..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            
            <!-- Filtro Estado -->
            <div class="w-full md:w-48">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Estado
                </label>
                <select wire:model.live="statusFilter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Beneficiario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cédula</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($beneficiaries as $beneficiary)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($beneficiary->first_name, 0, 1)) }}{{ strtoupper(substr($beneficiary->last_name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $beneficiary->full_name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-700 dark:text-gray-300">{{ $beneficiary->full_cedula }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                @if($beneficiary->phone)
                                <p class="text-gray-700 dark:text-gray-300"><i class="fas fa-phone text-gray-400 mr-2"></i>{{ $beneficiary->phone }}</p>
                                @endif
                                @if($beneficiary->email)
                                <p class="text-gray-500 dark:text-gray-400 text-xs"><i class="fas fa-envelope text-gray-400 mr-2"></i>{{ $beneficiary->email }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="text-gray-700 dark:text-gray-300 font-medium">
                                    <i class="fas fa-map-marked-alt text-blue-500 mr-1"></i>
                                    {{ $beneficiary->circuitoComunal->codigo ?? 'N/A' }}
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs">
                                    <i class="fas fa-map-pin text-orange-500 mr-1"></i>
                                    {{ $beneficiary->parroquia->parroquia ?? 'N/A' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($beneficiary->status === 'active')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Activo
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                                <i class="fas fa-pause-circle mr-1"></i>Inactivo
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.beneficiaries.edit', $beneficiary->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    Editar
                                </a>
                                <button wire:click="toggleStatus({{ $beneficiary->id }})" 
                                        type="button"
                                        wire:loading.attr="disabled"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="toggleStatus({{ $beneficiary->id }})">Cambiar Estado</span>
                                    <span wire:loading wire:target="toggleStatus({{ $beneficiary->id }})">Procesando...</span>
                                </button>
                                <button onclick="confirm('¿Estás seguro de eliminar este beneficiario?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteBeneficiary({{ $beneficiary->id }})"
                                        type="button"
                                        wire:loading.attr="disabled"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="deleteBeneficiary">Eliminar</span>
                                    <span wire:loading wire:target="deleteBeneficiary">Eliminando...</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-users text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron beneficiarios</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Intenta ajustar los filtros o añade un nuevo beneficiario</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $beneficiaries->links() }}
        </div>
    </div>

</x-container>
