<?php

namespace App\Telegram\Commands;

use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class MenuCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'menu';
    protected string $description = 'Mostrar menú de opciones';

    public function handle()
    {
        // Verificar autenticación
        $user = $this->requireAuth();
        if (!$user) {
            return;
        }
        
        $from = $this->getUpdate()->getMessage()->getFrom();
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        
        $text = "📋 *Menú Principal*\n\n";
        $text .= "Hola *{$user->name}*, selecciona una parroquia para ver sus reportes:\n\n";
        $text .= "📍 *Parroquias Disponibles:*\n";
        $text .= "• Parroquia Sabana Libre\n";
        $text .= "• Parroquia La Unión\n";
        $text .= "• Parroquia Santa Rita\n";
        $text .= "• Parroquia Escuque\n\n";
        $text .= "🔍 *Buscar Beneficiario* - Buscar por nombre o cédula\n";
        $text .= "📊 *Estadísticas* - Ver estadísticas globales del sistema\n";
        $text .= "❓ *Ayuda* - Ver guía completa del bot";

        $keyboard = [
            [['text' => '📍 Parroquia Sabana Libre'], ['text' => '📍 Parroquia La Unión']],
            [['text' => '📍 Parroquia Santa Rita'], ['text' => '📍 Parroquia Escuque']],
            [['text' => '🔍 Buscar Beneficiario']],
            [['text' => '📊 Estadísticas'], ['text' => '❓ Ayuda']],
        ];

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'persistent' => true,
            ]),
        ]);
        
        // Registrar actividad
        self::logTelegramActivity(
            'Accedió al menú principal',
            [
                'command' => 'menu',
            ],
            $telegramUser
        );
    }
}
