<?php

namespace App\Livewire;

use App\Models\Ciudadano;
use App\Models\Solicitud;
use App\Models\TipoSolicitud;
use App\Services\EvolutionService;
use App\Services\GroqAIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class SolicitudForm extends Component
{
    // Datos personales
    public string $cedula = '';
    public string $nombre = '';
    public string $apellido = '';
    public string $email = '';
    public string $telefono_movil = '';
    public string $whatsapp = '';

    // Solicitud
    public string $tipo_solicitud_id = '';
    public string $descripcion = '';
    public string $direccion = '';
    public bool $acepta_terminos = false;

    // Estado UI
    public bool $enviado = false;

    protected function rules(): array
    {
        return [
            'cedula' => ['required', 'regex:/^[0-9]{6,8}$/'],
            'nombre' => 'required|string|min:2|max:100',
            'apellido' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'telefono_movil' => ['required', 'regex:/^0?4[0-9]{9}$/'],
            'whatsapp' => ['required', 'regex:/^0?4[0-9]{9}$/'],
            'tipo_solicitud_id' => 'required|exists:tipo_solicitud,id',
            'descripcion' => 'required|string|min:10|max:2000',
            'direccion' => 'required|string|min:5|max:500',
            'acepta_terminos' => 'accepted',
        ];
    }

    protected function messages(): array
    {
        return [
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
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'acepta_terminos.accepted' => 'Debe aceptar los términos y condiciones.',
        ];
    }

    /**
     * Validación en tiempo real campo por campo
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Enviar la solicitud
     */
    public function enviarSolicitud(): void
    {
        $this->validate();

        try {
            // 1. Normalizar cédula
            $cedulaNormalizada = 'V' . $this->cedula;

            // 2. Normalizar teléfonos
            $telefonoNorm = $this->normalizarTelefono($this->telefono_movil);
            $whatsappNorm = $this->normalizarTelefono($this->whatsapp);

            // 3. Crear o actualizar ciudadano
            $ciudadano = Ciudadano::updateOrCreate(
                ['cedula' => $cedulaNormalizada],
                [
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'email' => $this->email,
                    'telefono_movil' => $telefonoNorm,
                    'whatsapp' => $whatsappNorm,
                    'whatsapp_send' => true,
                ]
            );

            // 4. Crear solicitud
            $solicitud = Solicitud::create([
                'ciudadano_id' => $ciudadano->id,
                'tipo_solicitud_id' => $this->tipo_solicitud_id,
                'descripcion' => $this->descripcion,
                'direccion' => $this->direccion,
                'estado' => 'pendiente',
                'acepta_terminos' => true,
            ]);

            // 5. Enviar WhatsApp de notificación
            $this->enviarWhatsApp($ciudadano, $solicitud);

            // 6. Resetear formulario y mostrar éxito
            $this->resetForm();
            $this->enviado = true;

            $this->dispatch('swal-welcome', [
                'icon' => 'success',
                'title' => 'Solicitud enviada',
                'text' => 'Su solicitud ha sido registrada exitosamente. Pronto nos comunicaremos con usted.',
                'timer' => 5000,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al procesar solicitud ciudadana', [
                'error' => $e->getMessage(),
                'cedula' => $this->cedula,
            ]);

            $this->dispatch('swal-welcome', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al procesar su solicitud. Por favor, intente nuevamente.',
            ]);
        }
    }

    /**
     * Enviar notificaciones por WhatsApp (mensajes generados por Groq si está configurado):
     * 1) Al ciudadano: confirmación de recepción.
     * 2) Al director: notificación con detalle (por ahora WHATSAPP_NOTIFY_NUMBER).
     */
    private function enviarWhatsApp(Ciudadano $ciudadano, Solicitud $solicitud): void
    {
        try {
            $evolutionService = app(EvolutionService::class);
            $groq = app(GroqAIService::class);
            $tipoSolicitud = TipoSolicitud::find($solicitud->tipo_solicitud_id);
            $tipoNombre = $tipoSolicitud ? $tipoSolicitud->nombre : 'Solicitud';
            $nombreCompleto = trim($ciudadano->nombre . ' ' . $ciudadano->apellido);

            // —— 1) Mensaje al ciudadano (Groq o predeterminado) ——
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
                'mensaje_ia' => $resCiudadano['es_ia'] ?? false,
            ]);

            // —— 2) Mensaje al director (Groq o predeterminado) ——
            $numeroDirector = config('app.whatsapp_notify_number');
            if (!empty($numeroDirector)) {
                $servicio = $solicitud->departamento?->nombre ?? $solicitud->tipoSolicitud?->departamento?->nombre ?? 'N/A';
                $resDirector = $groq->generarMensajeNuevaSolicitudDirector([
                    'categoria' => $tipoNombre,
                    'tipo_solicitud' => $tipoNombre,
                    'servicio' => $servicio,
                    'descripcion' => Str::limit($solicitud->descripcion ?? '', 300),
                ]);
                $mensajeDirector = $resDirector['mensaje'] ?? $this->mensajeDirectorPredeterminado($ciudadano, $solicitud, $tipoNombre);

                $evolutionService->sendMessage($numeroDirector, $mensajeDirector);
                Log::info('WhatsApp notificación al director', [
                    'solicitud_id' => $solicitud->id,
                    'numero_director' => $numeroDirector,
                    'mensaje_ia' => $resDirector['es_ia'] ?? false,
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
     * Mensaje predeterminado al director si Groq falla
     */
    private function mensajeDirectorPredeterminado(Ciudadano $ciudadano, Solicitud $solicitud, string $tipoNombre): string
    {
        $servicio = $solicitud->departamento?->nombre ?? $solicitud->tipoSolicitud?->departamento?->nombre ?? 'N/A';
        $mensaje = "📋 *Nueva solicitud ciudadana*\n\n";
        $mensaje .= "🏷️ *Categoría:* {$tipoNombre}\n";
        $mensaje .= "📌 *Tipo de solicitud:* {$tipoNombre}\n";
        $mensaje .= "🏛️ *Servicio:* {$servicio}\n";
        $mensaje .= "📝 *Descripción:*\n" . Str::limit($solicitud->descripcion ?? '', 400) . "\n\n";
        $mensaje .= "Por favor revise en el sistema para gestionarla.";
        return $mensaje;
    }

    /**
     * Normalizar número de teléfono a formato +58
     */
    private function normalizarTelefono(string $telefono): string
    {
        $telefono = ltrim($telefono, '0');
        return '+58' . $telefono;
    }

    /**
     * Resetear todos los campos del formulario
     */
    private function resetForm(): void
    {
        $this->cedula = '';
        $this->nombre = '';
        $this->apellido = '';
        $this->email = '';
        $this->telefono_movil = '';
        $this->whatsapp = '';
        $this->tipo_solicitud_id = '';
        $this->descripcion = '';
        $this->direccion = '';
        $this->acepta_terminos = false;
    }

    public function render()
    {
        return view('livewire.solicitud-form', [
            'tiposSolicitud' => TipoSolicitud::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }
}
