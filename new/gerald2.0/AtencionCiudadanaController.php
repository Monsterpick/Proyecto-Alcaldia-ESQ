<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\CategoriaSolicitud;
use App\Models\Empresa;
use App\Models\Ciudadano;
use App\Models\DerechoDePalabra;
use App\Models\Solicitud;
use App\Services\EvolutionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AtencionCiudadanaController extends Controller
{
    protected EvolutionService $evolutionService;

    public function __construct(EvolutionService $evolutionService)
    {
        $this->evolutionService = $evolutionService;
    }

    /**
     * Mostrar formulario de nueva solicitud
     */
    public function create(): View
    {
        $sesionController = new SesionMunicipalController();

        return view('web.page.participacion_ciudadana.atencion_ciudadana', [
            'empresa' => Empresa::first(),
            'sesionesProximas' => $sesionController->getSesionesDisponibles(),
            'comisiones' => Comision::where('activo', true)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre', 'descripcion']),
            'tiposSolicitud' => CategoriaSolicitud::where('activo', true)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre', 'descripcion']),
        ]);
    }

    /**
     * Guardar nueva solicitud (unificado para ambos tipos)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cedula' => ['required', 'regex:/^[0-9]{8}$/'],
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email',
            'telefono_movil' => 'required|regex:/^0?4[0-2][0-9]{8}$/',
            'whatsapp' => 'required|regex:/^0?4[0-2][0-9]{8}$/',
            'tipo_solicitud' => 'required|in:derecho_palabra,atencion',
            'sesion_municipal_id' => 'nullable|required_if:tipo_solicitud,derecho_palabra|exists:sesions_municipal,id',
            'comision_id' => 'nullable|exists:comisions,id',
            'motivo_solicitud' => 'nullable|required_if:tipo_solicitud,derecho_palabra|string|min:10|max:1000',
            'tipo_solicitud_id' => 'nullable|required_if:tipo_solicitud,atencion|exists:tipo_solicitud,id',
            'descripcion' => 'nullable|required_if:tipo_solicitud,atencion|string|min:10|max:2000',
            'acepta_terminos' => 'required|accepted',
        ], [
            'cedula.required' => 'La cédula es requerida',
            'cedula.regex' => 'La cédula debe tener exactamente 8 dígitos',
            'nombre.required' => 'El nombre es requerido',
            'apellido.required' => 'El apellido es requerido',
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo debe ser válido',
            'telefono_movil.required' => 'El teléfono móvil es requerido',
            'telefono_movil.regex' => 'El teléfono debe comenzar con 04',
            'whatsapp.required' => 'El WhatsApp es requerido',
            'whatsapp.regex' => 'El WhatsApp debe comenzar con 04',
            'tipo_solicitud.required' => 'Debe seleccionar un tipo de solicitud',
            'sesion_municipal_id.required_if' => 'Debe seleccionar una sesión municipal',
            'motivo_solicitud.required_if' => 'El motivo de solicitud es requerido',
            'motivo_solicitud.min' => 'El motivo debe tener al menos 10 caracteres',
            'tipo_solicitud_id.required_if' => 'Debe seleccionar un tipo de solicitud',
            'descripcion.required_if' => 'La descripción es requerida',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
            'acepta_terminos.required' => 'Debe aceptar los términos y condiciones',
            'acepta_terminos.accepted' => 'Debe aceptar los términos y condiciones',
        ]);

        // Normalizar cédula (agregar V-)
        $cedulaNormalizada = 'V' . $validated['cedula'];

        // Normalizar teléfonos
        $telefonoMovil = $this->normalizarTelefono($validated['telefono_movil']);
        $whatsapp = $this->normalizarTelefono($validated['whatsapp']);

        // Buscar ciudadano
        $ciudadano = Ciudadano::where('cedula', $cedulaNormalizada)->first();

        // Si es Derecho de Palabra, validar solicitud pendiente
        if ($validated['tipo_solicitud'] === 'derecho_palabra') {
            if ($ciudadano) {
                $solicitudPendiente = DerechoDePalabra::where('ciudadano_id', $ciudadano->id)
                    ->where('estado', 'pendiente')
                    ->first();

                if ($solicitudPendiente) {
                    return redirect(route('home') . '#participacion')
                        ->with('warning', '⚠️ Ya tiene una solicitud de derecho de palabra pendiente. Espere a que sea procesada antes de enviar otra.');
                }
            }
        }

        // Si es Atención Ciudadana, validar solicitud pendiente
        if ($validated['tipo_solicitud'] === 'atencion') {
            if ($ciudadano) {
                $solicitudPendiente = Solicitud::where('ciudadano_id', $ciudadano->id)
                    ->where('estado', 'pendiente')
                    ->first();

                if ($solicitudPendiente) {
                    return redirect(route('home') . '#participacion')
                        ->with('warning', '⚠️ Ya tiene una solicitud de atención pendiente. Espere a que sea procesada antes de enviar otra.');
                }
            }
        }

        // Crear o actualizar ciudadano
        $ciudadano = Ciudadano::updateOrCreate(
            ['cedula' => $cedulaNormalizada],
            [
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'telefono_movil' => $telefonoMovil,
                'whatsapp' => $whatsapp,
                'whatsapp_send' => true,
            ]
        );

        // Guardar según tipo de solicitud
        if ($validated['tipo_solicitud'] === 'derecho_palabra') {
            return $this->guardarDerechoPalabra($ciudadano, $validated);
        } else {
            return $this->guardarAtencionCiudadana($ciudadano, $validated);
        }
    }

    /**
     * Guardar solicitud de Derecho de Palabra
     */
    private function guardarDerechoPalabra(Ciudadano $ciudadano, array $validated)
    {
        $derechoPalabra = DerechoDePalabra::create([
            'ciudadano_id' => $ciudadano->id,
            'sesion_municipal_id' => $validated['sesion_municipal_id'],
            'comision_id' => $validated['comision_id'] ?? null,
            'motivo_solicitud' => $validated['motivo_solicitud'],
            'estado' => 'pendiente',
            'acepta_terminos' => true,
        ]);

        // Enviar WhatsApp de confirmación
        try {
            $this->enviarWhatsAppDerechoPalabra($ciudadano, $derechoPalabra);
        } catch (\Exception $e) {
            Log::error('Error al enviar WhatsApp de derecho de palabra', ['error' => $e->getMessage()]);
        }

        return redirect(route('home') . '#participacion')
            ->with('success', 'Estimado ciudadano, su solicitud de derecho de palabra fue enviada exitosamente. Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp.');
    }

    /**
     * Guardar solicitud de Atención Ciudadana
     */
    private function guardarAtencionCiudadana(Ciudadano $ciudadano, array $validated)
    {
        $solicitud = Solicitud::create([
            'ciudadano_id' => $ciudadano->id,
            'tipo_solicitud_id' => $validated['tipo_solicitud_id'],
            'descripcion' => $validated['descripcion'],
            'estado' => 'pendiente',
            'acepta_terminos' => true,
        ]);

        // Enviar WhatsApp de confirmación
        try {
            $this->enviarWhatsAppAtencionCiudadana($ciudadano, $solicitud);
        } catch (\Exception $e) {
            Log::error('Error al enviar WhatsApp de atención ciudadana', ['error' => $e->getMessage()]);
        }

        return redirect(route('home') . '#participacion')
            ->with('success', 'Estimado ciudadano, su solicitud de atención fue enviada exitosamente. Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp.');
    }

    /**
     * Enviar WhatsApp para Derecho de Palabra
     */
    private function enviarWhatsAppDerechoPalabra(Ciudadano $ciudadano, DerechoDePalabra $derechoPalabra)
    {
        // Obtener datos para el mensaje
        $sesion = $derechoPalabra->sesionMunicipal;
        $sesionTitulo = $sesion ? $sesion->titulo : 'Sesión Municipal';

        $comision = null;
        if ($derechoPalabra->comision_id) {
            $comision = $derechoPalabra->comision;
            $comision = $comision ? $comision->nombre : null;
        }

        // Obtener nombre de empresa
        $empresa = Empresa::first();
        $nombreEmpresa = $empresa && $empresa->razon_social ? $empresa->razon_social : 'Plenaria';

        // Construir mensaje
        $mensaje = "✅ *Solicitud de Derecho de Palabra Recibida*\n\n";
        $mensaje .= "Estimado/a *{$ciudadano->nombre} {$ciudadano->apellido}*,\n\n";
        $mensaje .= "Le confirmamos que su solicitud de derecho de palabra ha sido recibida exitosamente por {$nombreEmpresa}.\n\n";
        $mensaje .= "📋 *Sesión:* {$sesionTitulo}\n";
        if ($comision) {
            $mensaje .= "👥 *Comisión:* {$comision}\n";
        }
        $mensaje .= "\n";
        $mensaje .= "Pronto nos comunicaremos con usted para confirmar su participación. Agradecemos su interés en participar activamente en la vida municipal.\n\n";
        $mensaje .= "Si tiene alguna consulta, estamos a su disposición.";

        // Enviar por WhatsApp
        $response = $this->evolutionService->sendMessage($ciudadano->whatsapp, $mensaje);

        if (!$response['error']) {
            Log::info('WhatsApp de derecho de palabra enviado', [
                'ciudadano_id' => $ciudadano->id,
                'solicitud_id' => $derechoPalabra->id,
            ]);
        } else {
            Log::warning('Error al enviar WhatsApp de derecho de palabra', [
                'ciudadano_id' => $ciudadano->id,
                'error' => $response['message'] ?? 'Error desconocido',
            ]);
        }
    }

    /**
     * Enviar WhatsApp para Atención Ciudadana
     */
    private function enviarWhatsAppAtencionCiudadana(Ciudadano $ciudadano, Solicitud $solicitud)
    {
        // Obtener tipo de solicitud
        $tipoSolicitud = $solicitud->tipoSolicitud;
        $tipoSolicitudNombre = $tipoSolicitud ? $tipoSolicitud->nombre : 'atención ciudadana';

        // Obtener nombre de empresa
        $empresa = Empresa::first();
        $nombreEmpresa = $empresa && $empresa->razon_social ? $empresa->razon_social : 'Plenaria';

        // Construir mensaje
        $mensaje = "✅ *Solicitud de Atención Recibida*\n\n";
        $mensaje .= "Estimado/a *{$ciudadano->nombre} {$ciudadano->apellido}*,\n\n";
        $mensaje .= "Le confirmamos que su solicitud de {$tipoSolicitudNombre} ha sido recibida exitosamente por {$nombreEmpresa}.\n\n";
        $mensaje .= "Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp para brindarle la atención que requiere.\n\n";
        $mensaje .= "Agradecemos su confianza en nuestros servicios de participación ciudadana. Si tiene alguna consulta adicional, estamos a su disposición.";

        // Enviar por WhatsApp
        $response = $this->evolutionService->sendMessage($ciudadano->whatsapp, $mensaje);

        if (!$response['error']) {
            Log::info('WhatsApp de atención ciudadana enviado', [
                'ciudadano_id' => $ciudadano->id,
                'solicitud_id' => $solicitud->id,
            ]);
        } else {
            Log::warning('Error al enviar WhatsApp de atención ciudadana', [
                'ciudadano_id' => $ciudadano->id,
                'error' => $response['message'] ?? 'Error desconocido',
            ]);
        }
    }

    /**
     * Normalizar números de teléfono a formato +58
     */
    private function normalizarTelefono(string $telefono): string
    {
        $telefono = ltrim($telefono, '0');
        return '+58' . $telefono;
    }
}
