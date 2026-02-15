<?php

use App\Models\Departamento;
use App\Models\Director;
use App\Services\DirectorDepartamentoService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Departamentos y Directores')] class extends Component {

    public function eliminarDepartamento($id): void
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Departamento eliminado',
            'text' => 'El departamento ha sido eliminado correctamente.',
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
    }

    public function quitarDirectorDelDepartamento($id): void
    {
        $director = Director::findOrFail($id);
        app(DirectorDepartamentoService::class)->quitarDirectorDelDepartamento($director);
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Director quitado del departamento',
            'text' => 'El director ha sido desasignado de este departamento. Su perfil sigue en el listado de directores.',
        ]);
    }

    public function with(): array
    {
        return [
            'departamentos' => Departamento::with(['director', 'directorTemporal'])->orderBy('nombre')->get(),
        ];
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Departamentos y Directores'],
        ]" />
    </x-slot>

    <x-container class="w-full px-4">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">
                    <i class="fa-solid fa-building-user mr-2"></i>Departamentos / Directores
                </h1>
                <p class="mt-1 text-sm" style="color: var(--color-text-tertiary);">
                    Gestión de departamentos y sus directores asignados.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-button info href="{{ route('admin.departamentos.create') }}" wire:navigate>
                    <i class="fa-solid fa-plus"></i>
                    Añadir departamento
                </x-button>
                <x-button success href="{{ route('admin.directores.create') }}" wire:navigate>
                    <i class="fa-solid fa-user-plus"></i>
                    Añadir director
                </x-button>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($departamentos as $dept)
                <div class="rounded-lg border overflow-hidden" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold" style="color: var(--color-text-primary);">{{ $dept->nombre }}</h3>
                                @if($dept->descripcion)
                                    <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">{{ $dept->descripcion }}</p>
                                @endif
                                @if($dept->servicios_generales)
                                    <p class="mt-2 text-sm">
                                        <span class="font-medium" style="color: var(--color-text-tertiary);">Servicios:</span>
                                        <span style="color: var(--color-text-secondary);">{{ $dept->servicios_generales }}</span>
                                    </p>
                                @endif
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if($dept->director)
                                        @if($dept->director->activo)
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                <i class="fa-solid fa-user-tie"></i>
                                                {{ $dept->director->nombre_completo }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium bg-slate-100 text-slate-600 dark:bg-slate-800/50 dark:text-slate-400 border border-slate-300 dark:border-slate-600">
                                                <i class="fa-solid fa-user-xmark"></i>
                                                Director inactivo
                                            </span>
                                        @endif
                                    @endif
                                    @if($dept->directorTemporal)
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-300 border border-sky-300 dark:border-sky-700">
                                            <i class="fa-solid fa-user-clock"></i>
                                            Temporal: {{ $dept->directorTemporal->nombre_completo }}
                                        </span>
                                    @endif
                                    @if(!$dept->director && !$dept->directorTemporal)
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-bold bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200 border border-amber-300 dark:border-amber-700">
                                            <i class="fa-solid fa-exclamation-triangle"></i>
                                            SIN ASIGNACIÓN DE DIRECTOR
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 shrink-0">
                                @if($dept->director || $dept->directorTemporal)
                                    @if($dept->director)
                                    <a href="{{ route('admin.directores.edit', $dept->director) }}" wire:navigate
                                       class="inline-flex items-center justify-center gap-1.5 rounded-lg border px-3 py-2 text-sm font-medium transition"
                                       style="border-color: var(--color-blue-500); color: var(--color-blue-600); background-color: var(--color-blue-50);">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Editar director
                                    </a>
                                    <button type="button"
                                            onclick="confirmarQuitarDirector({{ $dept->director->id }})"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 text-amber-700 px-3 py-2 text-sm font-medium transition hover:bg-amber-100">
                                        <i class="fa-solid fa-user-minus"></i>
                                        Quitar del departamento
                                    </button>
                                    @endif
                                    @if($dept->directorTemporal)
                                    @php $puedePromover = !$dept->director || !$dept->director->activo; @endphp
                                    @if($puedePromover)
                                    <button type="button"
                                            onclick="confirmarPromoverAPrincipal({{ $dept->directorTemporal->id }})"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-green-200 bg-green-50 text-green-700 px-3 py-2 text-sm font-medium transition hover:bg-green-100">
                                        <i class="fa-solid fa-arrow-up"></i>
                                        Asignar como director principal
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.directores.edit', $dept->directorTemporal) }}" wire:navigate
                                       class="inline-flex items-center justify-center gap-1.5 rounded-lg border px-3 py-2 text-sm font-medium transition"
                                       style="border-color: var(--color-sky-500); color: var(--color-sky-600); background-color: var(--color-sky-50);">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Editar temporal
                                    </a>
                                    <button type="button"
                                            onclick="confirmarQuitarDirector({{ $dept->directorTemporal->id }})"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 text-amber-700 px-3 py-2 text-sm font-medium transition hover:bg-amber-100">
                                        <i class="fa-solid fa-user-minus"></i>
                                        Quitar temporal
                                    </button>
                                    @endif
                                @endif
                                <a href="{{ route('admin.departamentos.edit', $dept) }}" wire:navigate
                                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border px-3 py-2 text-sm font-medium transition"
                                   style="border-color: var(--color-border-primary); color: var(--color-text-secondary);">
                                    <i class="fa-solid fa-pen"></i>
                                    Editar
                                </a>
                                <button type="button"
                                        onclick="confirmarEliminarDepartamento({{ $dept->id }})"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-red-50 text-red-700 px-3 py-2 text-sm font-medium transition hover:bg-red-100">
                                    <i class="fa-solid fa-trash"></i>
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border p-12 text-center" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                    <i class="fa-solid fa-building-user text-4xl mb-4" style="color: var(--color-text-tertiary);"></i>
                    <p class="text-lg font-medium" style="color: var(--color-text-secondary);">No hay departamentos registrados</p>
                    <p class="mt-1 text-sm" style="color: var(--color-text-tertiary);">Añade un departamento o un director para comenzar.</p>
                    <div class="mt-6 flex justify-center gap-3">
                        <x-button info href="{{ route('admin.departamentos.create') }}" wire:navigate>
                            <i class="fa-solid fa-plus"></i> Añadir departamento
                        </x-button>
                        <x-button success href="{{ route('admin.directores.create') }}" wire:navigate>
                            <i class="fa-solid fa-user-plus"></i> Añadir director
                        </x-button>
                    </div>
                </div>
            @endforelse
        </div>
    </x-container>

    @push('scripts')
        <script>
            function confirmarEliminarDepartamento(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'El departamento se eliminará y no podrás revertirlo.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('eliminarDepartamento', id);
                    }
                });
            }
            function confirmarPromoverAPrincipal(id) {
                Swal.fire({
                    title: '¿Asignar como director principal?',
                    text: 'El director temporal pasará a ser el director principal. El director inactivo (si existía) será desvinculado del departamento.',
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
            function confirmarQuitarDirector(id) {
                Swal.fire({
                    title: '¿Quitar director del departamento?',
                    html: 'Solo se desasignará de este departamento. <strong>El perfil del director NO se eliminará</strong> y permanecerá en el listado de directores.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d97706',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, quitar del departamento',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('quitarDirectorDelDepartamento', id);
                    }
                });
            }
        </script>
    @endpush
</div>
