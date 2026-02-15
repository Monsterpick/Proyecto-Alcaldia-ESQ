<?php

use App\Models\Departamento;
use App\Models\Director;
use App\Services\DirectorDepartamentoService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Editar departamento')] class extends Component {

    public Departamento $departamento;
    public string $nombre = '';
    public string $descripcion = '';
    public ?int $director_id = null;
    public string $servicios_generales = '';

    public function mount(Departamento $departamento): void
    {
        $this->departamento = $departamento;
        $this->nombre = $departamento->nombre;
        $this->descripcion = $departamento->descripcion ?? '';
        $this->director_id = $departamento->director_id;
        $this->servicios_generales = $departamento->servicios_generales ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'nombre' => 'required|string|min:2|max:255|regex:/^[\pL\s\-\.]+$/u|unique:departamentos,nombre,' . $this->departamento->id,
            'descripcion' => 'nullable|string|min:3|max:2000',
            'director_id' => 'nullable|exists:directores,id',
            'servicios_generales' => 'nullable|string|max:1000',
        ], [
            'nombre.required' => 'El nombre del departamento es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',
            'nombre.unique' => 'Ya existe un departamento con ese nombre.',
            'descripcion.min' => 'La descripción debe tener al menos 3 caracteres.',
        ]);

        $directorAnterior = $this->departamento->director;
        $temporalAnterior = $this->departamento->directorTemporal;
        if ($directorAnterior && $directorAnterior->id != $validated['director_id']) {
            $directorAnterior->update(['departamento_id' => null]);
        }
        if ($temporalAnterior && $temporalAnterior->id != $validated['director_id']) {
            $temporalAnterior->update(['departamento_id' => null]);
            $this->departamento->update(['director_temporal_id' => null]);
        }

        $servicios = trim($validated['servicios_generales'] ?? '');
        $serviciosFormateados = $servicios
            ? implode(', ', array_map(fn ($s) => \Illuminate\Support\Str::ucfirst(trim($s)), array_filter(explode(',', $servicios))))
            : null;

        $this->departamento->update([
            'nombre' => \Illuminate\Support\Str::ucfirst(trim($validated['nombre'])),
            'descripcion' => trim($validated['descripcion']) ? \Illuminate\Support\Str::ucfirst(trim($validated['descripcion'])) : null,
            'servicios_generales' => $serviciosFormateados,
            'director_id' => $validated['director_id'],
            'director_temporal_id' => null,
        ]);

        $this->departamento->syncServiciosATipos();

        if (!empty($validated['director_id'])) {
            $director = Director::findOrFail($validated['director_id']);
            $director->update([
                'departamento_id' => $this->departamento->id,
                'departamento_nombre_pendiente' => null,
            ]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Departamento actualizado',
            'text' => 'El departamento se ha actualizado correctamente.',
        ]);

        $this->redirect(route('admin.departamentos.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('admin.departamentos.index'), navigate: true);
    }

    public function with(): array
    {
        $directores = Director::where(function ($q) {
            $q->whereNull('departamento_id')
                ->orWhere('departamento_id', $this->departamento->id)
                ->orWhereNotNull('departamento_nombre_pendiente');
        })->orderBy('nombre')->get();

        return [
            'directoresDisponibles' => $directores,
        ];
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Departamentos y Directores', 'route' => route('admin.departamentos.index')],
            ['name' => 'Editar departamento'],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Editar departamento</h1>
                <p class="text-sm py-4" style="color: var(--color-text-tertiary);">Modifique los datos del departamento.</p>
                <div class="border-t" style="border-color: var(--color-border-primary);"></div>

                <div class="grid grid-cols-1 gap-4 pt-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-input label="Nombre del departamento" wire:model="nombre" placeholder="Ej: Atención al Ciudadano" required />
                        @error('nombre')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Descripción corta y concisa</label>
                        <textarea wire:model="descripcion" rows="2" placeholder="Breve descripción del departamento (la primera letra en mayúscula)"
                                  class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                  style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);"></textarea>
                        @error('descripcion')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Director</label>
                        <select wire:model="director_id"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);">
                            <option value="">-- Sin asignar --</option>
                            @foreach($directoresDisponibles as $d)
                                @if(!$d->departamento_id || $d->departamento_id == $this->departamento->id)
                                    <option value="{{ $d->id }}">{{ $d->nombre_completo }} ({{ $d->cedula }})</option>
                                @endif
                            @endforeach
                        </select>
                        @error('director_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Servicios generales</label>
                        <textarea wire:model="servicios_generales" rows="5" placeholder="Liste cada servicio separado por coma. Ej: Aguas blancas, Aguas servidas, Mantenimiento y reparación, Factibilidad técnica"
                                  class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                  style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);"></textarea>
                        <p class="mt-1 text-xs" style="color: var(--color-text-tertiary);">Detallado y global. Separe cada servicio con coma (,).</p>
                        @error('servicios_generales')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end space-x-2 pt-4">
                        <x-button info wire:click="save" spinner="save" label="Actualizar" icon="check" interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
