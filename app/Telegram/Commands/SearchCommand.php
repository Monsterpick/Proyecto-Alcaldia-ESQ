<?php

namespace App\Telegram\Commands;

use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;

class SearchCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'search';
    protected string $description = 'Buscar beneficiario por nombre o cédula';

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
        $text = "🔍 *Búsqueda de Beneficiarios*\n\n";
        $text .= "Presiona el botón abajo para activar la búsqueda:";

        $keyboard = [
            'inline_keyboard' => [
                [
                    [
                        'text' => '🔍 Buscar por nombre o cédula',
                        'switch_inline_query_current_chat' => ''
                    ]
                ]
            ]
        ];

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode($keyboard),
        ]);
        
        // Registrar actividad
        self::logTelegramActivity(
            'Inició búsqueda de beneficiarios',
            [
                'command' => 'search',
                'action' => 'search_initiated'
            ],
            $telegramUser
        );
    }
}
