<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramSetupWebhook extends Command
{
    protected $signature = 'telegram:setup-webhook';
    protected $description = 'Configurar webhook automáticamente en producción';

    public function handle()
    {
        // Obtener URL de la aplicación
        $appUrl = rtrim(config('app.url'), '/');
        
        if (!$appUrl || $appUrl === 'http://localhost') {
            $this->warn('⚠️  APP_URL no configurada o es localhost. Configura APP_URL en .env con la URL pública del servidor (ej: https://midominio.com)');
            return 1;
        }

        if (str_starts_with($appUrl, 'http://') && config('app.env') === 'production') {
            $this->warn('⚠️  Telegram requiere HTTPS para el webhook. APP_URL actual es HTTP: ' . $appUrl);
            $this->line('   Usa una URL https:// para que el bot reciba mensajes.');
        }
        
        $webhookUrl = $appUrl . '/api/telegram/webhook';
        
        try {
            $info = Telegram::getWebhookInfo();
            $currentUrl = is_array($info) ? ($info['url'] ?? '') : ($info->url ?? '');
            if ($currentUrl) {
                $this->line('📍 Webhook actual en Telegram: ' . $currentUrl);
            }
        } catch (\Throwable $e) {
            // Ignorar si falla obtener info (ej. token no configurado)
        }
        
        $this->info('🤖 Configurando webhook de Telegram...');
        $this->line("📍 Nueva URL: {$webhookUrl}");
        
        try {
            Telegram::setWebhook(['url' => $webhookUrl]);
            $this->info('✅ Webhook configurado exitosamente.');
            
            $info = Telegram::getWebhookInfo();
            $data = is_array($info) ? $info : $info->toArray();
            $verifiedUrl = $data['url'] ?? null;
            if ($verifiedUrl === $webhookUrl) {
                $this->info('✅ Verificado: Telegram enviará las actualizaciones a este servidor.');
            } else {
                $this->line('   URL registrada en Telegram: ' . ($verifiedUrl ?: '(vacía)'));
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
