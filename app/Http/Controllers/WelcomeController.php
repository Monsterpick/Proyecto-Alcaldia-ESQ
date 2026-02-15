<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Ciudadano;
use App\Models\CircuitoComunal;
use App\Models\Parroquia;
use App\Models\Report;
use App\Models\Solicitud;
use App\Models\TipoSolicitud;
use App\Services\EvolutionService;
use App\Services\GroqAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    /**
     * Extrae sectores únicos de circuitos comunales por parroquia.
     * Igual que en Beneficiarios: Sector: X en descripcion, orden alfabético.
     */
    private function extraerSectoresPorParroquia($circuitosGrouped): array
    {
        $result = [];
        foreach ($circuitosGrouped as $parroquiaId => $circuitos) {
            $sectoresArray = [];
            foreach ($circuitos as $circuito) {
                if ($circuito->descripcion && str_contains($circuito->descripcion, 'Sector:')) {
                    preg_match('/Sector:\s*([^|]+)/', $circuito->descripcion, $matches);
                    if (isset($matches[1])) {
                        $sector = trim($matches[1]);
                        if ($sector && !in_array($sector, $sectoresArray)) {
                            $sectoresArray[] = $sector;
                        }
                    }
                }
            }
            sort($sectoresArray);
            $result[$parroquiaId] = $sectoresArray;
        }
        return $result;
    }

    public function index(): Response
    {
        // Obtener estadísticas (con try/catch por si las tablas no existen)
        try {
            $stats = [
                'solicitudes' => Solicitud::count(),
                'beneficiarios' => Beneficiary::count(),
                'reportes' => Report::count(),
            ];
        } catch (\Exception $e) {
            $stats = [
                'solicitudes' => 0,
                'beneficiarios' => 0,
                'reportes' => 0,
            ];
        }

        $cacheKey = 'welcome_form_data';
        $cacheTtl = 3600; // 1 hora

        $formData = Cache::remember($cacheKey, $cacheTtl, function () {
            $tiposSolicitud = TipoSolicitud::with('departamento:id,nombre')
                ->where('activo', true)
                ->whereHas('departamento')
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'departamento_id']);

            $parroquias = Parroquia::where('municipio_id', 1)->orderBy('parroquia')->get(['id', 'parroquia']);
            $circuitos = CircuitoComunal::query()
                ->orderBy('parroquia_id')
                ->orderBy('codigo')
                ->get(['id', 'parroquia_id', 'nombre', 'codigo', 'descripcion'])
                ->groupBy('parroquia_id');

            $sectoresPorParroquia = $this->extraerSectoresPorParroquia($circuitos);

            return [
                'tiposSolicitud' => $tiposSolicitud,
                'parroquias' => $parroquias,
                'circuitos' => $circuitos,
                'sectoresPorParroquia' => $sectoresPorParroquia,
            ];
        });

        $tiposSolicitud = $formData['tiposSolicitud'];
        $parroquias = $formData['parroquias'];
        $circuitos = $formData['circuitos'];
        $sectoresPorParroquia = $formData['sectoresPorParroquia'];

        return Inertia::render('Welcome', [
            'stats' => $stats,
            'tiposSolicitud' => $tiposSolicitud,
            'parroquias' => $parroquias,
            'circuitosPorParroquia' => $circuitos,
            'sectoresPorParroquia' => $sectoresPorParroquia,
        ]);
    }

    public function storeSolicitud(Request $request)
    {
        $validated = $request->validate([
            'cedula' => ['required', 'regex:/^[0-9]{6,8}$/'],
            'nombre' => 'required|string|min:2|max:100',
            'apellido' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'telefono_movil' => ['required', 'regex:/^0?4[0-9]{9}$/'],
            'whatsapp' => ['required', 'regex:/^0?4[0-9]{9}$/'],
            'tipo_solicitud_id' => 'required|exists:tipo_solicitud,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'parroquia_id' => 'required|exists:parroquias,id',
            'circuito_comunal_id' => ['required', 'exists:circuito_comunals,id'],
            'sector' => 'nullable|string|max:255',
            'descripcion' => 'required|string|min:10|max:2000',
            'direccion' => 'required|string|min:5|max:500',
            'acepta_terminos' => 'accepted',
        ], [
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.regex' => 'La cédula debe tener entre 6 y 8 dígitos numéricos.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.min' => 'El apellido debe tener al menos 2 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'telefono_movil.required' => 'El teléfono móvil es obligatorio.',
            'telefono_movil.regex' => 'El teléfono debe comenzar con 04 y tener 11 dígitos.',
            'whatsapp.required' => 'El WhatsApp es obligatorio.',
            'whatsapp.regex' => 'El WhatsApp debe comenzar con 04 y tener 11 dígitos.',
            'tipo_solicitud_id.required' => 'Debe seleccionar un tipo de servicio.',
            'tipo_solicitud_id.exists' => 'El tipo de servicio seleccionado no es válido.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'descripcion.max' => 'La descripción no puede exceder 2000 caracteres.',
            'parroquia_id.required' => 'Debe seleccionar la parroquia.',
            'parroquia_id.exists' => 'La parroquia seleccionada no es válida.',
            'circuito_comunal_id.required' => 'Debe seleccionar el circuito comunal.',
            'circuito_comunal_id.exists' => 'El circuito comunal seleccionado no es válido.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'acepta_terminos.accepted' => 'Debe aceptar los términos y condiciones.',
        ]);

        $this->validarCircuitoYParroquia($validated);
        $this->validarSectorPerteneceAParroquia($validated);

        try {
            // Normalizar cédula
            $cedulaNormalizada = 'V' . $validated['cedula'];

            // Normalizar teléfonos
            $telefonoNorm = $this->normalizarTelefono($validated['telefono_movil']);
            $whatsappNorm = $this->normalizarTelefono($validated['whatsapp']);

            // Crear o actualizar ciudadano (capitalización de nombres)
            $ciudadano = Ciudadano::updateOrCreate(
                ['cedula' => $cedulaNormalizada],
                [
                    'nombre' => Str::title(trim($validated['nombre'])),
                    'apellido' => Str::title(trim($validated['apellido'])),
                    'email' => $validated['email'],
                    'telefono_movil' => $telefonoNorm,
                    'whatsapp' => $whatsappNorm,
                    'whatsapp_send' => true,
                ]
            );

            // Obtener departamento del tipo de solicitud si no viene en el request
            $tipoSolicitud = TipoSolicitud::find($validated['tipo_solicitud_id']);
            $departamentoId = $validated['departamento_id'] ?? $tipoSolicitud?->departamento_id;

            // Crear solicitud
            $solicitud = Solicitud::create([
                'ciudadano_id' => $ciudadano->id,
                'tipo_solicitud_id' => $validated['tipo_solicitud_id'],
                'departamento_id' => $departamentoId,
                'parroquia_id' => $validated['parroquia_id'],
                'circuito_comunal_id' => $validated['circuito_comunal_id'],
                'sector' => $validated['sector'] ?? null,
                'descripcion' => $validated['descripcion'],
                'direccion' => $validated['direccion'],
                'direccion_exacta' => $validated['direccion'],
                'estado' => 'pendiente',
                'acepta_terminos' => true,
            ]);

            // Enviar WhatsApp de notificación
            $this->enviarWhatsApp($ciudadano, $solicitud);

            return back()->with('success', 'Su solicitud ha sido registrada exitosamente. Pronto nos comunicaremos con usted.');

        } catch (\Exception $e) {
            Log::error('Error al procesar solicitud ciudadana', [
                'error' => $e->getMessage(),
                'cedula' => $validated['cedula'] ?? null,
            ]);

            return back()->withErrors(['error' => 'Ocurrió un error al procesar su solicitud. Por favor, intente nuevamente.']);
        }
    }

    private function validarCircuitoYParroquia(array $validated): void
    {
        $circuito = CircuitoComunal::find($validated['circuito_comunal_id']);
        if ($circuito && (int) $circuito->parroquia_id !== (int) $validated['parroquia_id']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'circuito_comunal_id' => ['El circuito comunal seleccionado no pertenece a la parroquia indicada.'],
            ]);
        }
    }

    private function validarSectorPerteneceAParroquia(array $validated): void
    {
        $sector = trim($validated['sector'] ?? '');
        if ($sector === '') {
            return;
        }

        $circuitos = CircuitoComunal::where('parroquia_id', $validated['parroquia_id'])->get();
        $sectoresValidos = [];
        foreach ($circuitos as $circuito) {
            if ($circuito->descripcion && str_contains($circuito->descripcion, 'Sector:')) {
                preg_match('/Sector:\s*([^|]+)/', $circuito->descripcion, $matches);
                if (isset($matches[1])) {
                    $s = trim($matches[1]);
                    if ($s && !in_array($s, $sectoresValidos)) {
                        $sectoresValidos[] = $s;
                    }
                }
            }
        }

        if (!in_array($sector, $sectoresValidos)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'sector' => ['El sector seleccionado no es válido para la parroquia indicada.'],
            ]);
        }
    }

    private function normalizarTelefono(string $telefono): string
    {
        $telefono = ltrim($telefono, '0');
        return '+58' . $telefono;
    }

    private function enviarWhatsApp(Ciudadano $ciudadano, Solicitud $solicitud): void
    {
        try {
            $evolutionService = app(EvolutionService::class);
            $groq = app(GroqAIService::class);
            $tipoSolicitud = TipoSolicitud::find($solicitud->tipo_solicitud_id);
            $tipoNombre = $tipoSolicitud ? $tipoSolicitud->nombre : 'Solicitud';
            $nombreCompleto = trim($ciudadano->nombre . ' ' . $ciudadano->apellido);

            // 1) Mensaje al ciudadano
            $resCiudadano = $groq->generarMensajeConfirmacionAtencionCiudadana([
                'nombre' => $nombreCompleto,
                'tipo_solicitud' => $tipoNombre,
            ]);
            $mensajeCiudadano = $resCiudadano['mensaje'] ?? '';

            $numeroCiudadano = $ciudadano->getWhatsappNormalizado();
            $result = $evolutionService->sendMessage($numeroCiudadano, $mensajeCiudadano);
            Log::info('WhatsApp confirmación al ciudadano', [
                'ciudadano_id' => $ciudadano->id,
                'solicitud_id' => $solicitud->id,
                'evolution_error' => $result['error'] ?? false,
            ]);

            // 2) Mensaje al director del departamento (principal o temporal activo)
            // Sin número de ticket: solo categoría, solicitud, servicio y descripción
            $numeroDirector = $this->obtenerNumeroWhatsappDirector($solicitud);
            if (!empty($numeroDirector)) {
                $departamento = $solicitud->departamento;
                $servicio = $departamento?->nombre ?? 'N/A';
                $resDirector = $groq->generarMensajeNuevaSolicitudDirector([
                    'categoria' => $tipoNombre,
                    'tipo_solicitud' => $tipoNombre,
                    'servicio' => $servicio,
                    'descripcion' => Str::limit($solicitud->descripcion ?? '', 300),
                ]);
                $mensajeDirector = $resDirector['mensaje'] ?? $this->mensajeDirectorPredeterminado($ciudadano, $solicitud, $tipoNombre);

                $evolutionService->sendMessage($numeroDirector, $mensajeDirector);
                Log::info('WhatsApp notificación al director de departamento', [
                    'solicitud_id' => $solicitud->id,
                    'numero_director' => $numeroDirector,
                    'mensaje_ia' => $resDirector['es_ia'] ?? false,
                ]);
            } else {
                Log::info('No se envió WhatsApp a director: sin director activo o sin teléfono', [
                    'solicitud_id' => $solicitud->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error al enviar WhatsApp de solicitud', [
                'error' => $e->getMessage(),
                'ciudadano_id' => $ciudadano->id,
            ]);
        }
    }

    /**
     * Obtiene el número de WhatsApp del director responsable del departamento:
     * - Director principal activo
     * - Si no, director temporal activo
     * - Si ninguno está activo o no tiene teléfono, retorna null
     */
    private function obtenerNumeroWhatsappDirector(Solicitud $solicitud): ?string
    {
        $departamento = $solicitud->departamento()->with(['director', 'directorTemporal'])->first();

        if (!$departamento) {
            return null;
        }

        $directorPrincipal = $departamento->director;
        $directorTemporal = $departamento->directorTemporal;

        $destino = null;
        if ($directorPrincipal && $directorPrincipal->activo) {
            $destino = $directorPrincipal;
        } elseif ($directorTemporal && $directorTemporal->activo) {
            $destino = $directorTemporal;
        }

        if (!$destino) {
            return null;
        }

        if (!method_exists($destino, 'getWhatsappNormalizado')) {
            return null;
        }

        return $destino->getWhatsappNormalizado() ?: null;
    }

    private function mensajeDirectorPredeterminado(Ciudadano $ciudadano, Solicitud $solicitud, string $tipoNombre): string
    {
        $servicio = $solicitud->departamento?->nombre ?? 'N/A';
        $mensaje = "📋 *Nueva solicitud ciudadana*\n\n";
        $mensaje .= "🏷️ *Categoría:* {$tipoNombre}\n";
        $mensaje .= "📌 *Tipo de solicitud:* {$tipoNombre}\n";
        $mensaje .= "🏛️ *Servicio:* {$servicio}\n";
        $mensaje .= "📝 *Descripción:*\n" . Str::limit($solicitud->descripcion ?? '', 400) . "\n\n";
        $mensaje .= "Por favor revise en el sistema para gestionarla.";
        return $mensaje;
    }
}
