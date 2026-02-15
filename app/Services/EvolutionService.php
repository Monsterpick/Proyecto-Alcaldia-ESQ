<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionService
{
    protected $baseUrl;
    protected $apiKey;
    protected $isConfigured;

    public function __construct()
    {
        $this->baseUrl = config('app.evolution_api_url', env('EVOLUTION_API_URL'));
        $this->apiKey = config('app.evolution_api_key', env('EVOLUTION_API_KEY'));

        $this->isConfigured = !empty($this->baseUrl) && !empty($this->apiKey);

        if (!$this->isConfigured) {
            Log::warning('Evolution API no configurada completamente', [
                'baseUrl' => $this->baseUrl,
                'apiKey' => $this->apiKey ? 'configurado' : 'faltante',
            ]);
        }
    }

    /**
     * Helper para hacer las peticiones HTTP
     */
    protected function request()
    {
        if (!$this->isConfigured) {
            throw new \Exception('Evolution API no está configurada. Verifica EVOLUTION_API_URL y EVOLUTION_API_KEY en .env');
        }

        $headers = [
            'Content-Type' => 'application/json',
        ];
        // Evolution API estándar usa header "apikey". Algunos hosts (ej. nevora) pueden usar "Authorization: Bearer"
        if (config('app.evolution_auth_bearer', false)) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        } else {
            $headers['apikey'] = $this->apiKey;
        }

        // Timeout 30 s para evitar cURL 28 (SSL connection timeout) con Evolution/nevora
        return Http::withHeaders($headers)
            ->timeout(30)
            ->connectTimeout(15)
            ->baseUrl($this->baseUrl);
    }

    /**
     * Crear una instancia y obtener el QR
     */
    public function createInstance($instanceName)
    {
        $response = $this->request()->post('/instance/create', [
            'instanceName' => $instanceName,
            'qrcode' => true,
            'integration' => 'WHATSAPP-BAILEYS',
        ]);

        return $response->json();
    }

    /**
     * Enviar documento (ej. PDF) por WhatsApp.
     * @param string $number Número destino (con código país)
     * @param string $media Base64 del archivo o URL pública
     * @param string $filename Nombre del archivo (ej. solicitud-123.pdf)
     * @param string|null $caption Texto opcional que acompaña el documento
     */
    public function sendDocument(string $number, string $media, string $filename, ?string $caption = null): array
    {
        if (!$this->isConfigured) {
            Log::warning('Evolution API no configurada - envío de documento omitido');
            return ['error' => false, 'success' => true, 'simulated' => true];
        }

        $instanceName = config('app.evolution_instance_name', 'Sistema_1x10_1');
        $numberClean = preg_replace('/[^0-9]/', '', $number);
        if (strlen($numberClean) < 10) {
            return ['error' => true, 'message' => 'Número de teléfono inválido'];
        }

        try {
            // Evolution API requiere base64 PURO (sin prefijo data:...) o URL
            $mediaValue = str_starts_with($media, 'http') ? $media : $media;

            $payload = [
                'number' => $numberClean,
                'mediatype' => 'document',
                'media' => $mediaValue,
                'fileName' => $filename,
            ];
            if ($caption !== null && $caption !== '') {
                $payload['caption'] = $caption;
            }

            $response = $this->request()->post("/message/sendMedia/{$instanceName}", $payload);
            $result = $response->json();

            $validStatuses = ['PENDING', 'SUCCESS', 'SENT'];
            $hasError = isset($result['error']) || !$response->successful() ||
                (isset($result['status']) && !in_array($result['status'], $validStatuses));

            if ($hasError) {
                Log::error('Error al enviar documento por Evolution API', [
                    'response' => $result,
                    'status_code' => $response->status(),
                ]);
                return [
                    'error' => true,
                    'message' => $result['message'] ?? $result['error'] ?? 'Error al enviar documento',
                ];
            }

            Log::info('Documento enviado por WhatsApp', ['number' => $number, 'filename' => $filename]);
            return ['error' => false, 'success' => true, 'status' => $result['status'] ?? 'SENT'];
        } catch (\Exception $e) {
            Log::error('Excepción al enviar documento por WhatsApp', ['error' => $e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Enviar un mensaje de texto a una instancia específica
     */
    public function sendText($instanceName, $phone, $message)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        $response = $this->request()->post("/message/sendText/{$instanceName}", [
            'number' => $phone,
            'text' => $message,
        ]);

        $result = $response->json();

        Log::info('Respuesta de Evolution API', [
            'status_code' => $response->status(),
            'response' => $result,
        ]);

        return $result;
    }

    /**
     * Verificar estado de la conexión
     */
    public function checkState($instanceName)
    {
        $response = $this->request()->get("/instance/connectionState/{$instanceName}");
        return $response->json();
    }

    /**
     * Desconectar / Cerrar sesión
     */
    public function logout($instanceName)
    {
        $response = $this->request()->delete("/instance/logout/{$instanceName}");
        return $response->json();
    }

    /**
     * Obtener el QR de una instancia existente
     */
    public function fetchQR($instanceName)
    {
        $response = $this->request()->get("/instance/connect/{$instanceName}");
        $result = $response->json();

        if (!isset($result['base64']) && (!isset($result['count']) || $result['count'] == 0)) {
            sleep(1);
            $response = $this->request()->get("/instance/connect/{$instanceName}");
            $result = $response->json();
        }

        return $result;
    }

    /**
     * Eliminar una instancia completamente
     */
    public function deleteInstance($instanceName)
    {
        $response = $this->request()->delete("/instance/delete/{$instanceName}");
        return $response->json();
    }

    /**
     * Obtener información de una instancia
     */
    public function getInstance($instanceName)
    {
        $response = $this->request()->get("/instance/fetchInstances?instanceName={$instanceName}");
        return $response->json();
    }

    /**
     * Reiniciar una instancia
     */
    public function restartInstance($instanceName)
    {
        $response = $this->request()->put("/instance/restart/{$instanceName}");
        return $response->json();
    }

    /**
     * Obtener QR con reintentos inteligentes
     */
    public function getQRWithRetry($instanceName, $maxRetries = 3)
    {
        for ($i = 0; $i < $maxRetries; $i++) {
            $result = $this->fetchQR($instanceName);

            if (isset($result['base64']) || (isset($result['count']) && $result['count'] > 0)) {
                return $result;
            }

            if ($i < $maxRetries - 1) {
                sleep(2);
            }
        }

        return $result;
    }

    /**
     * Enviar mensaje de WhatsApp (método principal)
     * Si la API no está configurada, retorna éxito simulado (para desarrollo)
     */
    public function sendMessage(string $number, string $message): array
    {
        if (!$this->isConfigured) {
            Log::warning('Evolution API no configurada - WhatsApp deshabilitado. Revisa EVOLUTION_API_URL y EVOLUTION_API_KEY en .env', [
                'number' => $number,
                'message_preview' => substr($message, 0, 50),
            ]);

            return [
                'error' => false,
                'success' => true,
                'message' => 'WhatsApp deshabilitado - Evolution API no configurada',
                'simulated' => true,
            ];
        }

        $instanceName = config('app.evolution_instance_name', 'Sistema_1x10_1');
        // Asegurar que el número sea solo dígitos (E.164 sin el +)
        $numberClean = preg_replace('/[^0-9]/', '', $number);
        if (strlen($numberClean) < 10) {
            Log::warning('Número de WhatsApp con formato inválido', ['original' => $number, 'limpio' => $numberClean]);
            return [
                'error' => true,
                'message' => 'Número de teléfono inválido. Debe incluir código de país (ej: 584241234567).',
            ];
        }

        $maxAttempts = 2; // Primer intento + 1 reintento en caso de timeout
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                Log::info('Enviando WhatsApp por Evolution API', [
                    'number' => $numberClean,
                    'instance' => $instanceName,
                    'api_url' => $this->baseUrl,
                    'attempt' => $attempt,
                ]);

                $result = $this->sendText($instanceName, $numberClean, $message);

                $validStatuses = ['PENDING', 'SUCCESS', 'SENT'];
                $hasError = isset($result['error']) ||
                            (isset($result['status']) && !in_array($result['status'], $validStatuses));

                if ($hasError) {
                    Log::error('Error en respuesta de Evolution API', [
                        'response' => $result,
                        'number' => $number,
                    ]);

                    return [
                        'error' => true,
                        'message' => $result['message'] ?? $result['error'] ?? 'Error al enviar mensaje',
                        'response' => $result,
                    ];
                }

                Log::info('WhatsApp enviado exitosamente por Evolution API', [
                    'number' => $number,
                    'instance' => $instanceName,
                    'status' => $result['status'] ?? 'unknown',
                ]);

                return [
                    'error' => false,
                    'success' => true,
                    'message' => 'Mensaje enviado correctamente',
                    'status' => $result['status'] ?? 'SENT',
                    'response' => $result,
                ];

            } catch (\Exception $e) {
                $lastException = $e;
                $isTimeout = stripos($e->getMessage(), 'timeout') !== false || stripos($e->getMessage(), '28') !== false;

                Log::warning('Excepción al enviar WhatsApp', [
                    'error' => $e->getMessage(),
                    'number' => $number,
                    'instance' => $instanceName,
                    'attempt' => $attempt,
                    'will_retry' => $isTimeout && $attempt < $maxAttempts,
                ]);

                if ($isTimeout && $attempt < $maxAttempts) {
                    sleep(3); // Esperar 3 s antes de reintentar
                    continue;
                }

                Log::error('Excepción al enviar WhatsApp (sin más reintentos)', [
                    'error' => $e->getMessage(),
                    'number' => $number,
                    'instance' => $instanceName,
                ]);

                return [
                    'error' => true,
                    'message' => 'Error de conexión: ' . $e->getMessage(),
                ];
            }
        }

        return [
            'error' => true,
            'message' => 'Error de conexión: ' . ($lastException ? $lastException->getMessage() : 'Timeout o error de red'),
        ];
    }
}
