<?php

use App\Models\Departamento;
use App\Models\Director;
use App\Services\DirectorDepartamentoService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Añadir departamento')] class extends Component {

    public string $nombre = '';
    public string $descripcion = '';
    public ?int $director_id = null;
    public string $servicios_generales = '';

    public function mount(): void
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->director_id = null;
        $this->servicios_generales = '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'nombre' => 'required|string|min:2|max:255|regex:/^[\pL\s\-\.]+$/u|unique:departamentos,nombre',
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

        $servicios = trim($validated['servicios_generales'] ?? '');
        $serviciosFormateados = $servicios
            ? implode(', ', array_map(fn ($s) => \Illuminate\Support\Str::ucfirst(trim($s)), array_filter(explode(',', $servicios))))
            : null;

        $departamento = Departamento::create([
            'nombre' => \Illuminate\Support\Str::ucfirst(trim($validated['nombre'])),
            'descripcion' => trim($validated['descripcion']) ? \Illuminate\Support\Str::ucfirst(trim($validated['descripcion'])) : null,
            'servicios_generales' => $serviciosFormateados,
        ]);

        $departamento->syncServiciosATipos();

        $service = app(DirectorDepartamentoService::class);
        if (!empty($validated['director_id'])) {
            $director = Director::findOrFail($validated['director_id']);
            $departamento->update(['director_id' => $director->id]);
            $director->update([
                'departamento_id' => $departamento->id,
                'departamento_nombre_pendiente' => null,
            ]);
        } else {
            $service->autoAsignarDirectorPendiente($departamento);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Departamento creado',
            'text' => 'El departamento se ha creado correctamente.',
        ]);

        $this->redirect(route('admin.departamentos.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('admin.departamentos.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'directoresDisponibles' => Director::whereNull('departamento_id')
                ->orWhereNotNull('departamento_nombre_pendiente')
                ->orderBy('nombre')
                ->get(),
        ];
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Departamentos y Directores', 'route' => route('admin.departamentos.index')],
            ['name' => 'Añadir departamento'],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Añadir departamento</h1>
                <p class="text-sm py-4" style="color: var(--color-text-tertiary);">Complete los datos del departamento.</p>
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
                                @if(!$d->departamento_id)
                                    <option value="{{ $d->id }}">{{ $d->nombre_completo }} ({{ $d->cedula }})</option>
                                @endif
                            @endforeach
                        </select>
                        @if($directoresDisponibles->isEmpty() || $directoresDisponibles->whereNull('departamento_id')->isEmpty())
                            <p class="mt-2 text-xs" style="color: var(--color-text-tertiary);">
                                <i class="fa-solid fa-info-circle mr-1"></i>
                                No hay directores sin asignar. Primero <a href="{{ route('admin.directores.create') }}" wire:navigate class="text-blue-600 hover:underline">registre un director</a> en la sección correspondiente.
                            </p>
                        @endif
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
                        <x-button info wire:click="save" spinner="save" label="Guardar" icon="check" interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
