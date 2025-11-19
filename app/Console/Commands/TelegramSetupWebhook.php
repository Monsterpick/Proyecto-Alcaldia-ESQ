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
        $appUrl = config('app.url');
        
        if (!$appUrl || $appUrl === 'http://localhost') {
            $this->warn('⚠️  APP_URL no configurada, webhook no configurado');
            return 0;
        }
        
        $webhookUrl = $appUrl . '/api/telegram/webhook';
        
        $this->info('🤖 Configurando webhook de Telegram...');
        $this->line("📍 URL: {$webhookUrl}");
        
        try {
            Telegram::setWebhook(['url' => $webhookUrl]);
            $this->info('✅ Webhook configurado exitosamente!');
            
            // Verificar configuración
            $info = Telegram::getWebhookInfo();
            if ($info['url'] === $webhookUrl) {
                $this->info('✅ Webhook verificado y funcionando');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
