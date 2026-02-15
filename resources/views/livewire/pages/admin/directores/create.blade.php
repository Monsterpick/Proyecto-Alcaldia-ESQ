<?php

use App\Models\Departamento;
use App\Models\Director;
use App\Services\DirectorDepartamentoService;
use App\Services\DirectorCredentialsService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Añadir director')] class extends Component {

    public string $nombre = '';
    public string $segundo_nombre = '';
    public string $apellido = '';
    public string $segundo_apellido = '';
    public string $tipo_documento = 'V';
    public string $cedula = '';
    public string $fecha_nacimiento = '';
    public string $telefono = '';
    public string $email = '';
    public string $departamento_input = '';
    public ?int $departamento_id = null;
    public string $departamento_nombre_pendiente = '';
    public bool $departamento_por_asignar = false;

    public array $departamentosSugeridos = [];
    public bool $departamentoExiste = false;
    public bool $mostrarAvisoNoExistente = false;

    public function updatedDepartamentoInput($value): void
    {
        $value = trim($value);
        $this->departamento_id = null;
        $this->departamento_nombre_pendiente = $value;

        if (strlen($value) < 2) {
            $this->departamentosSugeridos = [];
            $this->departamentoExiste = false;
            $this->mostrarAvisoNoExistente = false;
            return;
        }

        $depts = Departamento::where('nombre', 'like', '%' . $value . '%')
            ->limit(10)
            ->get(['id', 'nombre']);

        $this->departamentosSugeridos = $depts->toArray();
        $existe = $depts->contains(fn($d) => strcasecmp(trim($d->nombre), $value) === 0);
        $this->departamentoExiste = $existe;
        $this->mostrarAvisoNoExistente = !$existe && strlen($value) >= 2;
    }

    public function seleccionarDepartamento(int $id, string $nombre): void
    {
        $this->departamento_id = $id;
        $this->departamento_input = $nombre;
        $this->departamento_nombre_pendiente = '';
        $this->departamentosSugeridos = [];
        $this->departamentoExiste = true;
        $this->mostrarAvisoNoExistente = false;
    }

    public function save(): void
    {
        $reglas = [
            'nombre' => 'required|string|min:2|max:100|regex:/^[\pL\s\-]+$/u',
            'segundo_nombre' => 'nullable|string|max:100|regex:/^[\pL\s\-]*$/u',
            'apellido' => 'required|string|min:2|max:100|regex:/^[\pL\s\-]+$/u',
            'segundo_apellido' => 'nullable|string|max:100|regex:/^[\pL\s\-]*$/u',
            'tipo_documento' => 'required|in:V,E,J,P',
            'cedula' => 'required|string|min:6|max:20|regex:/^\d+$/|unique:directores,cedula',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d') . '|after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            'telefono' => 'required|string|min:10|max:30|regex:/^[\d\s\-\+\(\)]+$/',
            'email' => 'required|email|max:255',
        ];

        $mensajes = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.regex' => 'El nombre solo debe contener letras.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.min' => 'El apellido debe tener al menos 2 caracteres.',
            'apellido.regex' => 'El apellido solo debe contener letras.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.min' => 'La cédula debe tener al menos 6 dígitos.',
            'cedula.regex' => 'La cédula solo debe contener números.',
            'cedula.unique' => 'Ya existe un director con esta cédula.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'El director debe ser mayor de 18 años.',
            'fecha_nacimiento.after_or_equal' => 'La fecha de nacimiento no es válida.',
            'telefono.min' => 'El teléfono debe tener al menos 10 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe contener @ y ser válido.',
        ];

        $this->validate($reglas, $mensajes);

        if (empty($this->departamento_id) && empty(trim($this->departamento_input))) {
            if (!$this->departamento_por_asignar) {
                $this->addError('departamento_input', 'Debe indicar el departamento o marcar "Departamento por asignar".');
                return;
            }
        }

        $service = app(DirectorDepartamentoService::class);
        $deptId = $this->departamento_id;
        $deptPendiente = null;
        $esTemporal = false;

        if (!$deptId && trim($this->departamento_input) && !$this->departamento_por_asignar) {
            $dept = $service->buscarDepartamentoPorNombre($this->departamento_input);
            if ($dept) {
                $deptId = $dept->id;
            } else {
                $deptPendiente = trim($this->departamento_input);
            }
        }

        if ($deptId) {
            $dept = Departamento::with(['director', 'directorTemporal'])->find($deptId);
            try {
                $service->puedeAsignarDirectorADepartamento($dept);
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->addError('departamento_input', $e->validator->errors()->first('departamento_input'));
                return;
            }
            $esTemporal = $service->debeSerTemporal($dept);
        }

        $director = Director::create([
            'nombre' => \Illuminate\Support\Str::title(trim($this->nombre)),
            'segundo_nombre' => trim($this->segundo_nombre) ? \Illuminate\Support\Str::title(trim($this->segundo_nombre)) : null,
            'apellido' => \Illuminate\Support\Str::title(trim($this->apellido)),
            'segundo_apellido' => trim($this->segundo_apellido) ? \Illuminate\Support\Str::title(trim($this->segundo_apellido)) : null,
            'tipo_documento' => $this->tipo_documento,
            'cedula' => preg_replace('/\D/', '', $this->cedula),
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'telefono' => trim($this->telefono),
            'email' => trim($this->email),
            'departamento_id' => null,
            'departamento_nombre_pendiente' => $deptPendiente,
        ]);

        if ($deptId) {
            $dept = Departamento::find($deptId);
            $service->asignarDirector($dept, $director);
        }

        $credentialsService = app(DirectorCredentialsService::class);
        $user = $credentialsService->crearUsuarioYEnviarCredenciales($director);

        $sinDepartamento = !$deptId && !$deptPendiente;
        $mensajeExito = $sinDepartamento
            ? 'El director se ha registrado. NO POSEE DEPARTAMENTO — Departamento por asignar.'
            : ($esTemporal ? 'El director se ha registrado como director temporal.' : 'El director se ha registrado correctamente.');
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Director creado',
            'text' => $mensajeExito,
        ]);

        $this->redirect(route('admin.directores.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('admin.departamentos.index'), navigate: true);
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Departamentos y Directores', 'route' => route('admin.departamentos.index')],
            ['name' => 'Añadir director'],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Añadir director</h1>
                <p class="text-sm py-4" style="color: var(--color-text-tertiary);">Complete los datos del director. Todos los campos son obligatorios.</p>
                <div class="border-t" style="border-color: var(--color-border-primary);"></div>

                <div class="grid grid-cols-1 gap-4 pt-4 sm:grid-cols-2">
                    <div>
                        <x-input label="Nombre" wire:model="nombre" placeholder="Primera letra en mayúscula" required />
                        @error('nombre')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-input label="Segundo nombre" wire:model="segundo_nombre" placeholder="Opcional" />
                        @error('segundo_nombre')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-input label="Apellido" wire:model="apellido" placeholder="Primera letra en mayúscula" required />
                        @error('apellido')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-input label="Segundo apellido" wire:model="segundo_apellido" placeholder="Opcional" />
                        @error('segundo_apellido')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Tipo de documento</label>
                        <select wire:model="tipo_documento" required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);">
                            <option value="V">V - Venezolano</option>
                            <option value="E">E - Extranjero</option>
                            <option value="J">J - Jurídico</option>
                            <option value="P">P - Pasaporte</option>
                        </select>
                    </div>
                    <div>
                        <x-input label="Cédula" wire:model="cedula" placeholder="Solo números" required />
                        @error('cedula')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-input label="Fecha de nacimiento" type="date" wire:model="fecha_nacimiento" required />
                        @error('fecha_nacimiento')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-input label="Teléfono" wire:model="telefono" placeholder="Ej: 0414-1234567" required />
                        @error('telefono')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <x-input label="Correo electrónico" type="email" wire:model="email" placeholder="ejemplo@correo.com" required />
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2" x-data="{ open: false }">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Departamento</label>
                        <input type="text"
                               wire:model.live.debounce.200ms="departamento_input"
                               placeholder="Escriba para buscar departamentos..."
                               class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                               style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);"
                               @focus="open = true"
                               @click.outside="open = false" />
                        @error('departamento_input')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                        @if(count($departamentosSugeridos) > 0)
                            <ul class="mt-1 rounded-lg border shadow-lg overflow-hidden" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                                @foreach($departamentosSugeridos as $d)
                                    <li>
                                        <button type="button"
                                                wire:click="seleccionarDepartamento({{ $d['id'] }}, {{ json_encode($d['nombre']) }})"
                                                class="w-full px-3 py-2 text-left text-sm hover:bg-opacity-80 block"
                                                style="color: var(--color-text-primary); background-color: var(--color-bg-primary);"
                                                onmouseover="this.style.backgroundColor='var(--color-bg-hover)'"
                                                onmouseout="this.style.backgroundColor='var(--color-bg-primary)'">
                                            {{ $d['nombre'] }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($mostrarAvisoNoExistente)
                            <p class="mt-2 text-xs flex items-start gap-1.5" style="color: var(--color-amber-700);">
                                <i class="fa-solid fa-info-circle mt-0.5 shrink-0"></i>
                                <span>Departamento no existente. Al crearlo se auto asignará a este director con el nombre correspondiente al colocado.</span>
                            </p>
                        @endif

                        @if(empty(trim($departamento_input)) && !$departamento_id)
                            <label class="mt-4 flex items-start gap-3 rounded-lg border p-3 cursor-pointer" style="border-color: var(--color-amber-200); background-color: var(--color-amber-50);">
                                <input type="checkbox" wire:model="departamento_por_asignar" class="mt-1 rounded border-gray-300">
                                <span class="text-sm font-semibold" style="color: var(--color-amber-800);">DEPARTAMENTO POR ASIGNAR</span>
                            </label>
                            <p class="mt-1 text-xs" style="color: var(--color-text-tertiary);">Marque esta casilla si el director aún no tiene departamento asignado.</p>
                        @endif
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
