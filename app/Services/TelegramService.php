<?php

namespace App\Services;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Enviar mensaje simple
     */
    public function sendMessage(string $chatId, string $message, array $options = []): bool
    {
        try {
            Telegram::sendMessage(array_merge([
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ], $options));
            
            return true;
        } catch (TelegramSDKException $e) {
            Log::error('Telegram Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Notificar nuevo beneficiario registrado
     */
    public function notifyNewBeneficiary($beneficiary, string $chatId): bool
    {
        $message = "✅ *Nuevo Beneficiario Registrado*\n\n";
        $message .= "👤 *Nombre:* {$beneficiary->full_name}\n";
        $message .= "🆔 *Cédula:* {$beneficiary->full_cedula}\n";
        $message .= "📱 *Teléfono:* {$beneficiary->phone}\n";
        $message .= "📍 *Ubicación:* {$beneficiary->state}, {$beneficiary->municipality}\n";
        $message .= "🕐 *Fecha:* " . now()->format('d/m/Y H:i');

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Notificar nueva entrega registrada
     */
    public function notifyNewDelivery($report, string $chatId): bool
    {
        $message = "📦 *Nueva Entrega Registrada*\n\n";
        $message .= "📋 *Código:* {$report->report_code}\n";
        $message .= "👤 *Beneficiario:* {$report->beneficiary_full_name}\n";
        $message .= "🆔 *Cédula:* {$report->beneficiary_full_cedula}\n";
        $message .= "📅 *Fecha de entrega:* " . $report->delivery_date->format('d/m/Y') . "\n";
        $message .= "📍 *Ubicación:* {$report->state}, {$report->municipality}\n";
        
        if ($report->notes) {
            $message .= "📝 *Notas:* {$report->notes}\n";
        }
        
        $message .= "🕐 *Registrado:* " . now()->format('d/m/Y H:i');

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Notificar entrega completada
     */
    public function notifyDeliveryCompleted($report, string $chatId): bool
    {
        $message = "✅ *Entrega Completada*\n\n";
        $message .= "📋 *Código:* {$report->report_code}\n";
        $message .= "👤 *Beneficiario:* {$report->beneficiary_full_name}\n";
        $message .= "📅 *Fecha:* " . $report->delivery_date->format('d/m/Y') . "\n";
        $message .= "🕐 *Completado:* " . now()->format('d/m/Y H:i');

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Notificar stock bajo
     */
    public function notifyLowStock($product, int $quantity, string $chatId): bool
    {
        $message = "⚠️ *Alerta de Stock Bajo*\n\n";
        $message .= "📦 *Producto:* {$product->name}\n";
        $message .= "📊 *Cantidad disponible:* {$quantity} unidades\n";
        $message .= "⚠️ Se recomienda reabastecer este producto\n";
        $message .= "🕐 *Fecha:* " . now()->format('d/m/Y H:i');

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Notificar movimiento de inventario
     */
    public function notifyInventoryMovement($movement, string $chatId): bool
    {
        $type = $movement->type === 'in' ? '📥 Entrada' : '📤 Salida';
        $product = $movement->product;
        $warehouse = $movement->warehouse;
        
        $message = "*Movimiento de Inventario*\n\n";
        $message .= "🔄 *Tipo:* {$type}\n";
        $message .= "📦 *Producto:* {$product->name}\n";
        $message .= "📊 *Cantidad:* {$movement->quantity}\n";
        $message .= "🏢 *Almacén:* {$warehouse->name}\n";
        
        if ($movement->notes) {
            $message .= "📝 *Notas:* {$movement->notes}\n";
        }
        
        $message .= "🕐 *Fecha:* " . now()->format('d/m/Y H:i');

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Enviar reporte diario
     */
    public function sendDailyReport(string $chatId): bool
    {
        $newBeneficiaries = \App\Models\Beneficiary::whereDate('created_at', today())->count();
        $newReports = \App\Models\Report::whereDate('created_at', today())->count();
        $completedReports = \App\Models\Report::whereDate('updated_at', today())
            ->where('status', 'delivered')
            ->count();

        $message = "📊 *Reporte Diario - " . now()->format('d/m/Y') . "*\n\n";
        $message .= "👥 *Beneficiarios:*\n";
        $message .= "   • Nuevos registros: {$newBeneficiaries}\n\n";
        $message .= "📦 *Entregas:*\n";
        $message .= "   • Nuevos reportes: {$newReports}\n";
        $message .= "   • Entregas completadas: {$completedReports}\n\n";
        $message .= "🕐 Generado: " . now()->format('H:i');

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Obtener información del bot
     */
    public function getBotInfo(): array
    {
        try {
            $response = Telegram::getMe();
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (TelegramSDKException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Configurar webhook
     */
    public function setWebhook(string $url): array
    {
        try {
            $response = Telegram::setWebhook(['url' => $url]);
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (TelegramSDKException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Eliminar webhook
     */
    public function removeWebhook(): array
    {
        try {
            $response = Telegram::removeWebhook();
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (TelegramSDKException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
