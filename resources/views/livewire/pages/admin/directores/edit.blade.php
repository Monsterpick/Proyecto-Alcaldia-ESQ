<?php

use App\Models\Departamento;
use App\Models\Director;
use App\Services\DirectorDepartamentoService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('livewire.layout.admin.admin'), Title('Editar director')] class extends Component {

    public Director $director;
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

    public array $departamentosSugeridos = [];
    public bool $departamentoExiste = false;
    public bool $mostrarAvisoNoExistente = false;

    public function mount(Director $director): void
    {
        $this->director = $director;
        $this->nombre = $director->nombre;
        $this->segundo_nombre = $director->segundo_nombre ?? '';
        $this->apellido = $director->apellido;
        $this->segundo_apellido = $director->segundo_apellido ?? '';
        $this->tipo_documento = $director->tipo_documento;
        $this->cedula = $director->cedula;
        $this->fecha_nacimiento = $director->fecha_nacimiento?->format('Y-m-d') ?? '';
        $this->telefono = $director->telefono ?? '';
        $this->email = $director->email ?? '';

        if ($director->departamento) {
            $this->departamento_id = $director->departamento_id;
            $this->departamento_input = $director->departamento->nombre;
            $this->departamentoExiste = true;
        } else {
            $this->departamento_input = $director->departamento_nombre_pendiente ?? '';
            $this->departamento_nombre_pendiente = $director->departamento_nombre_pendiente ?? '';
            $this->mostrarAvisoNoExistente = !empty(trim($this->departamento_input));
        }
    }

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
            'cedula' => 'required|string|min:6|max:20|regex:/^\d+$/|unique:directores,cedula,' . $this->director->id,
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d') . '|after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            'telefono' => 'required|string|min:10|max:30|regex:/^[\d\s\-\+\(\)]+$/',
            'email' => 'required|email|max:255',
        ];

        $mensajes = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo debe contener letras.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex' => 'El apellido solo debe contener letras.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.regex' => 'La cédula solo debe contener números.',
            'cedula.unique' => 'Ya existe un director con esta cédula.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'El director debe ser mayor de 18 años.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe contener @ y ser válido.',
        ];

        $this->validate($reglas, $mensajes);

        if (empty($this->departamento_id) && empty(trim($this->departamento_input))) {
            $this->addError('departamento_input', 'Debe indicar el departamento al que pertenece el director.');
            return;
        }

        // Si no seleccionó departamento pero escribió uno, buscar existente (case-insensitive)
        $deptId = $this->departamento_id;
        $deptPendiente = null;
        $esTemporal = false;
        if (!$deptId && trim($this->departamento_input)) {
            $dept = Departamento::whereRaw('LOWER(TRIM(nombre)) = ?', [strtolower(trim($this->departamento_input))])->first();
            if ($dept) {
                $deptId = $dept->id;
            } else {
                $deptPendiente = trim($this->departamento_input);
            }
        }

        $deptAnterior = $this->director->departamento;
        $service = app(DirectorDepartamentoService::class);
        if ($deptId) {
            $dept = Departamento::with(['director', 'directorTemporal'])->find($deptId);
            $esMismoDept = $this->director->departamento_id == $deptId;
            if (!$esMismoDept) {
                try {
                    $service->puedeAsignarDirectorADepartamento($dept);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    $this->addError('departamento_input', $e->validator->errors()->first('departamento_input'));
                    return;
                }
                $esTemporal = $service->debeSerTemporal($dept);
            }
        }

        if ($deptAnterior && (!$deptId || $deptAnterior->id != $deptId)) {
            $service->quitarDirectorDelDepartamento($this->director);
        }

        $this->director->update([
            'nombre' => \Illuminate\Support\Str::title(trim($this->nombre)),
            'segundo_nombre' => trim($this->segundo_nombre) ? \Illuminate\Support\Str::title(trim($this->segundo_nombre)) : null,
            'apellido' => \Illuminate\Support\Str::title(trim($this->apellido)),
            'segundo_apellido' => trim($this->segundo_apellido) ? \Illuminate\Support\Str::title(trim($this->segundo_apellido)) : null,
            'tipo_documento' => $this->tipo_documento,
            'cedula' => preg_replace('/\D/', '', $this->cedula),
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'telefono' => trim($this->telefono),
            'email' => trim($this->email),
            'departamento_id' => $deptId,
            'departamento_nombre_pendiente' => $deptPendiente,
        ]);

        if ($deptId) {
            $dept = Departamento::find($deptId);
            $service->asignarDirector($dept, $this->director);
        }

        $mensajeExito = $esTemporal ? 'El director se ha actualizado como director temporal.' : 'El director se ha actualizado correctamente.';
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Director actualizado',
            'text' => $mensajeExito,
        ]);

        $this->redirect(route('admin.departamentos.index'), navigate: true);
    }

    public function quitarComoDirectorDeDepartamento(): void
    {
        app(DirectorDepartamentoService::class)->quitarDirectorDelDepartamento($this->director);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Director desasignado',
            'text' => 'Ha sido quitado del departamento. Su perfil permanece en el listado de directores.',
        ]);
        $this->redirect(route('admin.departamentos.index'), navigate: true);
    }

    public function eliminarDirector(): void
    {
        app(DirectorDepartamentoService::class)->quitarDirectorDelDepartamento($this->director);
        $this->director->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Director eliminado',
            'text' => 'El director ha sido eliminado correctamente.',
        ]);
        $this->redirect(route('admin.departamentos.index'), navigate: true);
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
            ['name' => 'Editar director'],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Editar director</h1>
                        <p class="text-sm py-2" style="color: var(--color-text-tertiary);">Modifique los datos del director.</p>
                    </div>
                    <button type="button"
                            onclick="confirmarEliminarDirector()"
                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-red-50 text-red-700 px-3 py-2 text-sm font-medium transition hover:bg-red-100">
                        <i class="fa-solid fa-trash"></i>
                        Eliminar
                    </button>
                </div>
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
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Departamento</label>
                        <input type="text"
                               wire:model.live.debounce.200ms="departamento_input"
                               placeholder="Escriba para buscar departamentos..."
                               class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                               style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);" />
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

                        @if($this->director->departamento)
                            <div class="mt-4 rounded-lg border p-4" style="border-color: var(--color-amber-200); background-color: var(--color-amber-50);">
                                <p class="text-sm font-medium mb-2" style="color: var(--color-amber-800);">
                                    <i class="fa-solid fa-user-minus mr-1.5"></i>
                                    Eliminar como director de departamento
                                </p>
                                <p class="text-xs mb-3" style="color: var(--color-amber-700);">
                                    Esta acción quitará al director del departamento <strong>{{ $this->director->departamento->nombre }}</strong>. El perfil permanecerá en el listado de directores y el departamento quedará sin asignación.
                                </p>
                                <button type="button"
                                        onclick="confirmarQuitarComoDirector()"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-amber-400 bg-amber-100 px-3 py-1.5 text-sm font-medium text-amber-800 transition hover:bg-amber-200">
                                    <i class="fa-solid fa-user-minus"></i>
                                    Quitar del departamento
                                </button>
                            </div>
                        @endif
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

    @push('scripts')
        <script>
            function confirmarQuitarComoDirector() {
                Swal.fire({
                    title: '¿Quitar del departamento?',
                    html: 'Se desasignará de <strong>{{ $this->director->departamento?->nombre }}</strong>. Su perfil permanecerá en el listado de directores.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d97706',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, quitar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('quitarComoDirectorDeDepartamento');
                    }
                });
            }
            function confirmarEliminarDirector() {
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
                        @this.call('eliminarDirector');
                    }
                });
            }
        </script>
    @endpush
</div>
