<?php

namespace App\Telegram\Commands;

use App\Traits\LogsActivity;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class StartCommand extends Command
{
    use LogsActivity;
    
    protected string $name = 'start';
    protected string $description = 'Iniciar el bot y ver opciones disponibles';

    public function handle()
    {
        $from = $this->getUpdate()->getMessage()->getFrom();
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();
        
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        
        // Verificar si el usuario ya está autenticado
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();
        
        if (!$user) {
            // Usuario NO autenticado - Mensajes de bienvenida e instrucciones
            
            // Mensaje 1: Bienvenida
            $welcomeText = "👋 ¡Hola *{$from->getFirstName()}*!\n\n";
            $welcomeText .= "🎯 Bienvenido al *Sistema Web de Gestion de la Alcaldia del Municipio Escuque*\n\n";
            $welcomeText .= "📱 Este bot te permite:\n";
            $welcomeText .= "• Ver reportes por parroquia (Sabana Libre, La Unión, Santa Rita, Escuque)\n";
            $welcomeText .= "• Consultar reportes por categoría (Medicamentos, Ayudas Técnicas, Otros)\n";
            $welcomeText .= "• Ver estadísticas globales y por parroquia\n";
            $welcomeText .= "• Buscar beneficiarios rápidamente\n";
            $welcomeText .= "• Visualizar gráficos en tiempo real\n";
            $welcomeText .= "• Y mucho más...\n\n";
            $welcomeText .= "🔐 *Para comenzar, debes iniciar sesión con tu cuenta del sistema.*";
            
            $this->replyWithMessage([
                'text' => $welcomeText,
                'parse_mode' => 'Markdown',
            ]);
            
            // Mensaje 2: Instrucciones de login
            $instructionsText = "📝 *Instrucciones para Iniciar Sesión:*\n\n";
            $instructionsText .= "Escribe el comando `/login` seguido de tu email y contraseña\n\n";
            $instructionsText .= "*Formato:*\n";
            $instructionsText .= "`/login tu_email@mail.com tu_contraseña`\n\n";
            $instructionsText .= "⚠️ *Importante:*\n";
            $instructionsText .= "• Todo en una sola línea\n";
            $instructionsText .= "• Separado por espacios\n";
            $instructionsText .= "• Email y contraseña de tu cuenta del sistema\n\n";
            $instructionsText .= "✍️ Escribe tu comando ahora para iniciar sesión.";
            
            \Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $instructionsText,
                'parse_mode' => 'Markdown',
            ]);
            
            // Log
            self::logTelegramActivity(
                'Usuario no autenticado vio pantalla de bienvenida',
                [
                    'command' => 'start',
                    'authenticated' => false,
                ],
                $telegramUser
            );
            
            return;
        }
        
        // Usuario autenticado - mostrar menú completo
        $text = "👋 ¡Hola de nuevo *{$user->name}*!\n\n";
        $text .= "🎯 Bienvenido al *Sistema Web de Gestion de la Alcaldia del Municipio Escuque*\n\n";
        $text .= "📍 *Selecciona una parroquia* para ver sus reportes por categoría\n\n";
        $text .= "🔍 *Buscar Beneficiario* - Buscar por nombre o cédula\n\n";
        $text .= "📊 *Estadísticas* - Ver estadísticas globales del sistema\n\n";
        $text .= "❓ *Ayuda* - Guía completa de uso del bot\n\n";
        $text .= "💡 *Comandos útiles:*\n";
        $text .= "• /menu - Ver menú principal\n";
        $text .= "• /help - Ver ayuda completa\n";
        $text .= "• /logout - Cerrar sesión";

        // Teclado con todos los botones
        $keyboard = Keyboard::make([
            'keyboard' => [
                [
                    ['text' => '📍 Parroquia Sabana Libre'],
                    ['text' => '📍 Parroquia La Unión'],
                ],
                [
                    ['text' => '📍 Parroquia Santa Rita'],
                    ['text' => '📍 Parroquia Escuque'],
                ],
                [
                    ['text' => '🔍 Buscar Beneficiario'],
                ],
                [
                    ['text' => '📊 Estadísticas'],
                    ['text' => '❓ Ayuda'],
                ],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'persistent' => true,
        ]);

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
            'reply_markup' => $keyboard,
        ]);
        
        // Registrar actividad
        self::logTelegramActivity(
            'Inició el bot (autenticado)',
            [
                'command' => 'start',
                'authenticated' => true,
                'user_id' => $user->id,
                'user_name' => $user->name,
            ],
            $telegramUser
        );
    }
}
