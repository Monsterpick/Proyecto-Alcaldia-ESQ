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


    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table id="beneficiariesTable" class="w-full">
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
                                        onclick="event.stopPropagation()"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    Cambiar Estado
                                </button>
                                <button wire:click="confirmDelete({{ $beneficiary->id }})" 
                                        onclick="event.stopPropagation()"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    Eliminar
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
    </div>

</x-container>

<!-- Modal Eliminar -->
@if($showDeleteModal && $selectedBeneficiary)
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50" 
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     wire:click="showDeleteModal = false">
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 max-w-md w-full mx-4" wire:click.stop>
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">¿Eliminar Beneficiario?</h3>
        <p class="text-gray-600 dark:text-gray-400 text-center mb-4">
            ¿Estás seguro de que deseas eliminar a <strong class="text-gray-900 dark:text-white">{{ $selectedBeneficiary->full_name }}</strong>?
        </p>
        <p class="text-red-600 dark:text-red-400 text-center font-bold mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Esta acción es irreversible
        </p>
        
        <div class="flex gap-3">
            <button wire:click="showDeleteModal = false" 
                    class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg transition-colors">
                Cancelar
            </button>
            <button wire:click="deleteBeneficiary" 
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                Eliminar
            </button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<!-- jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    let table;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        table = $('#beneficiariesTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[1, 'asc']], // Ordenar por nombre
            columnDefs: [
                { orderable: false, targets: [0, 5] }, // Desabilitar ordenamiento en avatar y acciones
                { width: '320px', targets: 5 } // Ancho fijo para columna de acciones
            ],
            pageLength: 10,
            autoWidth: false,
            dom: '<"flex items-center justify-between mb-4"lf>rtip'
        });
    });

    // Capturar todos los eventos Livewire
    document.addEventListener('livewire:initialized', () => {
        // Listeners para reinicializar DataTable después de operaciones CRUD
        Livewire.on('beneficiary-created', () => {
            setTimeout(() => {
                            Primer Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="first_name" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: Juan">
                        @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Segundo Nombre
                        </label>
                        <input type="text" wire:model="second_name" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: Carlos">
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Apellido <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="last_name" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: Pérez">
                        @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Tipo de Documento <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="document_type" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: 12345678">
                        @error('cedula') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Teléfono
                        </label>
                        <input type="text" wire:model="phone" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: 0424-1234567">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Correo Electrónico
                        </label>
                        <input type="email" wire:model="email" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: correo@ejemplo.com">
                    </div>
                </div>
            </div>

            <!-- Ubicación Geográfica -->
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <h4 class="text-lg font-bold text-green-900 dark:text-green-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    Ubicación Geográfica
                </h4>
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
                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccione parroquia...</option>
                            @foreach($parroquias as $parroquia)
                                <option value="{{ $parroquia->id }}">{{ $parroquia->parroquia }}</option>
                            @endforeach
                        </select>
                        @error('parroquia_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Circuito Comunal (DESTACADO) -->
            <div class="bg-orange-50 dark:bg-orange-900/20 border-4 border-orange-500 rounded-lg p-4">
                <h4 class="text-lg font-bold text-orange-900 dark:text-orange-400 mb-2 flex items-center gap-2">
                    <i class="fas fa-map-marked-alt"></i>
                    Circuito Comunal <span class="text-red-500">*</span>
                </h4>
                
                @if($parroquia_id)
                    <div class="mb-3 p-2 bg-blue-100 dark:bg-blue-900/30 rounded border border-blue-300 dark:border-blue-700">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>{{ $circuitos->count() }}</strong> Circuitos Comunales disponibles en esta parroquia
                        </p>
                    </div>
                @endif

                <select wire:model="circuito_comunal_id" 
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Seleccione primero una parroquia...</option>
                    @foreach($circuitos as $circuito)
                        <option value="{{ $circuito->id }}">{{ $circuito->codigo }} - {{ $circuito->nombre }}</option>
                    @endforeach
                </select>
                @error('circuito_comunal_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                @if($circuito_comunal_id)
                    @php
                        $selectedCircuito = $circuitos->firstWhere('id', $circuito_comunal_id);
                    @endphp
                    @if($selectedCircuito)
                        <div class="mt-3 p-3 bg-green-100 dark:bg-green-900/30 rounded border border-green-300 dark:border-green-700">
                            <p class="text-sm text-green-800 dark:text-green-300">
                                <i class="fas fa-check-circle mr-1"></i>
                                <strong>Circuito seleccionado:</strong> {{ $selectedCircuito->descripcion }}
                            </p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Dirección Específica -->
            <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-home"></i>
                    Dirección Específica
                </h4>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Punto de Referencia
                        </label>
                        <input type="text" wire:model="reference_point" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: Cerca de la plaza, al lado de...">
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Dirección Exacta
                        </label>
                        <textarea wire:model="address" rows="2"
                                  class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Calle, número de casa, sector específico..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Estado del Beneficiario -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                    Estado del Beneficiario
                </label>
                <select wire:model="status" 
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="active">✅ Activo</option>
                    <option value="inactive">⏸️ Inactivo</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" wire:click="showCreateModal = false; showEditModal = false"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    {{ $showCreateModal ? 'Guardar' : 'Actualizar' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Ver Detalles -->
@if($showViewModal && $selectedBeneficiary)
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" 
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     wire:click="showViewModal = false">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" wire:click.stop>
        <!-- Header -->
        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between z-10">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-user mr-2"></i>
                Detalles del Beneficiario
            </h3>
            <button wire:click="showViewModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Información Personal -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h4 class="text-lg font-bold text-blue-900 dark:text-blue-400 mb-3 flex items-center gap-2">
                    <i class="fas fa-user"></i>
                    Información Personal
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nombre Completo</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Cédula</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->full_cedula }}</p>
                    </div>
                    @if($selectedBeneficiary->phone)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Teléfono</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->phone }}</p>
                    </div>
                    @endif
                    @if($selectedBeneficiary->email)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ubicación -->
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <h4 class="text-lg font-bold text-green-900 dark:text-green-400 mb-3 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    Ubicación
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Estado</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->state }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Municipio</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->municipality }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Parroquia</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->parroquia->parroquia ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Circuito Comunal</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->circuitoComunal->codigo ?? 'N/A' }} - {{ $selectedBeneficiary->circuitoComunal->nombre ?? '' }}</p>
                    </div>
                    @if($selectedBeneficiary->reference_point)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Punto de Referencia</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->reference_point }}</p>
                    </div>
                    @endif
                    @if($selectedBeneficiary->address)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Dirección Exacta</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedBeneficiary->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estado -->
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Estado</p>
                @if($selectedBeneficiary->status === 'active')
                <span class="px-4 py-2 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800 inline-flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>Activo
                </span>
                @else
                <span class="px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800 inline-flex items-center gap-2">
                    <i class="fas fa-pause-circle"></i>Inactivo
                </span>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button wire:click="showViewModal = false" 
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endif

<!-- Modal Eliminar -->
@if($showDeleteModal && $selectedBeneficiary)
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50" 
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     wire:click="showDeleteModal = false">
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 max-w-md w-full mx-4" wire:click.stop>
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">¿Eliminar Beneficiario?</h3>
        <p class="text-gray-600 dark:text-gray-400 text-center mb-4">
            ¿Estás seguro de que deseas eliminar a <strong class="text-gray-900 dark:text-white">{{ $selectedBeneficiary->full_name }}</strong>?
        </p>
        <p class="text-red-600 dark:text-red-400 text-center font-bold mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Esta acción es irreversible
        </p>
        
        <div class="flex gap-3">
            <button wire:click="showDeleteModal = false" 
                    class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg transition-colors">
                Cancelar
            </button>
            <button wire:click="deleteBeneficiary" 
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                Eliminar
            </button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<!-- jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    let table;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        table = $('#beneficiariesTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[1, 'asc']], // Ordenar por nombre
            columnDefs: [
                { orderable: false, targets: [0, 5] }, // Desabilitar ordenamiento en avatar y acciones
                { width: '320px', targets: 5 } // Ancho fijo para columna de acciones
            ],
            pageLength: 10,
            autoWidth: false,
            dom: '<"flex items-center justify-between mb-4"lf>rtip'
        });
    });

    // Capturar todos los eventos Livewire
    document.addEventListener('livewire:initialized', () => {
        // Listeners para reinicializar DataTable después de operaciones CRUD
        Livewire.on('beneficiary-created', () => {
            setTimeout(() => {
                if (table) {
                    table.destroy();
                }
                table = $('#beneficiariesTable').DataTable({
                    responsive: true,
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 5] },
                        { width: '320px', targets: 5 }
                    ],
                    pageLength: 10,
                    autoWidth: false,
                    dom: '<"flex items-center justify-between mb-4"lf>rtip'
                });
            }, 100);
        });
        
        Livewire.on('beneficiary-updated', () => {
            setTimeout(() => {
                if (table) {
                    table.destroy();
                }
                table = $('#beneficiariesTable').DataTable({
                    responsive: true,
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 5] },
                        { width: '320px', targets: 5 }
                    ],
                    pageLength: 10,
                    autoWidth: false,
                    dom: '<"flex items-center justify-between mb-4"lf>rtip'
                });
            }, 100);
        });
        
        // Sweet Alert para éxito
        Livewire.on('swal:success', (event) => {
            Swal.fire({
                icon: 'success',
                title: event[0]?.title || '¡Éxito!',
                text: event[0]?.text || 'Operación completada exitosamente',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                // Reiniciar tabla después de mostrar SweetAlert
                setTimeout(() => {
                    if (table) {
                        table.destroy();
                    }
                    table = $('#beneficiariesTable').DataTable({
                        responsive: true,
                        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                        order: [[1, 'asc']],
                        columnDefs: [
                            { orderable: false, targets: [0, 5] },
                            { width: '320px', targets: 5 }
                        ],
                        pageLength: 10,
                        autoWidth: false,
                        dom: '<"flex items-center justify-between mb-4"lf>rtip'
                    });
                }, 100);
            });
        });
    });
</script>
@endpush
