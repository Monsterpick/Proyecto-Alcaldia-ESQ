<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Traits\LogsActivity;
use Telegram\Bot\Commands\Command;

class LogoutCommand extends Command
{
    use LogsActivity;
    
    protected string $name = 'logout';
    protected string $description = 'Cerrar sesión del bot';

    public function handle()
    {
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();
        $from = $this->getUpdate()->getMessage()->getFrom();
        $message = $this->getUpdate()->getMessage()->getText();
        
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        
        // Buscar usuario autenticado
        $user = User::where('telegram_chat_id', $chatId)->first();
        
        if (!$user) {
            $this->replyWithMessage([
                'text' => "❌ No tienes una sesión activa.\n\nUsa /start para comenzar.",
                'parse_mode' => 'Markdown',
            ]);
            return;
        }
        
        // Verificar si incluyó la palabra "confirmar"
        if (stripos($message, 'confirmar') === false) {
            // No confirmó - Mostrar instrucciones
            $text = "⚠️ *Confirmación Requerida*\n\n";
            $text .= "Estás a punto de cerrar tu sesión en el bot.\n\n";
            $text .= "📱 Tu cuenta *{$user->name}* será desvinculada de este chat.\n\n";
            $text .= "✍️ Para confirmar, escribe:\n";
            $text .= "`/logout confirmar`\n\n";
            $text .= "❌ Para cancelar, no escribas nada.";
            
            $this->replyWithMessage([
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
            
            // Log
            self::logTelegramActivity(
                'Solicitó confirmación de cierre de sesión',
                [
                    'command' => 'logout',
                    'user_id' => $user->id,
                    'confirmed' => false,
                ],
                $telegramUser
            );
            
            return;
        }
        
        // Confirmación recibida - Cerrar sesión
        $userName = $user->name;
        
        // Remover el chat_id del usuario
        $user->telegram_chat_id = null;
        $user->save();
        
        // Limpiar sesión de login si existe
        \App\Models\TelegramAuthSession::where('chat_id', $chatId)->delete();
        
        // Log
        self::logAuth(
            'Cerró sesión en Telegram',
            $user,
            [
                'chat_id' => $chatId,
                'telegram_user' => $telegramUser,
            ]
        );
        
        $text = "👋 *Sesión Cerrada Exitosamente*\n\n";
        $text .= "Tu cuenta *{$userName}* ha sido desvinculada de este chat.\n\n";
        $text .= "✅ Ya no podrás acceder a las funciones del bot.\n\n";
        $text .= "🔑 Para volver a usar el bot, usa /start e inicia sesión nuevamente.";
        
        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'remove_keyboard' => true,
            ]),
        ]);
    }
}
