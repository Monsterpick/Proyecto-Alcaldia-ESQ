<?php

use App\Http\Controllers\SolicitudPdfController;
use App\Models\Departamento;
use App\Models\Solicitud;
use App\Services\EvolutionService;
use App\Services\GroqAIService;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

new #[Layout('livewire.layout.admin.admin'), Title('Solicitudes de Alcaldía Digital')] class extends Component {
    use WithPagination;

    public $search = '';
    public $filterEstado = 'all';
    public $filterDepartamento = '';

    public function updatedFilterEstado(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDepartamento(): void
    {
        $this->resetPage();
    }

    /** Propiedades para el modal de confirmar */
    public ?int $solicitudId = null;
    public string $email = '';
    public string $whatsapp = '';
    public string $tipo_solicitud = '';
    public string $parroquia = '';
    public string $circuito = '';
    public string $sector = '';
    public string $direccion_exacta = '';
    public string $descripcion = '';
    public string $estado = '';
    public string $respuesta = '';

    public function abrirModalConfirmar($id): void
    {
        $solicitud = Solicitud::with(['ciudadano', 'tipoSolicitud', 'parroquia', 'circuitoComunal'])->findOrFail($id);
        $this->solicitudId = $solicitud->id;
        $this->email = $solicitud->ciudadano?->email ?? '';
        $this->whatsapp = $solicitud->ciudadano?->whatsapp ?? $solicitud->ciudadano?->telefono_movil ?? '';
        $this->tipo_solicitud = $solicitud->tipoSolicitud?->nombre ?? '';
        $this->parroquia = $solicitud->parroquia?->parroquia ?? '';
        $this->circuito = $solicitud->circuitoComunal?->nombre ?? '';
        $this->sector = $solicitud->sector ?? '';
        $this->direccion_exacta = $solicitud->direccion_exacta ?? $solicitud->direccion ?? '';
        $this->descripcion = $solicitud->descripcion ?? '';
        $this->estado = '';
        $this->respuesta = '';
        $this->dispatch('abrir-confirmar-modal-solicitud');
    }

    public function confirmar(): void
    {
        $this->validate([
            'estado' => 'required|in:aprobado,rechazado',
            'respuesta' => 'required|string|max:1000',
        ], [
            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado debe ser Aprobar o Rechazar.',
            'respuesta.required' => 'La respuesta u observaciones son obligatorias.',
            'respuesta.max' => 'La respuesta no puede superar 1000 caracteres.',
        ]);

        $solicitud = Solicitud::with('ciudadano')->findOrFail($this->solicitudId);
        $solicitud->update([
            'estado' => $this->estado,
            'respuesta' => $this->respuesta,
            'fecha_respuesta' => now(),
        ]);

        // Notificar al ciudadano por WhatsApp (aprobado/rechazado + observación)
        $this->enviarWhatsAppResolucion($solicitud);

        // Notificar al director que aprobó/rechazó (confirmación de su acción)
        $this->enviarWhatsAppResolucionAlDirector($solicitud);

        // Enviar PDF al ciudadano solo cuando ya está aprobado o rechazado
        $this->enviarPdfAlCiudadano($solicitud);

        $this->dispatch('cerrar-confirmar-modal');
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Solicitud actualizada',
            'text' => 'La solicitud ha sido ' . ($this->estado === 'aprobado' ? 'aprobada' : 'rechazada') . ' y se guardó la respuesta.',
        ]);
        $this->resetPage();
    }

    /**
     * Enviar al ciudadano WhatsApp con el resultado (aprobado/rechazado) y la observación.
     * Mensaje generado por Groq si está configurado; si no, mensaje predeterminado.
     */
    private function enviarWhatsAppResolucion(Solicitud $solicitud): void
    {
        $ciudadano = $solicitud->ciudadano;
        if (!$ciudadano || empty($ciudadano->whatsapp)) {
            Log::warning('No se envió WhatsApp de resolución: ciudadano sin WhatsApp', ['solicitud_id' => $solicitud->id]);
            return;
        }

        try {
            $evolutionService = app(EvolutionService::class);
            $groq = app(GroqAIService::class);
            $numero = $ciudadano->getWhatsappNormalizado();
            $nombre = trim($ciudadano->nombre . ' ' . $ciudadano->apellido);
            $tipoNombre = $solicitud->tipoSolicitud?->nombre ?? 'atención ciudadana';
            $datos = [
                'nombre' => $nombre,
                'tipo_solicitud' => $tipoNombre,
                'observaciones' => $solicitud->respuesta ?? '',
            ];

            if ($solicitud->estado === 'aprobado') {
                $res = $groq->generarMensajeAprobacion($datos, 'atencion_ciudadana');
            } else {
                $res = $groq->generarMensajeRechazo($datos, 'atencion_ciudadana');
            }

            $mensaje = $res['mensaje'] ?? $this->mensajeResolucionPredeterminado($nombre, $solicitud->estado, $solicitud->respuesta ?? '');

            $evolutionService->sendMessage($numero, $mensaje);
            Log::info('WhatsApp resolución enviada al ciudadano', [
                'solicitud_id' => $solicitud->id,
                'estado' => $solicitud->estado,
                'mensaje_ia' => $res['es_ia'] ?? false,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar WhatsApp de resolución', [
                'error' => $e->getMessage(),
                'solicitud_id' => $solicitud->id,
            ]);
        }
    }

    /**
     * Mensaje predeterminado de resolución si Groq falla
     */
    private function mensajeResolucionPredeterminado(string $nombre, string $estado, string $respuesta): string
    {
        if ($estado === 'aprobado') {
            $mensaje = "✅ *Solicitud aprobada*\n\n";
            $mensaje .= "Estimado/a *{$nombre}*,\n\n";
            $mensaje .= "Le informamos que su solicitud ha sido *aprobada*.\n\n";
        } else {
            $mensaje = "📋 *Resolución de su solicitud*\n\n";
            $mensaje .= "Estimado/a *{$nombre}*,\n\n";
            $mensaje .= "Le informamos que su solicitud ha sido *rechazada*.\n\n";
        }
        $mensaje .= "📝 *Observaciones:*\n{$respuesta}\n\n";
        $mensaje .= "Si tiene dudas, puede contactarnos por los canales habituales.";
        return $mensaje;
    }

    /**
     * Envía al director un mensaje de confirmación: "La solicitud a [nombre] la has aprobado/rechazado".
     */
    private function enviarWhatsAppResolucionAlDirector(Solicitud $solicitud): void
    {
        $numeroDirector = $solicitud->getNumeroWhatsappDirector();
        if (empty($numeroDirector)) {
            Log::info('No se envió confirmación al director: sin director activo o sin teléfono', [
                'solicitud_id' => $solicitud->id,
            ]);
            return;
        }

        try {
            $evolutionService = app(EvolutionService::class);
            $groq = app(GroqAIService::class);
            $nombre = trim(($solicitud->ciudadano?->nombre ?? '') . ' ' . ($solicitud->ciudadano?->apellido ?? ''));
            $nombre = $nombre ?: 'ciudadano';

            $res = $groq->generarMensajeResolucionDirector([
                'nombre_ciudadano' => $nombre,
                'estado' => $solicitud->estado,
            ]);
            $mensaje = $res['mensaje'];

            $evolutionService->sendMessage($numeroDirector, $mensaje);
            Log::info('WhatsApp confirmación enviada al director', [
                'solicitud_id' => $solicitud->id,
                'estado' => $solicitud->estado,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar confirmación al director', [
                'error' => $e->getMessage(),
                'solicitud_id' => $solicitud->id,
            ]);
        }
    }

    /**
     * Envía el PDF de la solicitud al ciudadano por WhatsApp (solo cuando aprobada o rechazada).
     */
    private function enviarPdfAlCiudadano(Solicitud $solicitud): void
    {
        $ciudadano = $solicitud->ciudadano;
        if (!$ciudadano || empty($ciudadano->whatsapp)) {
            Log::info('No se envió PDF al ciudadano: sin WhatsApp', ['solicitud_id' => $solicitud->id]);
            return;
        }

        try {
            $pdfContent = SolicitudPdfController::generarPdfContent($solicitud);
            $base64 = base64_encode($pdfContent);
            $filename = 'solicitud-' . $solicitud->id . '-' . now()->format('Y-m-d') . '.pdf';

            $evolutionService = app(EvolutionService::class);
            $numero = $ciudadano->getWhatsappNormalizado();
            $nombre = trim($ciudadano->nombre . ' ' . $ciudadano->apellido);
            $caption = $solicitud->estado === 'aprobado'
                ? "📄 Adjunto encontrará el comprobante de su solicitud aprobada. Gracias por su confianza."
                : "📄 Adjunto encontrará el comprobante de su solicitud. Si tiene dudas, contacte por los canales habituales.";

            $result = $evolutionService->sendDocument($numero, $base64, $filename, $caption);
            if (!empty($result['error'])) {
                Log::error('Error al enviar PDF al ciudadano por Evolution API', [
                    'solicitud_id' => $solicitud->id,
                    'message' => $result['message'] ?? 'Error desconocido',
                ]);
            } else {
                Log::info('PDF enviado al ciudadano por WhatsApp', ['solicitud_id' => $solicitud->id]);
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar PDF al ciudadano', [
                'error' => $e->getMessage(),
                'solicitud_id' => $solicitud->id,
            ]);
        }
    }

    public function eliminarSolicitud($id): void
    {
        $this->authorize('delete-solicitud');
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Solicitud eliminada',
            'text' => 'La solicitud ha sido eliminada correctamente.',
        ]);
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Solicitud::with(['ciudadano', 'tipoSolicitud', 'departamento', 'parroquia', 'circuitoComunal'])
            ->latest();

        if (auth()->user()->hasRole('Director')) {
            $director = auth()->user()->director;
            if ($director && $director->departamento_id) {
                $query->where('departamento_id', $director->departamento_id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('ciudadano', function ($q2) {
                    $q2->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('cedula', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                ->orWhere('direccion', 'like', '%' . $this->search . '%')
                ->orWhere('direccion_exacta', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterEstado !== 'all') {
            $query->where('estado', $this->filterEstado);
        }

        if ($this->filterDepartamento !== '') {
            $query->where('departamento_id', $this->filterDepartamento);
        }

        $baseQuery = clone $query;
        $stats = [
            'total' => $baseQuery->count(),
            'pendiente' => (clone $baseQuery)->where('estado', 'pendiente')->count(),
            'aprobado' => (clone $baseQuery)->where('estado', 'aprobado')->count(),
            'rechazado' => (clone $baseQuery)->where('estado', 'rechazado')->count(),
        ];

        $departamentos = auth()->user()->hasRole('Director')
            ? collect()
            : Departamento::orderBy('nombre')->get(['id', 'nombre']);

        $departamentoDirector = null;
        if (auth()->user()->hasRole('Director') && auth()->user()->director?->departamento) {
            $departamentoDirector = auth()->user()->director->departamento->nombre;
        }

        return [
            'solicitudes' => $query->paginate(15),
            'stats' => $stats,
            'departamentos' => $departamentos,
            'departamentoDirector' => $departamentoDirector,
        ];
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Solicitudes de Alcaldía Digital'],
        ]" />
    </x-slot>

    <x-container class="w-full px-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">
                <i class="fa-solid fa-file-lines mr-2"></i>Solicitudes de Alcaldía Digital
            </h1>
            <p class="mt-1 text-sm" style="color: var(--color-text-tertiary);">
                Solicitudes enviadas desde el formulario de la página principal.
            </p>
            @if($departamentoDirector)
                <p class="mt-2 inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-sm font-medium" style="border-color: var(--color-blue-500); background-color: var(--color-blue-50); color: var(--color-blue-700);">
                    <i class="fa-solid fa-building-user"></i>
                    Departamento: <strong>{{ $departamentoDirector }}</strong>
                </p>
            @endif
        </div>

        {{-- Resumen --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border p-4" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                <p class="text-sm font-medium" style="color: var(--color-text-tertiary);">Total</p>
                <p class="text-2xl font-bold" style="color: var(--color-text-primary);">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-lg border p-4" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                <p class="text-sm font-medium" style="color: var(--color-text-tertiary);">Pendientes</p>
                <p class="text-2xl font-bold text-amber-600">{{ $stats['pendiente'] }}</p>
            </div>
            <div class="rounded-lg border p-4" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                <p class="text-sm font-medium" style="color: var(--color-text-tertiary);">Aprobadas</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['aprobado'] }}</p>
            </div>
            <div class="rounded-lg border p-4" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
                <p class="text-sm font-medium" style="color: var(--color-text-tertiary);">Rechazadas</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['rechazado'] }}</p>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="mb-6 rounded-lg border p-4" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium" style="color: var(--color-text-secondary);">Buscar (nombre, cédula, email, descripción, dirección)</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar..."
                           class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                           style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color: var(--color-text-secondary);">Departamento</label>
                    <select wire:model.live="filterDepartamento"
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                            style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);">
                        <option value="">Todos los departamentos</option>
                        @foreach($departamentos as $d)
                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" style="color: var(--color-text-secondary);">Estado</label>
                    <select wire:model.live="filterEstado"
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                            style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);">
                        <option value="all">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="overflow-hidden rounded-lg border" style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y text-left text-sm" style="border-color: var(--color-border-primary);">
                    <thead class="text-xs uppercase" style="background-color: var(--color-bg-secondary); color: var(--color-text-tertiary);">
                        <tr>
                            <th class="px-4 py-3">Fecha (Venezuela)</th>
                            <th class="px-4 py-3">Ciudadano</th>
                            <th class="px-4 py-3">Contacto</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Departamento</th>
                            <th class="px-4 py-3">Parroquia</th>
                            <th class="px-4 py-3">Circuito comunal</th>
                            <th class="px-4 py-3">Sector</th>
                            <th class="px-4 py-3">Dirección exacta</th>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="color: var(--color-text-primary); border-color: var(--color-border-primary);">
                        @forelse($solicitudes as $s)
                            @php
                                $fechaVzla = $s->created_at->timezone('America/Caracas')->format('d/m/Y H:i');
                            @endphp
                            <tr class="hover:bg-opacity-80" style="background-color: var(--color-bg-primary);">
                                <td class="whitespace-nowrap px-4 py-3">
                                    {{ $fechaVzla }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-medium">{{ $s->ciudadano?->nombre }} {{ $s->ciudadano?->apellido }}</span>
                                    <br><span class="text-xs" style="color: var(--color-text-tertiary);">C.I. {{ $s->ciudadano?->cedula }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs">{{ $s->ciudadano?->email }}</span>
                                    <br><span class="text-xs">Tel: {{ $s->ciudadano?->telefono_movil ?? $s->ciudadano?->whatsapp }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $s->tipoSolicitud?->nombre ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $s->departamento?->nombre ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $s->parroquia?->parroquia ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $s->circuitoComunal?->nombre ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $s->sector ?? '—' }}
                                </td>
                                <td class="max-w-[180px] px-4 py-3">
                                    <span class="line-clamp-2" title="{{ $s->direccion_exacta ?? $s->direccion }}">{{ Str::limit($s->direccion_exacta ?? $s->direccion ?? '—', 40) }}</span>
                                </td>
                                <td class="max-w-xs px-4 py-3">
                                    <span class="line-clamp-2" title="{{ $s->descripcion }}">{{ Str::limit($s->descripcion, 80) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($s->estado === 'pendiente')
                                        <span class="rounded-full px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800">Pendiente</span>
                                    @elseif($s->estado === 'aprobado')
                                        <span class="rounded-full px-2 py-1 text-xs font-medium bg-green-100 text-green-800">Aprobado</span>
                                    @else
                                        <span class="rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-800">Rechazado</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($s->estado === 'pendiente')
                                            <button type="button"
                                                    wire:click="abrirModalConfirmar({{ $s->id }})"
                                                    title="Confirmar solicitud"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 shadow-sm transition hover:bg-emerald-100 hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-800/50">
                                                <i class="fa-solid fa-check text-sm"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.solicitudes.pdf', $s) }}"
                                           target="_blank"
                                           title="Exportar PDF"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-sky-200 bg-sky-50 text-sky-700 shadow-sm transition hover:bg-sky-100 hover:border-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 dark:border-sky-800 dark:bg-sky-900/30 dark:text-sky-300 dark:hover:bg-sky-800/50">
                                            <i class="fa-solid fa-file-pdf text-sm"></i>
                                        </a>
                                        @can('delete-solicitud')
                                        <button type="button"
                                                onclick="confirmarEliminarSolicitud({{ $s->id }})"
                                                title="Eliminar"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-red-200 bg-red-50 text-red-700 shadow-sm transition hover:bg-red-100 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-800/50">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center" style="color: var(--color-text-tertiary);">
                                    No hay solicitudes registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($solicitudes->hasPages())
                <div class="border-t px-4 py-3" style="border-color: var(--color-border-primary);">
                    {{ $solicitudes->links() }}
                </div>
            @endif
        </div>
    </x-container>

    {{-- Modal Resolver solicitud — modal propio (grande y centrado) sin depender de Flux --}}
    <div x-data="{ abierto: false }"
         x-on:abrir-confirmar-modal-solicitud.window="abierto = true"
         x-on:cerrar-confirmar-modal.window="abierto = false"
         x-show="abierto"
         x-cloak
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        {{-- Panel del modal: ancho grande y centrado --}}
        <div x-show="abierto"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-on:click.outside="abierto = false; $dispatch('cerrar-confirmar-modal')"
             class="w-[92vw] max-w-[88rem] min-w-[48rem] max-h-[90vh] overflow-auto rounded-xl border shadow-2xl"
             style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);">
            <div class="w-full overflow-hidden">

                {{-- Título y cierre (sin barra de color) --}}
                <div class="flex items-start justify-between gap-4 px-5 pt-5 pb-2">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight" style="color: var(--color-text-primary);">Resolver solicitud</h2>
                        <p class="mt-0.5 text-sm" style="color: var(--color-text-tertiary);">Revisa los datos y define el resultado.</p>
                    </div>
                    <button x-on:click="abierto = false; $dispatch('cerrar-confirmar-modal')" type="button"
                            class="shrink-0 rounded-lg p-2 transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="color: var(--color-text-tertiary);">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="confirmar" class="md:flex">
                    {{-- Columna izquierda: resumen — estilo profesional con tarjetas por campo --}}
                    <div class="flex-1 border-t md:border-t-0 md:border-e px-5 py-4"
                         style="border-color: var(--color-border-primary); background: linear-gradient(180deg, var(--color-bg-secondary) 0%, var(--color-bg-primary) 100%);">
                        <div class="flex items-center gap-2 border-b pb-2.5 mb-3" style="border-color: var(--color-border-primary);">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-200/80 text-slate-600">
                                <i class="fa-solid fa-clipboard-list text-sm"></i>
                            </span>
                            <span class="text-sm font-semibold tracking-tight" style="color: var(--color-text-primary);">Resumen de la solicitud</span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="rounded-lg border bg-white/80 px-3 py-2.5 shadow-sm backdrop-blur-sm" style="border-color: var(--color-border-primary);">
                                <p class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <i class="fa-solid fa-envelope w-3.5 text-slate-400"></i> Correo
                                </p>
                                <p class="mt-1 break-all text-sm font-medium text-slate-800">{{ $email ?: '—' }}</p>
                            </div>
                            <div class="rounded-lg border bg-white/80 px-3 py-2.5 shadow-sm backdrop-blur-sm" style="border-color: var(--color-border-primary);">
                                <p class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <i class="fa-brands fa-whatsapp w-3.5 text-green-500"></i> WhatsApp
                                </p>
                                <p class="mt-1 text-sm font-medium text-slate-800">{{ $whatsapp ?: 'Sin número' }}</p>
                            </div>
                            <div class="rounded-lg border bg-white/80 px-3 py-2.5 shadow-sm backdrop-blur-sm" style="border-color: var(--color-border-primary);">
                                <p class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <i class="fa-solid fa-tag w-3.5 text-slate-400"></i> Tipo
                                </p>
                                <p class="mt-1 text-sm font-medium text-slate-800">{{ $tipo_solicitud ?: '—' }}</p>
                            </div>
                            <div class="rounded-lg border bg-white/80 px-3 py-2.5 shadow-sm backdrop-blur-sm" style="border-color: var(--color-border-primary);">
                                <p class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <i class="fa-solid fa-map-pin w-3.5 text-slate-400"></i> Ubicación
                                </p>
                                <p class="mt-1 text-sm font-medium text-slate-800">
                                    {{ $parroquia ?: '—' }}{{ $circuito ? ' · ' . $circuito : '' }}{{ $sector ? ' · Sector: ' . $sector : '' }}
                                </p>
                                @if($direccion_exacta)
                                    <p class="mt-1 text-sm text-slate-600">{{ $direccion_exacta }}</p>
                                @endif
                            </div>
                            <div class="rounded-lg border-l-4 border-emerald-300 bg-emerald-50/60 px-3 py-2.5 shadow-sm" style="border-left-color: rgb(110 231 183);">
                                <p class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <i class="fa-solid fa-align-left w-3.5 text-emerald-600"></i> Descripción
                                </p>
                                <p class="mt-1.5 whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ $descripcion ?: '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Columna derecha: decisión (estado + respuesta + botones) — zona de acción --}}
                    <div class="flex-1 px-5 py-4 md:min-w-[320px]"
                         style="border-left: 3px solid rgb(16 185 129);">
                        <p class="mb-3 text-xs font-semibold uppercase tracking-wider" style="color: var(--color-text-tertiary);">Tu decisión</p>
                        <p class="mb-4 text-sm" style="color: var(--color-text-secondary);">Indica si apruebas o rechazas y escribe la respuesta que quedará registrada.</p>

                        <div class="space-y-4">
                            <div>
                                <label for="modal_estado" class="mb-1 block text-sm font-medium" style="color: var(--color-text-secondary);">Resultado</label>
                                <select id="modal_estado" wire:model="estado"
                                        class="block w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500"
                                        style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);">
                                    <option value="">Elegir…</option>
                                    <option value="aprobado">Aprobar</option>
                                    <option value="rechazado">Rechazar</option>
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                @if($estado)
                                    <p class="mt-2">
                                        @if($estado === 'aprobado')
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-check-circle"></i> Aprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fa-solid fa-times-circle"></i> Rechazado
                                            </span>
                                        @endif
                                    </p>
                                @endif
                            </div>

                            <div x-data="{ max: 1000, texto: @entangle('respuesta') }">
                                <label for="modal_respuesta" class="mb-1 block text-sm font-medium" style="color: var(--color-text-secondary);">Respuesta al ciudadano</label>
                                <textarea id="modal_respuesta" wire:model="respuesta" rows="4" maxlength="1000"
                                          placeholder="Escribe aquí la respuesta u observaciones…"
                                          class="block w-full rounded-lg border px-3 py-2 text-sm resize-none focus:ring-2 focus:ring-emerald-500"
                                          style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-primary);"></textarea>
                                @error('respuesta')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs" style="color: var(--color-text-tertiary);">
                                    <span x-text="max - (texto ? texto.length : 0)"></span> caracteres restantes
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 pt-2">
                                <button x-on:click="abierto = false; $dispatch('cerrar-confirmar-modal')" type="button"
                                        class="rounded-lg border px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        style="border-color: var(--color-border-primary); background-color: var(--color-bg-primary); color: var(--color-text-secondary);">
                                    Cancelar
                                </button>
                                <button wire:click="confirmar" type="button" wire:loading.attr="disabled"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Enviar">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span wire:loading.remove>Enviar</span>
                                    <span wire:loading>Enviando…</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmarEliminarSolicitud(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'La solicitud se eliminará y no podrás revertirlo.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('eliminarSolicitud', id);
                    }
                });
            }
        </script>
    @endpush
</div>
