<?php

use App\Models\Director;
use App\Services\DirectorDepartamentoService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

new #[Layout('livewire.layout.admin.admin'), Title('Listado de Directores')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function toggleActivo(int $id): void
    {
        $director = Director::findOrFail($id);
        $director->update(['activo' => !$director->activo]);
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Estado actualizado',
            'text' => 'El director ha sido marcado como ' . ($director->activo ? 'activo' : 'inactivo') . '.',
        ]);
    }

    public function asignarComoDirectorPrincipal(int $id): void
    {
        $director = Director::findOrFail($id);
        $ok = app(DirectorDepartamentoService::class)->promoverTemporalAPrincipal($director);
        if (!$ok) {
            return;
        }
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Director principal asignado',
            'text' => 'El director ha sido promovido como director principal del departamento.',
        ]);
        $this->resetPage();
    }

    public function eliminarDirector(int $id): void
    {
        $director = Director::findOrFail($id);
        app(DirectorDepartamentoService::class)->quitarDirectorDelDepartamento($director);
        $director->delete();
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Director eliminado',
            'text' => 'El director ha sido eliminado correctamente.',
        ]);
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Director::with(['departamento', 'departamento.director']);

        if ($this->search) {
            $q = trim($this->search);
            $query->where(function ($qb) use ($q) {
                $qb->where('nombre', 'like', "%{$q}%")
                    ->orWhere('segundo_nombre', 'like', "%{$q}%")
                    ->orWhere('apellido', 'like', "%{$q}%")
                    ->orWhere('segundo_apellido', 'like', "%{$q}%")
                    ->orWhere('cedula', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhereHas('departamento', fn($d) => $d->where('nombre', 'like', "%{$q}%"));
            });
        }

        if ($this->statusFilter === 'activo') {
            $query->where('activo', true);
        } elseif ($this->statusFilter === 'inactivo') {
            $query->where('activo', false);
        }

        $directores = $query->orderBy('apellido')->orderBy('nombre')->paginate(15);

        $stats = [
            'total' => Director::count(),
            'activos' => Director::where('activo', true)->count(),
            'inactivos' => Director::where('activo', false)->count(),
        ];

        return [
            'directores' => $directores,
            'stats' => $stats,
        ];
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Directores', 'route' => route('admin.departamentos.index')],
            ['name' => 'Listado de Directores'],
        ]" />
    </x-slot>

    <x-container class="w-full px-4">
        {{-- Header profesional --}}
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight" style="color: var(--color-text-primary);">
                        <i class="fa-solid fa-address-card mr-2" style="color: var(--color-blue-600);"></i>
                        Listado de Directores
                    </h1>
                    <p class="mt-1 text-sm" style="color: var(--color-text-tertiary);">
                        Gestión profesional de directores de departamento. Visualice, edite y administre el estado de cada director.
                    </p>
                </div>
                <a href="{{ route('admin.directores.create') }}" wire:navigate
                   class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm transition"
                   style="background-color: var(--color-blue-600); color: white;">
                    <i class="fa-solid fa-user-plus"></i>
                    Añadir director
                </a>
            </div>
        </div>

        {{-- Tarjetas de estadísticas --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border p-5 shadow-sm" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: var(--color-text-tertiary);">Total Directores</p>
                        <p class="mt-1 text-2xl font-bold" style="color: var(--color-text-primary);">{{ $stats['total'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl" style="background-color: var(--color-blue-50);">
                        <i class="fa-solid fa-users text-xl" style="color: var(--color-blue-600);"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border p-5 shadow-sm" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: var(--color-text-tertiary);">Activos</p>
                        <p class="mt-1 text-2xl font-bold text-green-600">{{ $stats['activos'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                        <i class="fa-solid fa-circle-check text-xl text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border p-5 shadow-sm" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: var(--color-text-tertiary);">Inactivos</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">{{ $stats['inactivos'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50">
                        <i class="fa-solid fa-pause-circle text-xl text-amber-600"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="mb-6 rounded-xl border p-4" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium" style="color: var(--color-text-secondary);">Buscar (nombre, apellido, cédula, email, departamento)</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar directores..."
                           class="w-full rounded-lg border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                           style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color: var(--color-text-secondary);">Estado</label>
                    <select wire:model.live="statusFilter"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                            style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);">
                        <option value="">Todos</option>
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Tabla profesional --}}
        <div class="overflow-hidden rounded-xl border shadow-sm" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y text-left text-sm" style="border-color: var(--color-border-primary);">
                    <thead class="text-xs font-semibold uppercase tracking-wider" style="background-color: var(--color-bg-secondary); color: var(--color-text-tertiary);">
                        <tr>
                            <th class="px-6 py-4">Director</th>
                            <th class="px-6 py-4">Documento</th>
                            <th class="px-6 py-4">Departamento</th>
                            <th class="px-6 py-4">Contacto</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="color: var(--color-text-primary); border-color: var(--color-border-primary);">
                        @forelse($directores as $d)
                            <tr class="transition-colors hover:bg-opacity-95" style="background-color: var(--color-bg-primary);" onmouseover="this.style.backgroundColor='var(--color-bg-hover)'" onmouseout="this.style.backgroundColor='var(--color-bg-primary)'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold text-white" style="background: linear-gradient(135deg, var(--color-blue-600), var(--color-blue-800));">
                                            {{ strtoupper(substr($d->nombre, 0, 1)) }}{{ strtoupper(substr($d->apellido ?? '', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $d->nombre_completo }}</p>
                                            <p class="text-xs" style="color: var(--color-text-tertiary);">{{ $d->tipo_documento }}-{{ $d->cedula }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm">{{ $d->tipo_documento }}-{{ $d->cedula }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($d->departamento)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium {{ $d->esDirectorTemporal() ? 'bg-sky-50 text-sky-700' : '' }}" style="{{ !$d->esDirectorTemporal() ? 'background-color: var(--color-blue-50); color: var(--color-blue-700);' : '' }}">
                                            <i class="fa-solid fa-building mr-1.5"></i>
                                            {{ $d->departamento->nombre }}
                                            @if($d->esDirectorTemporal())
                                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold bg-sky-200 text-sky-800">Temporal</span>
                                            @endif
                                        </span>
                                    @elseif($d->departamento_nombre_pendiente)
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-medium bg-amber-50 text-amber-700">
                                            <i class="fa-solid fa-clock mr-1.5"></i>
                                            Pendiente: {{ $d->departamento_nombre_pendiente }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800/60 dark:text-slate-300 border border-slate-300 dark:border-slate-600">
                                            <i class="fa-solid fa-building-circle-exclamation"></i>
                                            DEPARTAMENTO POR ASIGNAR
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5 text-xs">
                                        @if($d->telefono)
                                            <p><i class="fa-solid fa-phone mr-1.5 w-4 text-slate-400"></i>{{ $d->telefono }}</p>
                                        @endif
                                        @if($d->email)
                                            <p><i class="fa-solid fa-envelope mr-1.5 w-4 text-slate-400"></i>{{ Str::limit($d->email, 30) }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($d->activo)
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            <i class="fa-solid fa-circle-check"></i>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                            <i class="fa-solid fa-pause-circle"></i>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        @if($d->esDirectorTemporal() && (!$d->departamento->director || !$d->departamento->director->activo))
                                        <button type="button"
                                                onclick="confirmarPromoverAPrincipal({{ $d->id }})"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 transition hover:bg-green-100">
                                            <i class="fa-solid fa-arrow-up"></i>
                                            Asignar como director principal
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.directores.edit', $d) }}" wire:navigate
                                           class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-medium transition"
                                           style="border-color: var(--color-blue-500); color: var(--color-blue-600); background-color: var(--color-blue-50);">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            Editar
                                        </a>
                                        <button type="button"
                                                wire:click="toggleActivo({{ $d->id }})"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-medium transition {{ $d->activo ? 'border-amber-400 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-green-400 bg-green-50 text-green-700 hover:bg-green-100' }}">
                                            <span wire:loading.remove wire:target="toggleActivo({{ $d->id }})">
                                                {{ $d->activo ? 'Desactivar' : 'Activar' }}
                                            </span>
                                            <span wire:loading wire:target="toggleActivo({{ $d->id }})">...</span>
                                        </button>
                                        <button type="button"
                                                onclick="confirmarEliminarDirector({{ $d->id }})"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100">
                                            <i class="fa-solid fa-trash"></i>
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <i class="fa-solid fa-address-card text-4xl mb-4" style="color: var(--color-text-tertiary);"></i>
                                    <p class="text-lg font-medium" style="color: var(--color-text-secondary);">No hay directores registrados</p>
                                    <p class="mt-1 text-sm" style="color: var(--color-text-tertiary);">Añada un director para comenzar.</p>
                                    <a href="{{ route('admin.directores.create') }}" wire:navigate
                                       class="mt-4 inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white"
                                       style="background-color: var(--color-blue-600);">
                                        <i class="fa-solid fa-user-plus"></i>
                                        Añadir director
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($directores->hasPages())
                <div class="border-t px-6 py-4" style="border-color: var(--color-border-primary);">
                    {{ $directores->links() }}
                </div>
            @endif
        </div>
    </x-container>

    @push('scripts')
        <script>
            function confirmarPromoverAPrincipal(id) {
                Swal.fire({
                    title: '¿Asignar como director principal?',
                    text: 'El director temporal pasará a ser el director principal. El director inactivo (si existía) será desvinculado.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, asignar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('asignarComoDirectorPrincipal', id);
                    }
                });
            }
            function confirmarEliminarDirector(id) {
                Swal.fire({
                    title: 'ACCIÓN IRREVERSIBLE',
                    html: '<p class="text-left"><strong>Se eliminará de forma permanente:</strong></p><ul class="text-left list-disc pl-5 mt-2 space-y-1"><li>El director</li><li>Su usuario de acceso al sistema</li><li>Todos los registros asociados</li></ul><p class="text-left mt-3 text-red-600 font-medium">No quedará rastro. Esta acción no se puede deshacer.</p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar definitivamente',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('eliminarDirector', id);
                    }
                });
            }
        </script>
    @endpush
</div>
