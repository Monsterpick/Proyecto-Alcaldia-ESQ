<?php

use Livewire\Volt\Component;
use App\Models\CircuitoComunal;
use App\Models\Parroquia;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Circuitos Comunales')] class extends Component {
    public $currentPage = 1;
    public $showEditModal = false;
    public $showCreateModal = false;
    public $editingCircuitoId = null;
    public $editNombre = '';
    public $editCodigo = '';
    public $editDescripcion = '';
    public $createParroquiaId = null;
    public $createNombre = '';
    public $createCodigo = '';
    public $createDescripcion = '';

    public function setPage($page)
    {
        $this->currentPage = $page;
    }

    public function openCreateModal($parroquiaId)
    {
        $this->createParroquiaId = $parroquiaId;
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['createParroquiaId', 'createNombre', 'createCodigo', 'createDescripcion']);
    }

    public function createCircuito()
    {
        $this->validate([
            'createNombre' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'createCodigo' => [
                'required',
                'string',
                'max:50',
                'unique:circuito_comunals,codigo',
                'regex:/^[A-Z0-9\-]+$/', // Solo mayúsculas, números y guiones
            ],
            'createParroquiaId' => 'required|exists:parroquias,id',
            'createDescripcion' => 'nullable|string|max:1000',
        ], [
            'createNombre.required' => 'El nombre es obligatorio',
            'createNombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'createNombre.max' => 'El nombre no puede exceder 255 caracteres',
            'createCodigo.required' => 'El código es obligatorio',
            'createCodigo.max' => 'El código no puede exceder 50 caracteres',
            'createCodigo.unique' => 'Este código ya está registrado',
            'createCodigo.regex' => 'El código solo puede contener letras mayúsculas, números y guiones (ej: CC-ESC-001)',
            'createParroquiaId.required' => 'Debe seleccionar una parroquia',
            'createParroquiaId.exists' => 'La parroquia seleccionada no es válida',
            'createDescripcion.max' => 'La descripción no puede exceder 1000 caracteres',
        ]);

        CircuitoComunal::create([
            'parroquia_id' => $this->createParroquiaId,
            'nombre' => trim($this->createNombre),
            'codigo' => strtoupper(trim($this->createCodigo)),
            'descripcion' => trim($this->createDescripcion),
            'is_active' => true,
        ]);

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Circuito creado exitosamente']);
        $this->closeCreateModal();
    }

    public function editCircuito($circuitoId)
    {
        $circuito = CircuitoComunal::find($circuitoId);
        if ($circuito) {
            $this->editingCircuitoId = $circuito->id;
            $this->editNombre = $circuito->nombre;
            $this->editCodigo = $circuito->codigo;
            $this->editDescripcion = $circuito->descripcion;
            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['editingCircuitoId', 'editNombre', 'editCodigo', 'editDescripcion']);
    }

    public function updateCircuito()
    {
        $this->validate([
            'editNombre' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'editCodigo' => [
                'required',
                'string',
                'max:50',
                'unique:circuito_comunals,codigo,' . $this->editingCircuitoId,
                'regex:/^[A-Z0-9\-]+$/',
            ],
            'editDescripcion' => 'nullable|string|max:1000',
        ], [
            'editNombre.required' => 'El nombre es obligatorio',
            'editNombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'editNombre.max' => 'El nombre no puede exceder 255 caracteres',
            'editCodigo.required' => 'El código es obligatorio',
            'editCodigo.max' => 'El código no puede exceder 50 caracteres',
            'editCodigo.unique' => 'Este código ya está registrado',
            'editCodigo.regex' => 'El código solo puede contener letras mayúsculas, números y guiones (ej: CC-ESC-001)',
            'editDescripcion.max' => 'La descripción no puede exceder 1000 caracteres',
        ]);

        $circuito = CircuitoComunal::find($this->editingCircuitoId);
        if ($circuito) {
            $circuito->update([
                'nombre' => trim($this->editNombre),
                'codigo' => strtoupper(trim($this->editCodigo)),
                'descripcion' => trim($this->editDescripcion),
            ]);

            $this->dispatch('alert', ['type' => 'success', 'message' => 'Circuito actualizado exitosamente']);
            $this->closeEditModal();
        }
    }

    public function deleteCircuito($circuitoId)
    {
        $circuito = CircuitoComunal::find($circuitoId);
        
        if (!$circuito) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Circuito no encontrado']);
            return;
        }

        // Verificar si hay beneficiarios o reportes asociados
        $beneficiariosCount = \App\Models\Beneficiary::where('circuito_comunal_id', $circuitoId)->count();
        $reportesCount = \App\Models\Report::where('circuito_comunal_id', $circuitoId)->count();

        if ($beneficiariosCount > 0 || $reportesCount > 0) {
            $mensaje = "No se puede eliminar este circuito porque tiene ";
            $mensaje .= $beneficiariosCount > 0 ? "{$beneficiariosCount} beneficiario(s) " : "";
            $mensaje .= ($beneficiariosCount > 0 && $reportesCount > 0) ? "y " : "";
            $mensaje .= $reportesCount > 0 ? "{$reportesCount} reporte(s) " : "";
            $mensaje .= "asociados";
            
            $this->dispatch('alert', ['type' => 'error', 'message' => $mensaje]);
            return;
        }

        $circuito->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Circuito eliminado exitosamente']);
    }

    public function with(): array
    {
        $parroquias = Parroquia::with(['circuitosComunales' => function($query) {
            $query->orderBy('codigo');
        }])->orderBy('id')->get();

        $currentParroquia = $parroquias->get($this->currentPage - 1);
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
                    <div class="flex items-center gap-3">
                        <h4 class="text-xl font-bold text-white">
                            <i class="fas fa-users mr-2"></i>
                            Comuna: {{ $comunaNombre }}
                        </h4>
                        <span class="rounded-full bg-white px-4 py-1 text-sm font-bold text-purple-700">
                            {{ $circuitos->count() }} CC
                        </span>
                    </div>
                    <button
                        wire:click="openCreateModal({{ $currentParroquia->id }})"
                        class="flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-bold text-purple-700 shadow-md transition-all hover:bg-purple-50 hover:shadow-lg">
                        <i class="fas fa-plus-circle"></i>
                        <span>Agregar CC</span>
                    </button>
                </div>

                <!-- Tabla DataTables -->
                <div class="overflow-x-auto">
                    <table class="tabla-circuitos min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300" style="width: 10%;">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300" style="width: 20%;">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300" style="width: 40%;">Descripción</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300" style="width: 10%;">Estado</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300" style="width: 20%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($circuitos as $circuito)
                            <tr class="transition-colors hover:bg-blue-50 dark:hover:bg-gray-700">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-1.5 text-sm font-mono font-bold text-white shadow-md">
                                        {{ $circuito->codigo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white break-words">{{ $circuito->nombre }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 whitespace-normal break-words leading-relaxed">
                                        {{ $circuito->descripcion ?? 'Sin descripción' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full bg-gradient-to-r from-green-500 to-green-600 px-3 py-1 text-xs font-bold text-white shadow-md">
                                        <i class="fas fa-check-circle mr-1"></i> Activo
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            wire:click="editCircuito({{ $circuito->id }})"
                                            class="inline-flex items-center gap-1 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-md transition-all hover:from-orange-600 hover:to-orange-700 hover:shadow-lg">
                                            <i class="fas fa-edit"></i>
                                            <span>Editar</span>
                                        </button>
                                        <button
                                            onclick="confirmDelete({{ $circuito->id }}, '{{ addslashes($circuito->nombre) }}')"
                                            class="inline-flex items-center gap-1 rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-3 py-2 text-sm font-semibold text-white shadow-md transition-all hover:from-red-600 hover:to-red-700 hover:shadow-lg">
                                            <i class="fas fa-trash-alt"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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

        <!-- Modal de Edición -->
        @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data @click.self="$wire.closeEditModal()">
            <div class="flex min-h-screen items-center justify-center px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
                <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
                    <div class="rounded-t-2xl border-b-4 border-orange-500 bg-gradient-to-r from-orange-600 to-red-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">
                                <i class="fas fa-edit mr-2"></i>
                                Editar Circuito Comunal
                            </h3>
                            <button wire:click="closeEditModal" class="text-white hover:text-gray-200">
                                <i class="fas fa-times text-2xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <form wire:submit.prevent="updateCircuito">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Código *</label>
                                    <input type="text" wire:model="editCodigo" placeholder="Ej: CC-ESC-001" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase" required>
                                    <p class="mt-1 text-xs text-gray-500">Solo letras mayúsculas, números y guiones. Máx. 50 caracteres</p>
                                    @error('editCodigo') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nombre *</label>
                                    <input type="text" wire:model="editNombre" placeholder="Nombre del circuito comunal" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <p class="mt-1 text-xs text-gray-500">Mínimo 3 caracteres, máximo 255 caracteres</p>
                                    @error('editNombre') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Descripción</label>
                                    <textarea wire:model="editDescripcion" rows="3" placeholder="Comuna, Centro Electoral, Sector, etc." class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Máximo 1000 caracteres (opcional)</p>
                                    @error('editDescripcion') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeEditModal" class="rounded-lg bg-gray-500 px-4 py-2 font-semibold text-white hover:bg-gray-600">
                                    Cancelar
                                </button>
                                <button type="submit" class="rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 font-semibold text-white hover:from-blue-700 hover:to-blue-800">
                                    <i class="fas fa-save mr-2"></i>
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal de Creación -->
        @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data @click.self="$wire.closeCreateModal()">
            <div class="flex min-h-screen items-center justify-center px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
                <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
                    <div class="rounded-t-2xl border-b-4 border-green-500 bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Crear Nuevo Circuito Comunal
                            </h3>
                            <button wire:click="closeCreateModal" class="text-white hover:text-gray-200">
                                <i class="fas fa-times text-2xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <form wire:submit.prevent="createCircuito">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Código *</label>
                                    <input type="text" wire:model="createCodigo" placeholder="Ej: CC-ESC-001" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase" required>
                                    <p class="mt-1 text-xs text-gray-500">Solo letras mayúsculas, números y guiones. Máx. 50 caracteres</p>
                                    @error('createCodigo') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nombre *</label>
                                    <input type="text" wire:model="createNombre" placeholder="Nombre del circuito comunal" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <p class="mt-1 text-xs text-gray-500">Mínimo 3 caracteres, máximo 255 caracteres</p>
                                    @error('createNombre') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Descripción</label>
                                    <textarea wire:model="createDescripcion" rows="3" placeholder="Comuna, Centro Electoral, Sector, etc." class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Máximo 1000 caracteres (opcional)</p>
                                    @error('createDescripcion') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeCreateModal" class="rounded-lg bg-gray-500 px-4 py-2 font-semibold text-white hover:bg-gray-600">
                                    Cancelar
                                </button>
                                <button type="submit" class="rounded-lg bg-gradient-to-r from-green-600 to-emerald-700 px-4 py-2 font-semibold text-white hover:from-green-700 hover:to-emerald-800">
                                    <i class="fas fa-save mr-2"></i>
                                    Crear
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Scripts DataTables -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            initDataTables();
        });

        document.addEventListener('livewire:navigated', function() {
            setTimeout(initDataTables, 100);
        });

        function initDataTables() {
            $('.tabla-circuitos').each(function() {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().destroy();
                }
            });

            $('.tabla-circuitos').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [
                    { targets: [3, 4], orderable: false },
                    { targets: [2], className: 'text-wrap' }
                ],
                responsive: true,
                autoWidth: false
            });
        }

        function confirmDelete(circuitoId, nombreCircuito) {
            Swal.fire({
                title: '¿Eliminar Circuito?',
                html: `¿Estás seguro de eliminar el circuito "<strong>${nombreCircuito}</strong>"?<br><span class="text-red-600">Esta acción no se puede deshacer</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Sí, eliminar',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteCircuito', circuitoId);
                    Swal.fire('Eliminado', 'El circuito ha sido eliminado', 'success');
                }
            });
        }
        </script>

        <style>
        /* Estilos para word wrap en celdas de DataTables */
        .tabla-circuitos td {
            max-width: 300px;
            word-wrap: break-word;
            white-space: normal !important;
        }

        .tabla-circuitos td:first-child,
        .tabla-circuitos td:nth-child(4),
        .tabla-circuitos td:nth-child(5) {
            white-space: nowrap !important;
        }

        .tabla-circuitos th {
            white-space: nowrap;
        }

        /* Mejorar el espaciado de las celdas con contenido largo */
        .tabla-circuitos tbody tr {
            vertical-align: top;
        }

        /* Asegurar que la descripción se vea completa */
        .tabla-circuitos td:nth-child(3) {
            max-width: 400px;
            line-height: 1.6;
        }
        </style>
    </div>
</div>
