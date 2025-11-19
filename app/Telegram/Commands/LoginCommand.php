<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Hash;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class LoginCommand extends Command
{
    use LogsActivity;
    
    protected string $name = 'login';
    protected string $description = 'Iniciar sesión en el sistema';

    public function handle()
    {
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();
        $message = $this->getUpdate()->getMessage()->getText();
        
        // Verificar si ya está autenticado
        $existingUser = User::where('telegram_chat_id', $chatId)->first();
        
        if ($existingUser) {
            // Usuario YA tiene sesión activa
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "⚠️ *Ya tienes una sesión activa*\n\n✅ Tu cuenta *{$existingUser->name}* ya está vinculada a este chat.\n\n🔐 Si deseas iniciar sesión con otra cuenta:\n1️⃣ Cierra tu sesión actual con `/logout confirmar`\n2️⃣ Luego usa `/login email contraseña`\n\n💡 Puedes usar el bot normalmente con tu sesión actual.",
                'parse_mode' => 'Markdown',
            ]);
            return;
        }
        
        // Usuario NO está autenticado - Proceder con login
        
        // Parsear comando
        $parts = preg_split('/\s+/', trim($message));
        
        // Si solo es /login sin argumentos
        if (count($parts) < 3) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "🔐 *Autenticación Requerida*\n\n📝 *Formato:*\n`/login email contraseña`\n\n🔒 *Ejemplo:*\n`/login ag@gmail.com 1234`",
                'parse_mode' => 'Markdown',
            ]);
            return;
        }
        
        // Extraer credenciales
        $email = $parts[1];
        $password = $parts[2];
        
        // Buscar usuario
        $user = User::where('email', $email)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ *Credenciales incorrectas*\n\nUsuario o contraseña inválidos.",
                'parse_mode' => 'Markdown',
            ]);
            return;
        }
        
        // Verificar si ya está vinculado a otro chat
        if ($user->telegram_chat_id && $user->telegram_chat_id != $chatId) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "⚠️ *Cuenta ya vinculada*\n\nLa cuenta *{$user->name}* ya está vinculada a otro chat de Telegram.\n\n🔐 Si quieres vincularla a este chat:\n1️⃣ Cierra sesión desde el otro dispositivo con `/logout confirmar`\n2️⃣ Luego vuelve aquí e inicia sesión nuevamente\n\n❓ Si no reconoces la otra sesión, contacta al administrador del sistema.",
                'parse_mode' => 'Markdown',
            ]);
            
            // Log del intento
            activity('telegram')
                ->causedBy($user)
                ->withProperties([
                    'action' => 'login_attempt_already_linked',
                    'current_chat_id' => $user->telegram_chat_id,
                    'attempted_chat_id' => $chatId,
                    'email' => $email,
                ])
                ->log("Intento de login desde otro chat (cuenta ya vinculada)");
            
            return;
        }
        
        // Vincular chat
        $isFirstLogin = empty($user->telegram_chat_id);
        $user->telegram_chat_id = $chatId;
        $user->save();
        
        // Log de autenticación
        activity('auth')
            ->causedBy($user)
            ->withProperties([
                'chat_id' => $chatId,
                'email' => $email,
                'is_first_login' => $isFirstLogin,
            ])
            ->log("Autenticación exitosa en Telegram");
        
        // Mensaje de éxito
        $welcomeText = $isFirstLogin 
            ? "🎉 *¡Bienvenido {$user->name}!*\n\n✅ Tu cuenta ha sido vinculada exitosamente a este chat.\n\nAhora puedes usar todos los comandos del bot."
            : "👋 *¡Hola de nuevo {$user->name}!*\n\n✅ Sesión restablecida exitosamente.";
        
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $welcomeText,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => '📍 Parroquia Sabana Libre'], ['text' => '📍 Parroquia La Unión']],
                    [['text' => '📍 Parroquia Santa Rita'], ['text' => '📍 Parroquia Escuque']],
                    [['text' => '📊 Estadísticas'], ['text' => '❓ Ayuda']],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'persistent' => true,
            ]),
        ]);
    }
    
    private function processLogin($chatId, $username, $password, $telegramUser)
    {
        // Buscar usuario
        $user = User::where('email', $username)
            ->orWhere('name', $username)
            ->first();
        
        // Verificar credenciales
        if (!$user || !\Hash::check($password, $user->password)) {
            $this->replyWithMessage([
                'text' => "❌ *Credenciales incorrectas*\n\nUsuario o contraseña inválidos.\n\nIntenta nuevamente con `/login email contraseña`",
                'parse_mode' => 'Markdown',
            ]);
            
            self::logTelegramActivity(
                'Intento de login fallido',
                [
                    'username' => $username,
                    'reason' => 'invalid_credentials',
                ],
                $telegramUser
            );
            
            return;
        }
        
        // Verificar si ya está vinculado a otro chat
        if ($user->telegram_chat_id && $user->telegram_chat_id != $chatId) {
            $this->replyWithMessage([
                'text' => "⚠️ *Cuenta ya vinculada*\n\nEsta cuenta ya está vinculada a otro chat.\n\nSi quieres vincularla a este chat, primero usa /logout desde el otro dispositivo.",
                'parse_mode' => 'Markdown',
            ]);
            return;
        }
        
        // Autenticación exitosa
        $isFirstLogin = empty($user->telegram_chat_id);
        $user->telegram_chat_id = $chatId;
        $user->save();
        
        // Log
        self::logAuth(
            'Autenticación exitosa en Telegram',
            $user,
            [
                'chat_id' => $chatId,
                'telegram_user' => $telegramUser,
                'is_first_login' => $isFirstLogin,
            ]
        );
        
        $welcomeText = $isFirstLogin 
            ? "🎉 *¡Bienvenido {$user->name}!*\n\n✅ Tu cuenta ha sido vinculada exitosamente.\n\nAhora puedes usar todos los comandos del bot.\n\nUsa /menu para ver las opciones disponibles."
            : "👋 *¡Hola de nuevo {$user->name}!*\n\n✅ Autenticación exitosa.\n\nUsa /menu para ver las opciones disponibles.";
        
        \Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $welcomeText,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
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
                        ['text' => '📊 Estadísticas'],
                        ['text' => '❓ Ayuda'],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'persistent' => true,
            ]),
        ]);
    }
}
