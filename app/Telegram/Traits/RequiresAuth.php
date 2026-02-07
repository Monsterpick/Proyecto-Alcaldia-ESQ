<?php

namespace App\Telegram\Traits;

use App\Models\User;
use App\Traits\LogsActivity;

trait RequiresAuth
{
    use LogsActivity;
    
    /**
     * Verificar si el usuario está autenticado
     * Retorna el usuario autenticado o null
     */
    protected function checkAuth()
    {
        $message = $this->getUpdate()->getMessage();
        $callbackQuery = $this->getUpdate()->getCallbackQuery();
        
        $chatId = null;
        
        if ($message) {
            $chatId = $message->getChat()->getId();
        } elseif ($callbackQuery) {
            $chatId = $callbackQuery->getMessage()->getChat()->getId();
        }
        
        if (!$chatId) {
            return null;
        }
        
        return User::where('telegram_chat_id', $chatId)->first();
    }
    
    /**
     * Enviar mensaje de no autenticado
     */
    protected function sendUnauthenticatedMessage()
    {
        $text = "🔐 *Acceso Restringido*\n\n";
        $text .= "Necesitas autenticarte para usar este comando.\n\n";
        $text .= "📝 Usa /login para iniciar sesión con tu cuenta del sistema.";
        
        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);
        
        // Log del intento no autorizado
        $from = $this->getUpdate()->getMessage() 
            ? $this->getUpdate()->getMessage()->getFrom()
            : $this->getUpdate()->getCallbackQuery()->getFrom();
        
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        
        self::logTelegramActivity(
            'Intento de acceso sin autenticación',
            [
                'command' => $this->name ?? 'unknown',
                'authenticated' => false,
            ],
            $telegramUser
        );
    }
    
    /**
     * Verificar autenticación y retornar usuario o enviar mensaje de error
     * Retorna el usuario autenticado o false si no está autenticado
     */
    protected function requireAuth()
    {
        $user = $this->checkAuth();
        
        if (!$user) {
            $this->sendUnauthenticatedMessage();
            return false;
        }
        
        return $user;
    }
}
