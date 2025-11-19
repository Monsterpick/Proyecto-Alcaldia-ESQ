<?php

namespace App\Telegram\Commands;

use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;

class CustomHelpCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'help';
    protected string $description = 'Ayuda sobre cómo usar el bot';

    public function handle()
    {
        // Verificar autenticación
        $user = $this->requireAuth();
        if (!$user) {
            return;
        }
        
        // Obtener información del usuario
        $from = $this->getUpdate()->getMessage()->getFrom();
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        
        $text = "❓ *Ayuda - Sistema 1X10 Escuque*\n\n";
        $text .= "Este bot te permite consultar información sobre beneficiarios, reportes, inventario y estadísticas del sistema.\n\n";
        
        $text .= "💡 *Comandos disponibles:*\n\n";
        
        $text .= "`/start` - Iniciar el bot y ver opciones\n";
        $text .= "`/stats` - Ver estadísticas del sistema\n";
        $text .= "`/beneficiaries` - Lista de beneficiarios\n";
        $text .= "`/search` - Buscar beneficiario por nombre o cédula\n";
        $text .= "`/logout` - Cerrar sesión\n";
        $text .= "`/help` - Ver esta ayuda\n\n";
        
        $text .= "📋 *Opciones del menú:*\n\n";
        
        $text .= "• 📊 *Estadísticas* - Ver estadísticas del sistema\n";
        $text .= "• 👥 *Beneficiarios* - Lista de beneficiarios\n";
        $text .= "• 📦 *Reportes* - Ver reportes de entregas\n";
        $text .= "• 📋 *Inventario* - Estado del inventario\n";
        $text .= "• 🔍 *Buscar* - Búsqueda rápida de beneficiarios\n\n";
        
        $text .= "💡 *Tip:* Usa los botones del teclado para acceder rápidamente a todas las opciones.\n\n";
        
        $text .= "📞 *¿Necesitas más ayuda?*\n";
        $text .= "Contacta al administrador del sistema.";

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);
        
        // Registrar actividad
        self::logTelegramActivity(
            'Consultó la ayuda del bot',
            [
                'command' => 'help',
                'custom_help' => true,
            ],
            $telegramUser
        );
    }
}
