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

        // Verificar si está configurado
        $this->isConfigured = !empty($this->baseUrl) && !empty($this->apiKey);

        if (!$this->isConfigured) {
            Log::warning('Evolution API no configurada completamente', [
                'baseUrl' => $this->baseUrl,
                'apiKey' => $this->apiKey ? 'configurado' : 'faltante'
            ]);
        }
    }

    /**
     * Helper para hacer las peticiones
     */
    protected function request()
    {
        // Si no está configurado, retornar cliente HTTP básico para evitar errores
        if (!$this->isConfigured) {
            throw new \Exception('Evolution API no está configurada. Verifica EVOLUTION_API_URL y EVOLUTION_API_KEY en .env');
        }

        return Http::withHeaders([
            'apikey' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->baseUrl($this->baseUrl);
    }

    /**
     * 1. Crear una instancia y obtener el QR
     */
    public function createInstance($instanceName)
    {
        $response = $this->request()->post('/instance/create', [
            'instanceName' => $instanceName,
            'qrcode' => true,
            'integration' => 'WHATSAPP-BAILEYS'
        ]);

        return $response->json();
    }

    /**
     * 2. Enviar un mensaje de texto
     */
    public function sendText($instanceName, $phone, $message)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        $response = $this->request()->post("/message/sendText/{$instanceName}", [
            'number' => $phone,
            'text' => $message
        ]);

        $result = $response->json();

        Log::info('Respuesta de Evolution API', [
            'status_code' => $response->status(),
            'response' => $result
        ]);

        return $result;
    }

    /**
     * 3. Verificar estado de la conexión
     */
    public function checkState($instanceName)
    {
        $response = $this->request()->get("/instance/connectionState/{$instanceName}");
        return $response->json();
    }

    /**
     * 4. Desconectar / Cerrar sesión
     */
    public function logout($instanceName)
    {
        $response = $this->request()->delete("/instance/logout/{$instanceName}");
        return $response->json();
    }

    /**
     * 5. Obtener el QR de una instancia existente
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
     * 6. Eliminar una instancia completamente
     */
    public function deleteInstance($instanceName)
    {
        $response = $this->request()->delete("/instance/delete/{$instanceName}");
        return $response->json();
    }

    /**
     * 7. Obtener información de una instancia
     */
    public function getInstance($instanceName)
    {
        $response = $this->request()->get("/instance/fetchInstances?instanceName={$instanceName}");
        return $response->json();
    }

    /**
     * 8. Reiniciar una instancia
     */
    public function restartInstance($instanceName)
    {
        $response = $this->request()->put("/instance/restart/{$instanceName}");
        return $response->json();
    }

    /**
     * 9. Obtener QR con reintentos inteligentes
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
     * 10. Enviar mensaje - Intenta conectar a Evolution API real
     */
    public function sendMessage(string $number, string $message): array
    {
        // Si no está configurado, retornar éxito simulado
        if (!$this->isConfigured) {
            Log::warning('Evolution API no configurada - WhatsApp deshabilitado', [
                'number' => $number,
                'message_preview' => substr($message, 0, 50)
            ]);

            return [
                'error' => false,
                'success' => true,
                'message' => 'WhatsApp deshabilitado - Evolution API no configurada',
                'simulated' => true
            ];
        }

        $instanceName = 'Consejo_Municipal_1';

        try {
            Log::info('Intentando enviar WhatsApp por Evolution API', [
                'number' => $number,
                'instance' => $instanceName,
                'api_url' => $this->baseUrl,
            ]);

            $result = $this->sendText($instanceName, $number, $message);


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
                    'response' => $result
                ];
            }

            Log::info('✅ WhatsApp enviado exitosamente por Evolution API', [
                'number' => $number,
                'instance' => $instanceName,
                'status' => $result['status'] ?? 'unknown',
                'response' => $result
            ]);

            return [
                'error' => false,
                'success' => true,
                'message' => 'Mensaje enviado correctamente',
                'status' => $result['status'] ?? 'SENT',
                'response' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Excepción al enviar WhatsApp', [
                'error' => $e->getMessage(),
                'number' => $number,
                'instance' => $instanceName,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => true,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }
}
