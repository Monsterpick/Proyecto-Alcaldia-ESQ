<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAIService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected float $temperature;
    protected int $maxTokens;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY');
        $this->model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');
        $this->baseUrl = env('GROQ_API_URL', 'https://api.groq.com/openai/v1');
        $this->temperature = (float) env('GROQ_TEMPERATURE', 0.7);
        $this->maxTokens = (int) env('GROQ_MAX_TOKENS', 8000);
        $this->timeout = (int) env('GROQ_TIMEOUT', 60);
    }

    /**
     * Verifica si el servicio está configurado correctamente
     */
    protected function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Llama a la API de Groq (compatible con OpenAI)
     * Método PÚBLICO para que otros servicios puedan usarlo
     */
    public function llamarGroqAPI(string $prompt): array
    {
        try {
            // Validar que está configurado
            if (!$this->isConfigured()) {
                Log::warning('Groq API no configurada. Variable GROQ_API_KEY no encontrada en .env');
                return [
                    'success' => false,
                    'error' => 'Groq API no configurada',
                ];
            }

            $url = "{$this->baseUrl}/chat/completions";

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->apiKey}",
                ])
                ->post($url, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Eres un asistente profesional de la Administración Pública Municipal. Genera mensajes cordiales y profesionales para WhatsApp sobre participación ciudadana.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => $this->temperature,
                    'max_tokens' => $this->maxTokens,
                    'top_p' => (float) env('GROQ_TOP_P', 0.95),
                    'stream' => false
                ]);

            if (!$response->successful()) {
                Log::error('Error en respuesta de Groq API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'Error en la API de Groq: ' . $response->body(),
                ];
            }

            $data = $response->json();

            // Extraer el contenido generado (formato compatible con OpenAI)
            $contenido = $data['choices'][0]['message']['content'] ?? null;

            if (!$contenido) {
                return [
                    'success' => false,
                    'error' => 'No se pudo extraer contenido de la respuesta de Groq',
                ];
            }

            // Calcular tokens usados
            $tokensUsados = $data['usage']['total_tokens'] ?? 0;

            return [
                'success' => true,
                'contenido' => $contenido,
                'tokens_usados' => $tokensUsados,
                'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
            ];

        } catch (\Exception $e) {
            Log::error('Excepción al llamar a Groq API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtiene el nombre de la empresa registrada, default: Plenaria
     */
    protected function obtenerNombreEmpresa(): string
    {
        try {
            $empresa = \App\Models\Empresa::first();
            if ($empresa && !empty($empresa->razon_social)) {
                return $empresa->razon_social;
            }
        } catch (\Exception $e) {
            Log::warning('Error al obtener nombre de empresa', ['error' => $e->getMessage()]);
        }

        return 'Plenaria';
    }

    // ============================================================
    // MENSAJES DE CONFIRMACIÓN (cuando se recibe la solicitud)
    // ============================================================

    /**
     * Genera un mensaje de confirmación para Derecho de Palabra
     *
     * @param array $datosDerechoPalabra Información de la solicitud
     * @return array
     */
    public function generarMensajeConfirmacionDerechoPalabra(array $datosDerechoPalabra): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Groq API no configurada');
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                'es_ia' => false,
                'motivo' => 'API no configurada',
            ];
        }

        try {
            $prompt = $this->construirPromptDerechoPalabra($datosDerechoPalabra);
            $response = $this->llamarGroqAPI($prompt);

            if (!$response['success']) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                    'es_ia' => false,
                    'motivo' => 'Error en API',
                ];
            }

            $mensaje = trim($response['contenido']);

            if (strlen($mensaje) > 1000) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                    'es_ia' => false,
                    'motivo' => 'Mensaje muy largo',
                ];
            }

            return [
                'success' => true,
                'mensaje' => $mensaje,
                'tokens_usados' => $response['tokens_usados'] ?? 0,
                'es_ia' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Error generando mensaje derecho de palabra', ['error' => $e->getMessage()]);
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                'es_ia' => false,
                'motivo' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Construye el prompt para mensaje de Derecho de Palabra (confirmación)
     */
    protected function construirPromptDerechoPalabra(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $sesion = $datos['sesion'] ?? 'N/A';
        $comision = $datos['comision'] ?? null;
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
        $prompt .= "Genera un mensaje de WhatsApp cordial y profesional para confirmar que una solicitud de derecho de palabra ha sido recibida.\n\n";

        $prompt .= "## INFORMACIÓN DE LA SOLICITUD\n";
        $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
        $prompt .= "- Sesión Municipal: {$sesion}\n";

        if ($comision) {
            $prompt .= "- Comisión: {$comision}\n";
        }

        $prompt .= "\n## INSTRUCCIONES\n";
        $prompt .= "1. El mensaje debe ser profesional y cordial\n";
        $prompt .= "2. Confirma que la solicitud de derecho de palabra ha sido recibida exitosamente\n";
        $prompt .= "3. Menciona la sesión municipal a la que se refiere\n";
        $prompt .= "4. Indica que pronto se comunicarán para confirmar la asignación\n";
        $prompt .= "5. Agradece la participación ciudadana\n";
        $prompt .= "6. Ofrece disponibilidad para consultas\n";
        $prompt .= "7. Usa emojis de forma moderada (máximo 2-3)\n";
        $prompt .= "8. El mensaje debe ser CORTO (máximo 600 caracteres)\n";
        $prompt .= "9. Usa formato WhatsApp: *negritas*, _cursivas_\n";
        $prompt .= "10. NO uses HTML ni código\n";
        $prompt .= "11. El tono debe ser de la administración pública\n\n";
        $prompt .= "Genera SOLO el mensaje, sin introducción ni explicación adicional.";

        return $prompt;
    }

    /**
     * Mensaje predeterminado para Derecho de Palabra (confirmación)
     */
    protected function mensajePredeterminadoDerechoPalabra(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $sesion = $datos['sesion'] ?? 'N/A';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $mensaje = "✅ *Solicitud de Derecho de Palabra Recibida*\n\n";
        $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
        $mensaje .= "Le confirmamos que su solicitud de derecho de palabra ha sido recibida exitosamente por {$nombreEmpresa}.\n\n";
        $mensaje .= "📋 *Sesión:* {$sesion}\n\n";
        $mensaje .= "Pronto nos comunicaremos con usted para confirmar su participación. Agradecemos su interés en participar activamente en la vida municipal.\n\n";
        $mensaje .= "Si tiene alguna consulta, estamos a su disposición.";

        return $mensaje;
    }

    /**
     * Genera un mensaje de confirmación para Atención Ciudadana
     *
     * @param array $datosAtencion Información de la solicitud
     * @return array
     */
    public function generarMensajeConfirmacionAtencionCiudadana(array $datosAtencion): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Groq API no configurada');
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                'es_ia' => false,
                'motivo' => 'API no configurada',
            ];
        }

        try {
            $prompt = $this->construirPromptAtencionCiudadana($datosAtencion);
            $response = $this->llamarGroqAPI($prompt);

            if (!$response['success']) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                    'es_ia' => false,
                    'motivo' => 'Error en API',
                ];
            }

            $mensaje = trim($response['contenido']);

            if (strlen($mensaje) > 1000) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                    'es_ia' => false,
                    'motivo' => 'Mensaje muy largo',
                ];
            }

            return [
                'success' => true,
                'mensaje' => $mensaje,
                'tokens_usados' => $response['tokens_usados'] ?? 0,
                'es_ia' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Error generando mensaje atención ciudadana', ['error' => $e->getMessage()]);
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                'es_ia' => false,
                'motivo' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Construye el prompt para mensaje de Atención Ciudadana (confirmación)
     */
    protected function construirPromptAtencionCiudadana(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $tipoSolicitud = $datos['tipo_solicitud'] ?? 'N/A';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
        $prompt .= "Genera un mensaje de WhatsApp cordial y profesional para confirmar que una solicitud de atención ciudadana ha sido recibida.\n\n";

        $prompt .= "## INFORMACIÓN DE LA SOLICITUD\n";
        $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
        $prompt .= "- Tipo de Solicitud: {$tipoSolicitud}\n";

        $prompt .= "\n## INSTRUCCIONES\n";
        $prompt .= "1. El mensaje debe ser profesional y cordial\n";
        $prompt .= "2. Confirma que la solicitud ha sido recibida exitosamente\n";
        $prompt .= "3. Menciona el tipo de solicitud\n";
        $prompt .= "4. Indica que pronto se comunicarán por correo, llamada o WhatsApp\n";
        $prompt .= "5. Agradece por usar los canales de participación ciudadana\n";
        $prompt .= "6. Ofrece disponibilidad para consultas\n";
        $prompt .= "7. Usa emojis de forma moderada (máximo 2-3)\n";
        $prompt .= "8. El mensaje debe ser CORTO (máximo 600 caracteres)\n";
        $prompt .= "9. Usa formato WhatsApp: *negritas*, _cursivas_\n";
        $prompt .= "10. NO uses HTML ni código\n";
        $prompt .= "11. El tono debe ser de la administración pública\n\n";
        $prompt .= "Genera SOLO el mensaje, sin introducción ni explicación adicional.";

        return $prompt;
    }

    /**
     * Mensaje predeterminado para Atención Ciudadana (confirmación)
     */
    protected function mensajePredeterminadoAtencionCiudadana(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $tipoSolicitud = $datos['tipo_solicitud'] ?? 'atención ciudadana';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $mensaje = "✅ *Solicitud de Atención Recibida*\n\n";
        $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
        $mensaje .= "Le confirmamos que su solicitud de {$tipoSolicitud} ha sido recibida exitosamente por {$nombreEmpresa}.\n\n";
        $mensaje .= "Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp para brindarle la atención que requiere.\n\n";
        $mensaje .= "Agradecemos su confianza en nuestros servicios de participación ciudadana. Si tiene alguna consulta adicional, estamos a su disposición.";

        return $mensaje;
    }

    // ============================================================
    // MENSAJES DE APROBACIÓN (cuando se aprueba la solicitud)
    // ============================================================

    /**
     * Genera un mensaje de APROBACIÓN para Derecho de Palabra o Atención Ciudadana
     *
     * @param array $datos Información de la solicitud
     * @param string $tipo 'derecho_palabra' o 'atencion_ciudadana'
     * @return array
     */
    public function generarMensajeAprobacion(array $datos, string $tipo = 'derecho_palabra'): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Groq API no configurada');
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoAprobacion($datos, $tipo),
                'es_ia' => false,
                'motivo' => 'API no configurada',
            ];
        }

        try {
            $prompt = $this->construirPromptAprobacion($datos, $tipo);
            $response = $this->llamarGroqAPI($prompt);

            if (!$response['success']) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoAprobacion($datos, $tipo),
                    'es_ia' => false,
                    'motivo' => 'Error en API',
                ];
            }

            $mensaje = trim($response['contenido']);

            if (strlen($mensaje) > 1000) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoAprobacion($datos, $tipo),
                    'es_ia' => false,
                    'motivo' => 'Mensaje muy largo',
                ];
            }

            return [
                'success' => true,
                'mensaje' => $mensaje,
                'tokens_usados' => $response['tokens_usados'] ?? 0,
                'es_ia' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Error generando mensaje de aprobación', ['error' => $e->getMessage()]);
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoAprobacion($datos, $tipo),
                'es_ia' => false,
                'motivo' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Construye el prompt para mensaje de APROBACIÓN
     */
    protected function construirPromptAprobacion(array $datos, string $tipo): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $observaciones = $datos['observaciones'] ?? '';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        if ($tipo === 'derecho_palabra') {
            $sesion = $datos['sesion'] ?? 'N/A';
            $comision = $datos['comision'] ?? null;

            $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
            $prompt .= "Genera un mensaje de WhatsApp APROBANDO una solicitud de DERECHO DE PALABRA. El mensaje debe ser alegre, cordial y profesional.\n\n";

            $prompt .= "## INFORMACIÓN\n";
            $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
            $prompt .= "- Sesión: {$sesion}\n";
            if ($comision) {
                $prompt .= "- Comisión: {$comision}\n";
            }
            if ($observaciones) {
                $prompt .= "- Observaciones: {$observaciones}\n";
            }

            $prompt .= "\n## INSTRUCCIONES\n";
            $prompt .= "1. Comunica que la solicitud ha sido *APROBADA*\n";
            $prompt .= "2. Felicita al ciudadano por su participación\n";
            $prompt .= "3. Menciona la sesión donde participará\n";
            $prompt .= "4. Si hay observaciones, incluye un resumen positivo\n";
            $prompt .= "5. Indica que pronto recibirá detalles de fecha y hora\n";
            $prompt .= "6. Usa tono celebratorio pero profesional\n";
            $prompt .= "7. Máximo 500 caracteres\n";
            $prompt .= "8. Usa emojis positivos (máximo 3)\n";
            $prompt .= "9. Formato WhatsApp: *negritas*, _cursivas_\n\n";
            $prompt .= "Genera SOLO el mensaje.";
        } else {
            $tipoSolicitud = $datos['tipo_solicitud'] ?? 'atención ciudadana';

            $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
            $prompt .= "Genera un mensaje de WhatsApp APROBANDO una solicitud de ATENCIÓN CIUDADANA. El mensaje debe ser alegre, cordial y profesional.\n\n";

            $prompt .= "## INFORMACIÓN\n";
            $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
            $prompt .= "- Tipo de Solicitud: {$tipoSolicitud}\n";
            if ($observaciones) {
                $prompt .= "- Observaciones: {$observaciones}\n";
            }

            $prompt .= "\n## INSTRUCCIONES\n";
            $prompt .= "1. Comunica que la solicitud ha sido *APROBADA*\n";
            $prompt .= "2. Felicita al ciudadano por solicitar atención\n";
            $prompt .= "3. Menciona el tipo de solicitud\n";
            $prompt .= "4. Si hay observaciones, incluye un resumen positivo\n";
            $prompt .= "5. Indica que pronto se comunicarán con detalles\n";
            $prompt .= "6. Ofrece disponibilidad para consultas\n";
            $prompt .= "7. Máximo 500 caracteres\n";
            $prompt .= "8. Usa emojis positivos (máximo 3)\n";
            $prompt .= "9. Formato WhatsApp: *negritas*, _cursivas_\n\n";
            $prompt .= "Genera SOLO el mensaje.";
        }

        return $prompt;
    }

    /**
     * Mensaje predeterminado de APROBACIÓN
     */
    protected function mensajePredeterminadoAprobacion(array $datos, string $tipo): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        if ($tipo === 'derecho_palabra') {
            $sesion = $datos['sesion'] ?? 'N/A';
            $comision = $datos['comision'] ?? null;

            $mensaje = "✅ *¡Tu Derecho de Palabra ha sido APROBADO!*\n\n";
            $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
            $mensaje .= "¡Felicidades! Tu solicitud de derecho de palabra ha sido *APROBADA* por {$nombreEmpresa}.\n\n";
            $mensaje .= "📋 *Sesión:* {$sesion}\n";
            if ($comision) {
                $mensaje .= "👥 *Comisión:* {$comision}\n";
            }
            $mensaje .= "\n";
            $mensaje .= "Estamos listos para escuchar tu participación. Pronto te enviaremos los detalles sobre la fecha y hora de la sesión.\n\n";
            $mensaje .= "Agradecemos tu participación ciudadana.";
        } else {
            $tipoSolicitud = $datos['tipo_solicitud'] ?? 'atención ciudadana';

            $mensaje = "✅ *¡Tu Solicitud ha sido APROBADA!*\n\n";
            $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
            $mensaje .= "¡Excelente! Tu solicitud de {$tipoSolicitud} ha sido *APROBADA* por {$nombreEmpresa}.\n\n";
            $mensaje .= "Pronto te contactaremos vía correo, llamada o WhatsApp con los detalles de cómo procederemos.\n\n";
            $mensaje .= "Agradecemos tu confianza en nuestros servicios.";
        }

        return $mensaje;
    }

    // ============================================================
    // MENSAJES DE RECHAZO (cuando se rechaza la solicitud)
    // ============================================================

    /**
     * Genera un mensaje de RECHAZO para Derecho de Palabra o Atención Ciudadana
     *
     * @param array $datos Información de la solicitud
     * @param string $tipo 'derecho_palabra' o 'atencion_ciudadana'
     * @return array
     */
    public function generarMensajeRechazo(array $datos, string $tipo = 'derecho_palabra'): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Groq API no configurada');
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoRechazo($datos, $tipo),
                'es_ia' => false,
                'motivo' => 'API no configurada',
            ];
        }

        try {
            $prompt = $this->construirPromptRechazo($datos, $tipo);
            $response = $this->llamarGroqAPI($prompt);

            if (!$response['success']) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoRechazo($datos, $tipo),
                    'es_ia' => false,
                    'motivo' => 'Error en API',
                ];
            }

            $mensaje = trim($response['contenido']);

            if (strlen($mensaje) > 1000) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoRechazo($datos, $tipo),
                    'es_ia' => false,
                    'motivo' => 'Mensaje muy largo',
                ];
            }

            return [
                'success' => true,
                'mensaje' => $mensaje,
                'tokens_usados' => $response['tokens_usados'] ?? 0,
                'es_ia' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Error generando mensaje de rechazo', ['error' => $e->getMessage()]);
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoRechazo($datos, $tipo),
                'es_ia' => false,
                'motivo' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Construye el prompt para mensaje de RECHAZO
     */
    protected function construirPromptRechazo(array $datos, string $tipo): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $observaciones = $datos['observaciones'] ?? '';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        if ($tipo === 'derecho_palabra') {
            $sesion = $datos['sesion'] ?? 'N/A';
            $comision = $datos['comision'] ?? null;

            $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
            $prompt .= "Genera un mensaje de WhatsApp RECHAZANDO una solicitud de DERECHO DE PALABRA de forma respetuosa y profesional.\n\n";

            $prompt .= "## INFORMACIÓN\n";
            $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
            $prompt .= "- Sesión Solicitada: {$sesion}\n";
            if ($comision) {
                $prompt .= "- Comisión: {$comision}\n";
            }
            if ($observaciones) {
                $prompt .= "- Motivo del Rechazo: {$observaciones}\n";
            }

            $prompt .= "\n## INSTRUCCIONES\n";
            $prompt .= "1. Comunica el rechazo con respeto y empatía\n";
            $prompt .= "2. Explica brevemente el motivo del rechazo (basado en observaciones)\n";
            $prompt .= "3. Menciona la sesión a la que se refería\n";
            $prompt .= "4. Ofrece alternativas: poder apelar o participar en próximas sesiones\n";
            $prompt .= "5. Mantén tono profesional pero empático\n";
            $prompt .= "6. Máximo 500 caracteres\n";
            $prompt .= "7. Evita emojis negativos\n";
            $prompt .= "8. Formato WhatsApp: *negritas*, _cursivas_\n\n";
            $prompt .= "Genera SOLO el mensaje.";
        } else {
            $tipoSolicitud = $datos['tipo_solicitud'] ?? 'atención ciudadana';

            $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
            $prompt .= "Genera un mensaje de WhatsApp RECHAZANDO una solicitud de ATENCIÓN CIUDADANA de forma respetuosa y profesional.\n\n";

            $prompt .= "## INFORMACIÓN\n";
            $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
            $prompt .= "- Tipo de Solicitud: {$tipoSolicitud}\n";
            if ($observaciones) {
                $prompt .= "- Motivo del Rechazo: {$observaciones}\n";
            }

            $prompt .= "\n## INSTRUCCIONES\n";
            $prompt .= "1. Comunica el rechazo con respeto y empatía\n";
            $prompt .= "2. Explica brevemente el motivo del rechazo\n";
            $prompt .= "3. Ofrece alternativas o formas de resolver el problema\n";
            $prompt .= "4. Invita a contactar si hay dudas\n";
            $prompt .= "5. Mantén tono profesional pero empático\n";
            $prompt .= "6. Máximo 500 caracteres\n";
            $prompt .= "7. Evita emojis negativos\n";
            $prompt .= "8. Formato WhatsApp: *negritas*, _cursivas_\n\n";
            $prompt .= "Genera SOLO el mensaje.";
        }

        return $prompt;
    }

    /**
     * Mensaje predeterminado de RECHAZO
     */
    protected function mensajePredeterminadoRechazo(array $datos, string $tipo): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        if ($tipo === 'derecho_palabra') {
            $sesion = $datos['sesion'] ?? 'N/A';
            $comision = $datos['comision'] ?? null;

            $mensaje = "⚠️ *Solicitud de Derecho de Palabra Rechazada*\n\n";
            $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
            $mensaje .= "Lamentamos informarte que tu solicitud de derecho de palabra ha sido *RECHAZADA* por {$nombreEmpresa}.\n\n";
            $mensaje .= "📋 *Sesión Solicitada:* {$sesion}\n";
            if ($comision) {
                $mensaje .= "👥 *Comisión:* {$comision}\n";
            }
            $mensaje .= "\n";
            $mensaje .= "Si consideras que hay un error, puedes presentar una apelación contactándonos directamente.\n\n";
            $mensaje .= "Agradecemos tu comprensión.";
        } else {
            $tipoSolicitud = $datos['tipo_solicitud'] ?? 'atención ciudadana';

            $mensaje = "⚠️ *Solicitud de {$tipoSolicitud} Rechazada*\n\n";
            $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
            $mensaje .= "Lamentamos informarte que tu solicitud ha sido *RECHAZADA*.\n\n";
            $mensaje .= "Esto puede deberse a que la solicitud no cumple con los requisitos necesarios o está fuera de nuestro alcance.\n\n";
            $mensaje .= "Si deseas conocer más detalles, no dudes en contactarnos.\n\n";
            $mensaje .= "Agradecemos tu comprensión.";
        }

        return $mensaje;
    }
}
