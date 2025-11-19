<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    use LogsActivity;
    
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Manejar actualizaciones del webhook
     */
    public function webhook()
    {
        try {
            $update = Telegram::getWebhookUpdate();
            
            // Obtener información del usuario de Telegram
            $telegramUser = null;
            $chatId = null;
            
            if ($message = $update->getMessage()) {
                $from = $message->getFrom();
                $chatId = $message->getChat()->getId();
                $telegramUser = [
                    'id' => $from->getId(),
                    'username' => $from->getUsername(),
                    'first_name' => $from->getFirstName(),
                    'last_name' => $from->getLastName(),
                ];
            }
            
            // Procesar flujo de autenticación
            if ($message && $chatId) {
                $loginProcessed = $this->handleLoginFlow($message, $chatId, $telegramUser);
                if ($loginProcessed) {
                    return response()->json(['status' => 'ok']);
                }
            }
            
            // Manejar inline queries
            if ($inlineQuery = $update->getInlineQuery()) {
                $from = $inlineQuery->getFrom();
                $inlineChatId = $from->getId();
                $telegramUser = [
                    'id' => $from->getId(),
                    'username' => $from->getUsername(),
                    'first_name' => $from->getFirstName(),
                    'last_name' => $from->getLastName(),
                ];
                
                // Verificar autenticación para búsquedas inline
                $user = \App\Models\User::where('telegram_chat_id', $inlineChatId)->first();
                
                if (!$user) {
                    // Usuario no autenticado - mostrar mensaje en resultados inline
                    Telegram::answerInlineQuery([
                        'inline_query_id' => $inlineQuery->getId(),
                        'results' => json_encode([]),
                        'cache_time' => 0,
                        'switch_pm_text' => '🔐 Inicia sesión para buscar',
                        'switch_pm_parameter' => 'login',
                    ]);
                    
                    self::logTelegramActivity(
                        'Intento de búsqueda inline sin autenticación',
                        [
                            'query' => $inlineQuery->getQuery(),
                            'action' => 'inline_search_unauthorized'
                        ],
                        $telegramUser
                    );
                    
                    return response()->json(['status' => 'ok']);
                }
                
                $this->handleInlineQuery($inlineQuery);
                
                // Registrar búsqueda inline
                self::logTelegramActivity(
                    'Búsqueda inline realizada',
                    [
                        'query' => $inlineQuery->getQuery(),
                        'action' => 'inline_search',
                        'user_id' => $user->id,
                    ],
                    $telegramUser
                );
                
                return response()->json(['status' => 'ok']);
            }
            
            // Manejar callback queries (botones inline)
            if ($callbackQuery = $update->getCallbackQuery()) {
                $from = $callbackQuery->getFrom();
                $callbackChatId = $callbackQuery->getMessage()->getChat()->getId();
                $telegramUser = [
                    'id' => $from->getId(),
                    'username' => $from->getUsername(),
                    'first_name' => $from->getFirstName(),
                    'last_name' => $from->getLastName(),
                ];
                
                $data = $callbackQuery->getData();
                
                // Manejar botón de "Iniciar Sesión"
                if ($data === 'show_login_instructions') {
                    Telegram::answerCallbackQuery([
                        'callback_query_id' => $callbackQuery->getId(),
                        'text' => '📝 Lee las instrucciones abajo',
                        'show_alert' => false,
                    ]);
                    
                    // Enviar instrucciones detalladas
                    Telegram::sendMessage([
                        'chat_id' => $callbackChatId,
                        'text' => "📝 *Instrucciones para Iniciar Sesión:*\n\n1️⃣ Escribe el comando `/login` seguido de tu email y contraseña\n\n2️⃣ *Formato:*\n`/login tu_email@mail.com tu_contraseña`\n\n3️⃣ *Ejemplo real:*\n`/login ag@gmail.com 1234`\n\n⚠️ *Importante:*\n• Todo en una sola línea\n• Separado por espacios\n• Email y contraseña de tu cuenta del sistema\n\n✍️ Escribe tu comando ahora:",
                        'parse_mode' => 'Markdown',
                    ]);
                    
                    self::logTelegramActivity(
                        'Solicitó instrucciones de login',
                        ['action' => 'show_login_instructions'],
                        $telegramUser
                    );
                    
                    return response()->json(['status' => 'ok']);
                }
                
                
                Telegram::answerCallbackQuery([
                    'callback_query_id' => $callbackQuery->getId(),
                ]);
                
                // Manejar solicitudes de PDF
                if (strpos($data, 'pdf_') === 0) {
                    $reportId = str_replace('pdf_', '', $data);
                    $this->sendReportPdf($callbackChatId, $reportId, $telegramUser);
                    return response()->json(['status' => 'ok']);
                }
                
                // Manejar callbacks de parroquias
                if (strpos($data, 'parish_') === 0) {
                    $this->handleParishCallback($callbackChatId, $data, $telegramUser);
                    return response()->json(['status' => 'ok']);
                }
                
                if (strpos($data, 'cmd_') === 0) {
                    $commandName = str_replace('cmd_', '', $data);
                    Telegram::commandsHandler(true);
                    
                    // Registrar comando ejecutado vía botón
                    self::logTelegramActivity(
                        "Comando ejecutado: {$commandName}",
                        [
                            'command' => $commandName,
                            'action' => 'callback_query'
                        ],
                        $telegramUser
                    );
                }
                
                return response()->json(['status' => 'ok']);
            }
            
            // Manejar mensajes
            if ($message = $update->getMessage()) {
                $text = $message->getText();
                $chatId = $message->getChat()->getId();
                $from = $message->getFrom();
                
                $telegramUser = [
                    'id' => $from->getId(),
                    'username' => $from->getUsername(),
                    'first_name' => $from->getFirstName(),
                    'last_name' => $from->getLastName(),
                ];
                
                // Log para debugging
                logger()->info('========= MENSAJE RECIBIDO =========', [
                    'text' => $text,
                    'text_length' => strlen($text),
                    'chat_id' => $chatId,
                ]);
                
                // Verificar autenticación para botones de parroquia
                $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();
                
                // Verificar si es un botón de parroquia (contiene "Parroquia")
                if (strpos($text, 'Parroquia') !== false) {
                    // Verificar autenticación
                    if (!$user) {
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text' => "🔐 *Debes iniciar sesión primero*\n\nUsa /login para autenticarte.",
                            'parse_mode' => 'Markdown',
                        ]);
                        
                        // Registrar intento sin autenticación
                        self::logTelegramActivity(
                            "Intento de acceso a parroquia sin autenticación",
                            [
                                'text' => $text,
                                'action' => 'parish_unauthorized'
                            ],
                            $telegramUser
                        );
                        
                        return response()->json(['status' => 'ok']);
                    }
                    
                    // Extraer el nombre de la parroquia
                    $parishName = trim(str_replace(['📍', 'Parroquia'], '', $text));
                    
                    // Registrar actividad
                    self::logTelegramActivity(
                        "Accedió a parroquia: {$parishName}",
                        [
                            'parish' => $parishName,
                            'button_text' => $text,
                            'action' => 'parish_button'
                        ],
                        $telegramUser
                    );
                    
                    // Mostrar menú de la parroquia
                    $this->showParroquiaMenu($chatId, $parishName, $telegramUser);
                    return response()->json(['status' => 'ok']);
                }
                
                // Mapear otros botones del teclado a comandos
                $commandMap = [
                    '📊 Estadísticas' => 'stats',
                    '❓ Ayuda' => 'help',
                ];
                
                if (isset($commandMap[$text])) {
                    // Botón del teclado presionado - Ejecutar comando correspondiente
                    $commandName = $commandMap[$text];
                    
                    // Registrar comando ejecutado vía botón del teclado
                    self::logTelegramActivity(
                        "Comando ejecutado: {$commandName}",
                        [
                            'command' => $commandName,
                            'button_text' => $text,
                            'action' => 'keyboard_button'
                        ],
                        $telegramUser
                    );
                    
                    // Ejecutar el comando correspondiente
                    $commandClass = null;
                    switch($commandName) {
                        case 'stats':
                            $commandClass = new \App\Telegram\Commands\StatsCommand();
                            break;
                        case 'help':
                            $commandClass = new \App\Telegram\Commands\HelpCommand();
                            break;
                    }
                    
                    if ($commandClass) {
                        $commandClass->make(Telegram::bot(), $update, []);
                        $commandClass->handle();
                    }
                    
                    return response()->json(['status' => 'ok']);
                } elseif (strpos($text, '/') === 0) {
                    // Registrar comando ejecutado vía texto
                    $command = trim(explode(' ', $text)[0], '/');
                    self::logTelegramActivity(
                        "Comando ejecutado: {$command}",
                        [
                            'command' => $command,
                            'full_text' => $text,
                            'action' => 'text_command'
                        ],
                        $telegramUser
                    );
                } else {
                    // Registrar mensaje de texto
                    self::logTelegramActivity(
                        'Mensaje de texto recibido',
                        [
                            'text' => $text,
                            'action' => 'text_message'
                        ],
                        $telegramUser
                    );
                }
            }
            
            Telegram::commandsHandler(true);
            
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            // Log detallado del error
            logger()->error('========= TELEGRAM WEBHOOK ERROR =========', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Registrar error
            self::logError('Error en webhook de Telegram', $e, [
                'update' => $update ?? null
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Manejar inline queries
     */
    protected function handleInlineQuery($inlineQuery)
    {
        $query = $inlineQuery->getQuery();
        $queryId = $inlineQuery->getId();
        
        // Si no hay query, no buscar
        if (empty(trim($query))) {
            Telegram::answerInlineQuery([
                'inline_query_id' => $queryId,
                'results' => [],
                'cache_time' => 0,
            ]);
            return;
        }
        
        // Buscar beneficiarios
        $beneficiaries = \App\Models\Beneficiary::where(function($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%")
              ->orWhere('cedula', 'LIKE', "%{$query}%");
        })
        ->take(10)
        ->get();
        
        $results = [];
        
        if ($beneficiaries->count() === 0) {
            $results[] = [
                'type' => 'article',
                'id' => 'no_results',
                'title' => '❌ No se encontraron beneficiarios',
                'description' => "No hay coincidencias para: {$query}",
                'input_message_content' => [
                    'message_text' => "❌ *No se encontraron beneficiarios*\n\nNo hay coincidencias para la búsqueda: *{$query}*",
                    'parse_mode' => 'Markdown',
                ],
            ];
        }
        
        foreach ($beneficiaries as $beneficiary) {
            $reports = \App\Models\Report::where('beneficiary_cedula', $beneficiary->cedula)
                ->orderBy('delivery_date', 'desc')
                ->get();
            
            $text = "👤 *INFORMACIÓN DEL BENEFICIARIO*\n\n";
            $text .= "📋 *Datos Personales:*\n";
            $text .= "• *Nombre:* {$beneficiary->full_name}\n";
            $text .= "• *Cédula:* {$beneficiary->full_cedula}\n";
            $text .= "• *Estado:* " . ($beneficiary->status === 'active' ? '✅ Activo' : '❌ Inactivo') . "\n\n";
            
            $text .= "📍 *Ubicación:*\n";
            $text .= "• {$beneficiary->municipality}, {$beneficiary->state}\n\n";
            
            $text .= "📦 *Reportes:* {$reports->count()}\n";
            
            $status = $beneficiary->status === 'active' ? '✅' : '❌';
            
            $results[] = [
                'type' => 'article',
                'id' => (string)$beneficiary->id,
                'title' => "{$status} {$beneficiary->full_name}",
                'description' => "{$beneficiary->full_cedula}",
                'input_message_content' => [
                    'message_text' => $text,
                    'parse_mode' => 'Markdown',
                ],
            ];
        }
        
        Telegram::answerInlineQuery([
            'inline_query_id' => $queryId,
            'results' => json_encode($results),
            'cache_time' => 30,
        ]);
    }

    /**
     * Configurar el webhook
     */
    public function setWebhook(Request $request)
    {
        $url = $request->input('url', url('/api/telegram/webhook'));
        $result = $this->telegramService->setWebhook($url);
        
        return response()->json($result);
    }

    /**
     * Eliminar el webhook
     */
    public function removeWebhook()
    {
        $result = $this->telegramService->removeWebhook();
        return response()->json($result);
    }

    /**
     * Obtener información del bot
     */
    public function getMe()
    {
        $result = $this->telegramService->getBotInfo();
        return response()->json($result);
    }

    /**
     * Enviar mensaje de prueba
     */
    public function sendTestMessage(Request $request)
    {
        $chatId = $request->input('chat_id');
        $message = $request->input('message', '🤖 Mensaje de prueba desde el bot de Escuque');
        
        $sent = $this->telegramService->sendMessage($chatId, $message);
        
        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'Mensaje enviado' : 'Error al enviar mensaje'
        ]);
    }
    
    /**
     * Manejar el flujo de autenticación (login)
     */
    private function handleLoginFlow($message, $chatId, $telegramUser)
    {
        $text = $message->getText();
        
        // Log para debugging
        $logData = [
            'chat_id' => $chatId,
            'chat_id_type' => gettype($chatId),
            'text' => $text,
            'is_command' => strpos($text, '/') === 0,
        ];
        
        // Escribir en log Y en archivo temporal para debug
        logger()->info("=== LOGIN FLOW START ===", $logData);
        file_put_contents(storage_path('logs/telegram_debug.txt'), date('Y-m-d H:i:s') . " - START: " . json_encode($logData) . "\n", FILE_APPEND);
        
        // Ignorar comandos del sistema
        if (strpos($text, '/') === 0) {
            logger()->info("LOGIN FLOW: Ignorando comando", ['text' => $text]);
            return false;
        }
        
        // Limpiar sesiones expiradas
        \App\Models\TelegramAuthSession::cleanExpired();
        
        // Verificar si hay un proceso de login en curso  
        $session = \App\Models\TelegramAuthSession::where('chat_id', (string)$chatId)
            ->active()
            ->first();
        
        $debugData = [
            'chat_id' => $chatId,
            'chat_id_type' => gettype($chatId),
            'chat_id_string' => (string)$chatId,
            'session_exists' => $session ? true : false,
            'session_data' => $session ? $session->toArray() : null,
            'all_sessions' => \App\Models\TelegramAuthSession::all()->toArray(),
        ];
        
        logger()->info("LOGIN FLOW: Búsqueda de sesión", $debugData);
        file_put_contents(storage_path('logs/telegram_debug.txt'), date('Y-m-d H:i:s') . " - SEARCH: " . json_encode($debugData) . "\n", FILE_APPEND);
        
        if (!$session) {
            logger()->info("LOGIN FLOW: No hay sesión activa, saliendo");
            return false;
        }
        
        if ($session->step === 'waiting_username') {
            // Usuario ingresó su nombre de usuario o email
            $session->username = $text;
            $session->step = 'waiting_password';
            $session->expires_at = now()->addMinutes(5);
            $session->save();
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "🔑 Ahora ingresa tu *contraseña*:",
                'parse_mode' => 'Markdown',
            ]);
            
            self::logTelegramActivity(
                'Ingresó nombre de usuario',
                [
                    'step' => 'waiting_password',
                    'username_length' => strlen($text),
                ],
                $telegramUser
            );
            
            return true;
        }
        
        if ($session->step === 'waiting_password') {
            // Usuario ingresó su contraseña
            $username = $session->username;
            $password = $text;
            
            // Eliminar sesión
            $session->delete();
            
            // Buscar usuario por email o nombre
            $user = \App\Models\User::where('email', $username)
                ->orWhere('name', $username)
                ->first();
            
            // Verificar credenciales
            if (!$user || !\Hash::check($password, $user->password)) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ *Credenciales incorrectas*\n\nUsuario o contraseña inválidos.\n\nIntenta nuevamente con /login",
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
                
                return true;
            }
            
            // Verificar si el chat_id ya está en uso
            if ($user->telegram_chat_id && $user->telegram_chat_id != $chatId) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "⚠️ *Cuenta ya vinculada*\n\nEsta cuenta ya está vinculada a otro chat de Telegram.\n\nSi deseas vincularla a este chat, cierra sesión desde el otro dispositivo primero.",
                    'parse_mode' => 'Markdown',
                ]);
                
                return true;
            }
            
            // Autenticación exitosa - vincular chat_id
            $isFirstLogin = empty($user->telegram_chat_id);
            $user->telegram_chat_id = $chatId;
            $user->save();
            
            // Log de autenticación exitosa
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
            
            Telegram::sendMessage([
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
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Mostrar menú de una parroquia con inline buttons
     */
    private function showParroquiaMenu($chatId, $parish, $telegramUser)
    {
        logger()->info('========= DENTRO DE showParroquiaMenu =========', [
            'chat_id' => $chatId,
            'parish' => $parish,
        ]);
        
        $text = "📍 *Bienvenido a la Parroquia {$parish}*\n\n";
        $text .= "Presione el número correspondiente para ver los reportes de la categoría que desea:\n\n";
        $text .= "1️⃣ - Medicamentos\n";
        $text .= "2️⃣ - Ayudas Técnicas\n";
        $text .= "3️⃣ - Otros (Alimentos, Educación, Vivienda, Higiene)\n";
        $text .= "4️⃣ - Estadísticas de la Parroquia";
        
        // Convertir nombre de parroquia a formato sin espacios para callback_data
        $parishSlug = str_replace(' ', '_', $parish);
        
        // Crear inline keyboard
        $inlineKeyboard = [
            [
                ['text' => '1️⃣ Medicamentos', 'callback_data' => "parish_{$parishSlug}_cat_medicamentos"],
                ['text' => '2️⃣ Ayudas Técnicas', 'callback_data' => "parish_{$parishSlug}_cat_ayudas"],
            ],
            [
                ['text' => '3️⃣ Otros', 'callback_data' => "parish_{$parishSlug}_cat_otros"],
                ['text' => '4️⃣ Estadísticas', 'callback_data' => "parish_{$parishSlug}_stats"],
            ],
        ];
        
        try {
            logger()->info('========= ENVIANDO MENSAJE TELEGRAM =========', [
                'chat_id' => $chatId,
                'text_preview' => substr($text, 0, 50),
                'inline_keyboard_count' => count($inlineKeyboard),
            ]);
            
            $response = Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $inlineKeyboard,
                ]),
            ]);
            
            logger()->info('========= MENSAJE ENVIADO EXITOSAMENTE =========', [
                'response' => $response ? 'OK' : 'NULL',
            ]);
            
            // Registrar actividad
            self::logTelegramActivity(
                "Accedió al menú de parroquia: {$parish}",
                [
                    'parish' => $parish,
                    'action' => 'parish_menu',
                ],
                $telegramUser
            );
        } catch (\Exception $e) {
            logger()->error('========= ERROR EN showParroquiaMenu =========', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'parish' => $parish,
                'chat_id' => $chatId,
            ]);
            
            // Enviar mensaje de error al usuario
            try {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ Error: " . $e->getMessage(),
                ]);
            } catch (\Exception $e2) {
                logger()->error('No se pudo enviar mensaje de error: ' . $e2->getMessage());
            }
        }
    }
    
    /**
     * Manejar callback de categorías por parroquia
     */
    private function handleParishCallback($chatId, $callbackData, $telegramUser)
    {
        // Parsear callback: parish_{ParishName}_cat_{category} o parish_{ParishName}_stats
        preg_match('/parish_(.+?)_(cat_(.+)|stats)/', $callbackData, $matches);
        
        if (!$matches) {
            return;
        }
        
        $parish = str_replace('_', ' ', $matches[1]);
        $isStats = isset($matches[2]) && $matches[2] === 'stats';
        
        if ($isStats) {
            // Mostrar estadísticas de la parroquia
            $this->showParishStats($chatId, $parish, $telegramUser);
        } else {
            // Mostrar reportes por categoría
            $category = $matches[3];
            $this->showParishReports($chatId, $parish, $category, $telegramUser);
        }
    }
    
    /**
     * Mostrar estadísticas de una parroquia específica
     */
    private function showParishStats($chatId, $parish, $telegramUser)
    {
        // Obtener estadísticas de beneficiarios de la parroquia
        $totalBeneficiaries = \App\Models\Beneficiary::whereHas('parroquia', function($q) use ($parish) {
            $q->where('parroquia', $parish);
        })->count();
        
        $activeBeneficiaries = \App\Models\Beneficiary::whereHas('parroquia', function($q) use ($parish) {
            $q->where('parroquia', $parish);
        })->where('status', 'active')->count();
        
        $inactiveBeneficiaries = \App\Models\Beneficiary::whereHas('parroquia', function($q) use ($parish) {
            $q->where('parroquia', $parish);
        })->where('status', 'inactive')->count();
        
        // Obtener estadísticas de reportes de la parroquia
        $totalReports = \App\Models\Report::where('parish', $parish)->count();
        $deliveredReports = \App\Models\Report::where('parish', $parish)->where('status', 'delivered')->count();
        $inProcessReports = \App\Models\Report::where('parish', $parish)->where('status', 'in_process')->count();
        $notDeliveredReports = \App\Models\Report::where('parish', $parish)->where('status', 'not_delivered')->count();
        
        // Generar gráfico de beneficiarios
        $beneficiariesChart = $this->generatePieChart(
            "Beneficiarios - {$parish}",
            ['Activos', 'Inactivos'],
            [$activeBeneficiaries, $inactiveBeneficiaries],
            ['#10b981', '#ef4444']
        );
        
        // Generar gráfico de reportes
        $reportsChart = $this->generatePieChart(
            "Reportes - {$parish}",
            ['Entregados', 'En proceso', 'No entregados'],
            [$deliveredReports, $inProcessReports, $notDeliveredReports],
            ['#10b981', '#f59e0b', '#ef4444']
        );
        
        // Enviar texto con estadísticas
        $text = "📊 *Estadísticas de Parroquia {$parish}*\n\n";
        $text .= "👥 *Beneficiarios:*\n";
        $text .= "   • Total: {$totalBeneficiaries}\n";
        $text .= "   • ✅ Activos: {$activeBeneficiaries}\n";
        $text .= "   • ❌ Inactivos: {$inactiveBeneficiaries}\n\n";
        
        $text .= "📦 *Reportes de Entrega:*\n";
        $text .= "   • Total: {$totalReports}\n";
        $text .= "   • ✅ Entregados: {$deliveredReports}\n";
        $text .= "   • 🔄 En proceso: {$inProcessReports}\n";
        $text .= "   • ❌ No entregados: {$notDeliveredReports}\n\n";
        
        $text .= "📈 *Gráficos a continuación...*\n";
        $text .= "🕐 Actualizado: " . now()->format('d/m/Y H:i');
        
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);
        
        // Enviar gráficos
        if ($totalBeneficiaries > 0) {
            Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => \Telegram\Bot\FileUpload\InputFile::create($beneficiariesChart),
                'caption' => "📊 Gráfico de Beneficiarios - {$parish}",
            ]);
        }
        
        if ($totalReports > 0) {
            Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => \Telegram\Bot\FileUpload\InputFile::create($reportsChart),
                'caption' => "📦 Gráfico de Reportes - {$parish}",
            ]);
        }
        
        // Registrar actividad
        self::logTelegramActivity(
            "Consultó estadísticas de parroquia: {$parish}",
            [
                'parish' => $parish,
                'action' => 'parish_stats',
                'stats' => [
                    'beneficiaries' => $totalBeneficiaries,
                    'reports' => $totalReports,
                ]
            ],
            $telegramUser
        );
    }
    
    /**
     * Mostrar reportes de una categoría específica de una parroquia
     */
    private function showParishReports($chatId, $parish, $category, $telegramUser)
    {
        // Inicializar variables antes del try para que estén disponibles en todo el scope
        $categoryDisplay = 'Desconocida';
        $totalReports = 0;
        
        try {
            // Mapear categorías a IDs o nombres en la BD
            $categoryMap = [
                'medicamentos' => 'Medicamentos',
                'ayudas' => 'Ayudas técnicas',
                'otros' => ['Alimentos y Despensa', 'Educación y Útiles', 'Vivienda', 'Higiene Personal'],
            ];
            
            $categoryName = $categoryMap[$category] ?? null;
            
            if (!$categoryName) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ Categoría no encontrada.",
                    'parse_mode' => 'Markdown',
                ]);
                
                // Registrar intento con categoría inválida
                self::logTelegramActivity(
                    "Intentó consultar reportes con categoría inválida: {$category} en parroquia: {$parish}",
                    [
                        'parish' => $parish,
                        'category' => $category,
                        'action' => 'parish_category_reports_invalid',
                        'error' => 'invalid_category',
                    ],
                    $telegramUser
                );
                return;
            }
            
            // Obtener reportes por categoría usando la relación directa
            $query = \App\Models\Report::where('parish', $parish)
                ->whereHas('categories', function($q) use ($categoryName) {
                    if (is_array($categoryName)) {
                        $q->whereIn('categories.name', $categoryName);
                    } else {
                        $q->where('categories.name', $categoryName);
                    }
                });
            
            $totalReports = $query->count();
            $deliveredReports = (clone $query)->where('status', 'delivered')->count();
            $inProcessReports = (clone $query)->where('status', 'in_process')->count();
            $notDeliveredReports = (clone $query)->where('status', 'not_delivered')->count();
            
            // Obtener últimos 5 reportes con sus items, productos y categorías
            $latestReports = (clone $query)
                ->with(['items.product', 'categories'])
                ->whereHas('items') // Solo reportes que tengan items
                ->latest()
                ->take(5)
                ->get();
            
            // Preparar texto
            $categoryDisplay = is_array($categoryName) ? 'Otros' : $categoryName;
            if ($category === 'ayudas') {
                $categoryDisplay = 'Ayudas Técnicas';
            }
            
            $text = "📦 *Reportes de {$categoryDisplay}*\n";
            $text .= "📍 *Parroquia:* {$parish}\n\n";
            
            $text .= "📊 *Resumen:*\n";
            $text .= "   • Total de reportes: {$totalReports}\n";
            $text .= "   • ✅ Entregados: {$deliveredReports}\n";
            $text .= "   • 🔄 En proceso: {$inProcessReports}\n";
            $text .= "   • ❌ No entregados: {$notDeliveredReports}\n\n";
            
            if ($latestReports->isEmpty()) {
                $text .= "ℹ️ No hay reportes registrados para esta categoría en esta parroquia.";
            } else {
                $text .= "📋 *Últimos 5 reportes:*\n\n";
                
                foreach ($latestReports as $index => $report) {
                    try {
                        $statusEmoji = match($report->status) {
                            'delivered' => '✅',
                            'in_process' => '🔄',
                            'not_delivered' => '❌',
                            default => '❓',
                        };
                        
                        // Obtener productos del reporte con cantidad y unidad (manejando nulls)
                        $productos = $report->items->map(function($item) {
                            if ($item && $item->product) {
                                $cantidad = $item->quantity ?? 0;
                                $unidad = $item->product->unit ?? 'unidades';
                                // Escapar caracteres especiales de Markdown
                                $productName = $this->escapeTelegramMarkdown($item->product->name);
                                return "{$productName} {$cantidad} {$unidad}";
                            }
                            return null;
                        })->filter()->values();
                        
                        $productosText = $productos->count() > 0 ? $productos->implode(', ') : 'Sin productos';
                        $cantidadItems = $report->items->count();
                        
                        // Escapar caracteres especiales en nombres
                        $beneficiaryName = $this->escapeTelegramMarkdown($report->beneficiary_full_name ?? 'Sin nombre');
                        $reportStatus = $this->escapeTelegramMarkdown(ucfirst($report->status));
                        
                        $pdfIcon = $report->pdf_path ? '📄' : '❌';
                        $text .= ($index + 1) . ". {$statusEmoji} *{$report->report_code}* {$pdfIcon}\n";
                        $text .= "   • Productos: {$productosText}\n";
                        $text .= "   • Entregas: {$cantidadItems}\n";
                        $text .= "   • Beneficiario: {$beneficiaryName}\n";
                        $text .= "   • Fecha: " . ($report->delivery_date ? $report->delivery_date->format('d/m/Y') : 'N/A') . "\n";
                        $text .= "   • Estado: {$reportStatus}\n\n";
                    } catch (\Exception $itemError) {
                        // Si hay error con un reporte específico, continuar con el siguiente
                        logger()->error('Error procesando reporte en bot: ' . $itemError->getMessage(), [
                            'report_id' => $report->id ?? 'unknown',
                            'report_code' => $report->report_code ?? 'unknown'
                        ]);
                        continue;
                    }
                }
            }
            
            // Crear botones inline para descargar PDFs de reportes que los tengan
            $inlineKeyboard = [];
            foreach ($latestReports as $index => $report) {
                if ($report->pdf_path && file_exists(storage_path('app/public/' . $report->pdf_path))) {
                    $inlineKeyboard[] = [
                        [
                            'text' => "📄 PDF #{$report->report_code}",
                            'callback_data' => "pdf_{$report->id}"
                        ]
                    ];
                }
            }
            
            $messageParams = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ];
            
            // Agregar teclado inline solo si hay PDFs disponibles
            if (!empty($inlineKeyboard)) {
                $messageParams['reply_markup'] = json_encode([
                    'inline_keyboard' => $inlineKeyboard
                ]);
            }
            
            Telegram::sendMessage($messageParams);
        
        } catch (\Exception $e) {
            logger()->error('Error en showParishReports: ' . $e->getMessage(), [
                'parish' => $parish,
                'category' => $category,
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Escapar caracteres especiales del mensaje de error para evitar problemas de parsing
            $errorMsg = str_replace(['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'], '', $e->getMessage());
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Error al obtener reportes.\n\nPor favor intenta nuevamente o contacta al administrador si el problema persiste.",
            ]);
            
            // Registrar error en logs
            self::logTelegramActivity(
                "Error al consultar reportes de categoría: {$category} en parroquia: {$parish}",
                [
                    'parish' => $parish,
                    'category' => $category,
                    'action' => 'parish_category_reports_error',
                    'error_message' => $e->getMessage(),
                    'error_line' => $e->getLine(),
                ],
                $telegramUser
            );
            return;
        }
        
        // Registrar actividad - SIEMPRE se ejecuta después del try-catch
        self::logTelegramActivity(
            "Consultó reportes de categoría: {$categoryDisplay} en parroquia: {$parish}",
            [
                'parish' => $parish,
                'category' => $categoryDisplay,
                'action' => 'parish_category_reports',
                'total_reports' => $totalReports,
            ],
            $telegramUser
        );
    }
    
    /**
     * Generar URL de gráfico tipo pastel usando QuickChart
     */
    private function generatePieChart($title, $labels, $data, $colors)
    {
        $chart = [
            'type' => 'pie',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => $colors,
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => $title,
                    'fontSize' => 18,
                    'fontColor' => '#333',
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'fontSize' => 14,
                        'fontColor' => '#333',
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => '#fff',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14,
                        ],
                        'formatter' => null // Se mostrará el valor
                    ]
                ]
            ]
        ];
        
        $chartJson = json_encode($chart);
        $chartEncoded = urlencode($chartJson);
        
        return "https://quickchart.io/chart?c={$chartEncoded}&width=500&height=300&backgroundColor=white";
    }
    
    /**
     * Escapar caracteres especiales de Markdown de Telegram
     */
    private function escapeTelegramMarkdown($text)
    {
        // Escapar caracteres especiales de Markdown
        $specialChars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($specialChars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }
        return $text;
    }
    
    /**
     * Enviar PDF de un reporte específico
     */
    private function sendReportPdf($chatId, $reportId, $telegramUser)
    {
        try {
            $report = \App\Models\Report::find($reportId);
            
            if (!$report) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ Reporte no encontrado.",
                ]);
                return;
            }
            
            if (!$report->pdf_path) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ Este reporte no tiene PDF generado.",
                ]);
                return;
            }
            
            $pdfPath = storage_path('app/public/' . $report->pdf_path);
            
            if (!file_exists($pdfPath)) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ El archivo PDF no existe en el servidor.",
                ]);
                
                logger()->error('PDF no encontrado en servidor', [
                    'report_id' => $reportId,
                    'pdf_path' => $report->pdf_path,
                    'full_path' => $pdfPath
                ]);
                return;
            }
            
            // Enviar el documento PDF
            Telegram::sendDocument([
                'chat_id' => $chatId,
                'document' => \Telegram\Bot\FileUpload\InputFile::create($pdfPath, basename($pdfPath)),
                'caption' => "📄 Reporte: {$report->report_code}\n👤 Beneficiario: {$report->beneficiary_full_name}",
            ]);
            
            // Registrar actividad
            self::logTelegramActivity(
                "Descargó PDF del reporte: {$report->report_code}",
                [
                    'report_id' => $report->id,
                    'report_code' => $report->report_code,
                    'action' => 'pdf_download',
                ],
                $telegramUser
            );
            
        } catch (\Exception $e) {
            logger()->error('Error enviando PDF: ' . $e->getMessage(), [
                'report_id' => $reportId,
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Error al enviar el PDF. Por favor intenta nuevamente.",
            ]);
        }
    }
}
