<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Exceptions\TelegramSDKException;
use App\Traits\LogsActivity;
use App\Models\User;

class TelegramBotPolling extends Command
{
    use LogsActivity;
    protected $signature = 'telegram:polling';
    protected $description = 'Escuchar mensajes del bot de Telegram en modo polling';

    public function handle()
    {
        $this->info('🤖 Bot de Telegram iniciado...');
        $this->info('📱 Bot: @AlcaldiaES_bot');
        $this->info('Escuchando mensajes... (Presiona Ctrl+C para detener)');
        $this->newLine();

        $offset = 0;

        while (true) {
            try {
                $updates = Telegram::getUpdates([
                    'offset' => $offset,
                    'timeout' => 10,
                    'limit' => 100
                ]);

                foreach ($updates as $update) {
                    $offset = $update->getUpdateId() + 1;
                    
                    try {
                        // Manejar inline queries (búsqueda en tiempo real)
                        if ($inlineQuery = $update->getInlineQuery()) {
                            $this->handleInlineQuery($inlineQuery);
                            continue;
                        }
                        
                        // Manejar chosen inline result (cuando se selecciona un resultado)
                        if ($chosenResult = $update->getChosenInlineResult()) {
                            $from = $chosenResult->getFrom();
                            $resultId = $chosenResult->getResultId();
                            $query = $chosenResult->getQuery();
                            $this->info("✅ {$from->getFirstName()} seleccionó resultado: {$resultId} (búsqueda: '{$query}')");
                            continue;
                        }
                        
                        // Manejar callback queries (clics en botones)
                        if ($callbackQuery = $update->getCallbackQuery()) {
                            $from = $callbackQuery->getFrom();
                            $data = $callbackQuery->getData();
                            
                            // Obtener chatId y messageId de forma segura
                            $message = $callbackQuery->getMessage();
                            $chatId = $message ? $message->getChat()->getId() : $from->getId();
                            $messageId = $message ? $message->getMessageId() : null;
                            
                            $this->line("🔘 Botón presionado por {$from->getFirstName()}: {$data}");
                            
                            // Responder al callback para quitar el loading
                            Telegram::answerCallbackQuery([
                                'callback_query_id' => $callbackQuery->getId(),
                            ]);
                            
                            // Procesar callbacks de descargar PDF
                            if (strpos($data, 'download_pdf_') === 0) {
                                $this->info("📄 Callback de descarga de PDF detectado: {$data}");
                                $this->handlePdfDownload($chatId, $data, $from);
                                continue;
                            }
                            
                            // Procesar callbacks de beneficiario (ver reportes)
                            if (strpos($data, 'beneficiary_') === 0) {
                                $this->info("👤 Callback de beneficiario detectado: {$data}");
                                $this->handleBeneficiaryReports($chatId, $messageId, $data, $from);
                                continue;
                            }
                            
                            // Procesar callbacks de paginación de reportes
                            if (strpos($data, 'page_') === 0) {
                                $this->info("📄 Callback de paginación detectado: {$data}");
                                $this->handleReportPagination($chatId, $messageId, $data, $from);
                                continue;
                            }
                            
                            // Procesar callbacks de parroquia
                            if (strpos($data, 'parish_') === 0) {
                                $this->info("🏘️  Callback de parroquia detectado: {$data}");
                                $this->handleParishCallback($chatId, $messageId, $data, $from);
                                continue;
                            }
                            
                            // Procesar según el callback_data
                            if (strpos($data, 'cmd_') === 0) {
                                $commandName = str_replace('cmd_', '', $data);
                                
                                // Simular un comando
                                $telegram = Telegram::bot();
                                $commands = $telegram->getCommands();
                                
                                foreach ($commands as $command) {
                                    if ($command->getName() === $commandName) {
                                        try {
                                            // Crear un update simulado para el comando
                                            $entities = [];
                                            $command->make($telegram, $update, $entities);
                                            $this->info("✅ Comando '{$commandName}' ejecutado desde botón");
                                            break;
                                        } catch (\Exception $e) {
                                            $this->error("❌ Error: " . $e->getMessage());
                                        }
                                    }
                                }
                            }
                        }
                        
                        // Manejar mensajes normales
                        $message = $update->getMessage();
                        
                        if ($message) {
                            $from = $message->getFrom();
                            $text = $message->getText() ?? '[sin texto]';
                            $chatId = $message->getChat()->getId();

                            $this->line("📩 De: {$from->getFirstName()} | Chat ID: {$chatId} | Mensaje: {$text}");

                            // Detectar botones de parroquia PRIMERO (solo botones, no texto con "Parroquia")
                            if (strpos($text, '📍 Parroquia') === 0) {
                                // Extraer el nombre de la parroquia
                                $parishName = trim(str_replace(['📍', 'Parroquia'], '', $text));
                                
                                $this->info("🏘️  Botón de parroquia presionado: {$parishName}");
                                
                                // Obtener datos del usuario de Telegram
                                $telegramUser = [
                                    'id' => $from->getId(),
                                    'username' => $from->getUsername(),
                                    'first_name' => $from->getFirstName(),
                                    'last_name' => $from->getLastName()
                                ];
                                
                                // Manejar botón de parroquia
                                $this->handleParishButton($chatId, $parishName, $from, $telegramUser);
                                continue;
                            }
                            
                            // Detectar otros botones del teclado permanente
                            $commandMap = [
                                '🔍 Buscar Beneficiario' => 'search',
                                '📊 Estadísticas' => 'stats',
                                '❓ Ayuda' => 'help',
                            ];
                            
                            if (isset($commandMap[$text])) {
                                // Verificar autenticación para estos botones también
                                $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();
                                
                                // Obtener datos del usuario de Telegram
                                $telegramUser = [
                                    'id' => $from->getId(),
                                    'username' => $from->getUsername(),
                                    'first_name' => $from->getFirstName(),
                                    'last_name' => $from->getLastName()
                                ];
                                
                                if (!$user) {
                                    $this->warn("⚠️  Usuario no autenticado intentó usar: {$text}");
                                    
                                    // Registrar intento sin autenticación
                                    self::logTelegramActivity(
                                        "Intento de acceso sin autenticación: {$text}",
                                        [
                                            'button_text' => $text,
                                            'action' => 'button_unauthorized'
                                        ],
                                        $telegramUser
                                    );
                                    
                                    Telegram::sendMessage([
                                        'chat_id' => $chatId,
                                        'text' => "🔐 *Debes iniciar sesión primero*\n\nUsa /login para autenticarte y acceder a las funciones del bot.",
                                        'parse_mode' => 'Markdown',
                                    ]);
                                    
                                    continue;
                                }
                                
                                $commandName = $commandMap[$text];
                                
                                // Registrar comando ejecutado vía botón
                                self::logTelegramActivity(
                                    "Comando ejecutado: {$commandName}",
                                    [
                                        'command' => $commandName,
                                        'button_text' => $text,
                                        'action' => 'keyboard_button'
                                    ],
                                    $telegramUser
                                );
                                
                                $text = "/{$commandName}";
                                $this->info("🔘 Botón de teclado presionado, ejecutando: {$text}");
                            }

                            // Si es un comando, procesarlo
                            if (strpos($text, '/') === 0) {
                                $this->info("⚙️  Procesando comando...");
                                
                                // Obtener el comando sin el /
                                $commandText = ltrim($text, '/');
                                $parts = explode(' ', $commandText);
                                $commandName = $parts[0];
                                
                                // Obtener datos del usuario de Telegram
                                $telegramUser = [
                                    'id' => $from->getId(),
                                    'username' => $from->getUsername(),
                                    'first_name' => $from->getFirstName(),
                                    'last_name' => $from->getLastName()
                                ];
                                
                                // Buscar y ejecutar el comando
                                $telegram = Telegram::bot();
                                $commands = $telegram->getCommands();
                                
                                $executed = false;
                                foreach ($commands as $command) {
                                    if ($command->getName() === $commandName) {
                                        try {
                                            // Obtener las entidades del mensaje (necesario para el comando)
                                            $entities = $message->get('entities', []);
                                            
                                            // Convertir a array si es una Collection
                                            if ($entities instanceof \Illuminate\Support\Collection) {
                                                $entities = $entities->toArray();
                                            }
                                            
                                            $command->make($telegram, $update, $entities);
                                            $this->info("✅ Comando '{$commandName}' ejecutado");
                                            
                                            // Registrar comando ejecutado
                                            self::logTelegramActivity(
                                                "Comando ejecutado: {$commandName}",
                                                [
                                                    'command' => $commandName,
                                                    'full_text' => $text,
                                                    'action' => 'text_command'
                                                ],
                                                $telegramUser
                                            );
                                            
                                            $executed = true;
                                            break;
                                        } catch (\Exception $e) {
                                            $this->error("❌ Error ejecutando comando: " . $e->getMessage());
                                        }
                                    }
                                }
                                
                                if (!$executed) {
                                    $this->warn("⚠️  Comando '{$commandName}' no encontrado");
                                    
                                    // Enviar mensaje de comando no encontrado
                                    Telegram::sendMessage([
                                        'chat_id' => $chatId,
                                        'text' => "❌ Comando '{$commandName}' no reconocido. Usa /help para ver comandos disponibles."
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $this->error("❌ Error procesando update: " . $e->getMessage());
                    }
                }

                // Si no hay updates, mostrar que estamos escuchando
                if (count($updates) === 0) {
                    $this->comment("⏳ Esperando mensajes...");
                }

                // Pequeña pausa
                usleep(500000); // 0.5 segundos

            } catch (TelegramSDKException $e) {
                $this->error("❌ Error de Telegram: " . $e->getMessage());
                $this->info("⏳ Reintentando en 5 segundos...");
                sleep(5);
            } catch (\Exception $e) {
                $this->error("❌ Error inesperado: " . $e->getMessage());
                $this->info("⏳ Reintentando en 5 segundos...");
                sleep(5);
            }
        }
    }
    
    /**
     * Manejar inline queries (búsqueda en tiempo real)
     */
    protected function handleInlineQuery($inlineQuery)
    {
        $from = $inlineQuery->getFrom();
        $query = $inlineQuery->getQuery();
        $queryId = $inlineQuery->getId();
        
        $this->line("🔍 Búsqueda inline de {$from->getFirstName()}: '{$query}'");
        
        // Si no hay query, no buscar
        if (empty(trim($query))) {
            Telegram::answerInlineQuery([
                'inline_query_id' => $queryId,
                'results' => [],
                'cache_time' => 0,
            ]);
            return;
        }
        
        // Buscar beneficiarios por nombre o cédula
        $beneficiaries = \App\Models\Beneficiary::where(function($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%")
              ->orWhere('cedula', 'LIKE', "%{$query}%");
        })
        ->take(10)
        ->get();
        
        $results = [];
        
        // Si no hay resultados, enviar mensaje informativo
        if ($beneficiaries->count() === 0) {
            $results[] = [
                'type' => 'article',
                'id' => 'no_results',
                'title' => '❌ No se encontraron beneficiarios',
                'description' => "No hay coincidencias para: {$query}",
                'input_message_content' => [
                    'message_text' => "❌ *No se encontraron beneficiarios*\n\nNo hay coincidencias para la búsqueda: *{$query}*\n\nIntenta buscar por:\n• Nombre\n• Apellido\n• Cédula",
                    'parse_mode' => 'Markdown',
                ],
            ];
        }
        
        foreach ($beneficiaries as $beneficiary) {
            // Buscar reportes del beneficiario (solo por cédula sin el tipo de documento)
            $reports = \App\Models\Report::where('beneficiary_cedula', $beneficiary->cedula)
                ->orderBy('delivery_date', 'desc')
                ->get();
            
            // Construir mensaje detallado (CORTO)
            $text = "👤 *INFORMACIÓN DEL BENEFICIARIO*\n\n";
            
            // Datos personales
            $text .= "📋 *Datos Personales:*\n";
            $text .= "• *Nombre:* {$beneficiary->full_name}\n";
            $text .= "• *Cédula:* {$beneficiary->full_cedula}\n";
            $text .= "• *Fecha de nacimiento:* " . ($beneficiary->birth_date ? $beneficiary->birth_date->format('d/m/Y') : 'N/A') . "\n";
            
            if ($beneficiary->birth_date) {
                $age = $beneficiary->birth_date->age;
                $text .= "• *Edad:* {$age} años\n";
            }
            
            $text .= "• *Estado:* " . ($beneficiary->status === 'active' ? '✅ Activo' : '❌ Inactivo') . "\n\n";
            
            // Datos de contacto
            $text .= "📞 *Contacto:*\n";
            $text .= "• *Teléfono:* " . ($beneficiary->phone ?: 'N/A') . "\n";
            $text .= "• *Email:* " . ($beneficiary->email ?: 'N/A') . "\n\n";
            
            // Ubicación
            $text .= "📍 *Ubicación:*\n";
            $text .= "• *Estado:* {$beneficiary->state}\n";
            $text .= "• *Municipio:* {$beneficiary->municipality}\n";
            $text .= "• *Parroquia:* " . ($beneficiary->parish ?: 'N/A') . "\n";
            
            if ($beneficiary->address) {
                $text .= "• *Dirección:* {$beneficiary->address}\n";
            }
            
            // Información de reportes
            if ($reports->count() > 0) {
                $text .= "\n📊 *Total de reportes:* {$reports->count()}\n";
            } else {
                $text .= "\n📊 *Total de reportes:* 0\n";
            }
            
            $text .= "\n🕐 Consultado: " . now()->format('d/m/Y H:i');
            
            // Crear el resultado inline
            $status = $beneficiary->status === 'active' ? '✅' : '❌';
            $description = "{$beneficiary->full_cedula} | {$beneficiary->municipality}, {$beneficiary->state}";
            
            // Crear botón para ver todos los reportes
            $keyboard = null;
            if ($reports->count() > 0) {
                $keyboard = [
                    'inline_keyboard' => [
                        [
                            [
                                'text' => "📋 Ver Todos los Reportes ({$reports->count()})",
                                'callback_data' => "beneficiary_{$beneficiary->id}_0"
                            ]
                        ]
                    ]
                ];
            }
            
            $result = [
                'type' => 'article',
                'id' => (string)$beneficiary->id,
                'title' => "{$status} {$beneficiary->full_name}",
                'description' => $description,
                'input_message_content' => [
                    'message_text' => $text,
                    'parse_mode' => 'Markdown',
                ],
            ];
            
            if ($keyboard) {
                $result['reply_markup'] = $keyboard;
            }
            
            $results[] = $result;
        }
        
        // Enviar resultados
        try {
            Telegram::answerInlineQuery([
                'inline_query_id' => $queryId,
                'results' => json_encode($results),
                'cache_time' => 30,
            ]);
            
            $this->info("✅ Enviados " . count($results) . " resultados");
            
            // Registrar búsqueda
            if ($beneficiaries->count() > 0) {
                $telegramUser = [
                    'id' => $from->getId(),
                    'username' => $from->getUsername(),
                    'first_name' => $from->getFirstName(),
                    'last_name' => $from->getLastName()
                ];
                
                self::logTelegramActivity(
                    "Buscó beneficiarios: '{$query}' ({$beneficiaries->count()} resultados)",
                    [
                        'query' => $query,
                        'results_count' => $beneficiaries->count(),
                        'action' => 'inline_search_beneficiaries'
                    ],
                    $telegramUser
                );
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error enviando resultados: " . $e->getMessage());
        }
    }
    
    /**
     * Manejar botón de parroquia
     */
    protected function handleParishButton($chatId, $parishName, $from, $telegramUser = null)
    {
        // Si no se pasó telegramUser, crearlo
        if (!$telegramUser) {
            $telegramUser = [
                'id' => $from->getId(),
                'username' => $from->getUsername(),
                'first_name' => $from->getFirstName(),
                'last_name' => $from->getLastName()
            ];
        }
        
        // Verificar autenticación
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();
        
        if (!$user) {
            $this->warn("⚠️  Usuario no autenticado intentó acceder a parroquia");
            
            // Registrar intento sin autenticación
            self::logTelegramActivity(
                "Intento de acceso a parroquia sin autenticación: {$parishName}",
                [
                    'parish' => $parishName,
                    'action' => 'parish_unauthorized'
                ],
                $telegramUser
            );
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "🔐 *Debes iniciar sesión primero*\n\nUsa /login para autenticarte y acceder a las funciones del bot.",
                'parse_mode' => 'Markdown',
            ]);
            
            return;
        }
        
        $this->info("✅ Mostrando menú de parroquia: {$parishName}");
        
        // Registrar acceso a parroquia
        self::logTelegramActivity(
            "Accedió a parroquia: {$parishName}",
            [
                'parish' => $parishName,
                'action' => 'parish_button',
                'user_id' => $user->id
            ],
            $telegramUser
        );
        
        $text = "📍 *Bienvenido a la Parroquia {$parishName}*\n\n";
        $text .= "Presione el número correspondiente para ver los reportes de la categoría que desea:\n\n";
        $text .= "1️⃣ - Medicamentos\n";
        $text .= "2️⃣ - Ayudas Técnicas\n";
        $text .= "3️⃣ - Otros (Alimentos, Educación, Vivienda, Higiene)\n";
        $text .= "4️⃣ - Estadísticas de la Parroquia";
        
        // Convertir nombre de parroquia a formato sin espacios para callback_data
        $parishSlug = str_replace(' ', '_', $parishName);
        
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
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $inlineKeyboard,
                ]),
            ]);
            
            $this->info("✅ Menú enviado exitosamente");
        } catch (\Exception $e) {
            $this->error("❌ Error enviando menú: " . $e->getMessage());
        }
    }
    
    /**
     * Manejar callback de parroquia
     */
    protected function handleParishCallback($chatId, $messageId, $callbackData, $from)
    {
        $this->info("🔍 Procesando callback: {$callbackData}");
        
        // Crear datos del usuario de Telegram
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName()
        ];
        
        // Parsear callback: parish_{ParishName}_cat_{category}_page_{N} o parish_{ParishName}_stats
        // El _page_{N} es opcional
        preg_match('/parish_(.+?)_(cat_(.+?)(?:_page_\d+)?|stats)$/', $callbackData, $matches);
        
        if (!$matches) {
            $this->error("❌ Callback data inválido");
            return;
        }
        
        $parish = str_replace('_', ' ', $matches[1]);
        $isStats = isset($matches[2]) && $matches[2] === 'stats';
        
        if ($isStats) {
            // Mostrar estadísticas de la parroquia
            $this->info("📊 Mostrando estadísticas de: {$parish}");
            $this->showParishStats($chatId, $parish, $telegramUser);
        } else {
            // Mostrar reportes por categoría
            $category = $matches[3];
            $this->info("📦 Mostrando reportes de {$category} en {$parish}");
            $this->showParishReports($chatId, $messageId, $parish, $category, $callbackData, $telegramUser);
        }
    }
    
    /**
     * Mostrar estadísticas de una parroquia
     */
    protected function showParishStats($chatId, $parish, $telegramUser = null)
    {
        try {
            // Obtener estadísticas de beneficiarios
            $totalBeneficiaries = \App\Models\Beneficiary::whereHas('parroquia', function($q) use ($parish) {
                $q->where('parroquia', $parish);
            })->count();
            
            $activeBeneficiaries = \App\Models\Beneficiary::whereHas('parroquia', function($q) use ($parish) {
                $q->where('parroquia', $parish);
            })->where('status', 'active')->count();
            
            $inactiveBeneficiaries = \App\Models\Beneficiary::whereHas('parroquia', function($q) use ($parish) {
                $q->where('parroquia', $parish);
            })->where('status', 'inactive')->count();
            
            // Obtener estadísticas de reportes
            $totalReports = \App\Models\Report::where('parish', $parish)->count();
            $deliveredReports = \App\Models\Report::where('parish', $parish)->where('status', 'delivered')->count();
            $inProcessReports = \App\Models\Report::where('parish', $parish)->where('status', 'in_process')->count();
            $notDeliveredReports = \App\Models\Report::where('parish', $parish)->where('status', 'not_delivered')->count();
            
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
            
            $text .= "🕐 Actualizado: " . now()->format('d/m/Y H:i');
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
            
            $this->info("✅ Estadísticas enviadas");
            
            // Generar y enviar gráficos
            if ($totalBeneficiaries > 0) {
                $beneficiariesChart = $this->generatePieChart(
                    "Beneficiarios - {$parish}",
                    ['Activos', 'Inactivos'],
                    [$activeBeneficiaries, $inactiveBeneficiaries],
                    ['#10b981', '#ef4444']
                );
                
                Telegram::sendPhoto([
                    'chat_id' => $chatId,
                    'photo' => \Telegram\Bot\FileUpload\InputFile::create($beneficiariesChart),
                    'caption' => "📊 Gráfico de Beneficiarios - {$parish}",
                ]);
                
                $this->info("✅ Gráfico de beneficiarios enviado");
            }
            
            if ($totalReports > 0) {
                $reportsChart = $this->generatePieChart(
                    "Reportes - {$parish}",
                    ['Entregados', 'En proceso', 'No entregados'],
                    [$deliveredReports, $inProcessReports, $notDeliveredReports],
                    ['#10b981', '#f59e0b', '#ef4444']
                );
                
                Telegram::sendPhoto([
                    'chat_id' => $chatId,
                    'photo' => \Telegram\Bot\FileUpload\InputFile::create($reportsChart),
                    'caption' => "📦 Gráfico de Reportes - {$parish}",
                ]);
                
                $this->info("✅ Gráfico de reportes enviado");
            }
            
            // Registrar actividad
            self::logTelegramActivity(
                "Consultó estadísticas de parroquia: {$parish}",
                [
                    'parish' => $parish,
                    'action' => 'parish_stats',
                    'stats' => [
                        'beneficiaries' => $totalBeneficiaries ?? 0,
                        'reports' => $totalReports ?? 0
                    ]
                ],
                $telegramUser
            );
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            // Registrar error
            self::logTelegramActivity(
                "Error al consultar estadísticas de parroquia: {$parish}",
                [
                    'parish' => $parish,
                    'action' => 'parish_stats_error',
                    'error' => $e->getMessage()
                ],
                $telegramUser
            );
        }
    }
    
    /**
     * Mostrar reportes por categoría de una parroquia
     */
    protected function showParishReports($chatId, $messageId, $parish, $category, $callbackData, $telegramUser = null)
    {
        try {
            // Crear parishSlug para los botones de navegación
            $parishSlug = str_replace(' ', '_', $parish);
            
            // Mapear categorías
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
                ]);
                return;
            }
            
            // Extraer página del callback (si existe)
            // Formato: parish_ParishName_cat_category_page_N
            $page = 0;
            if (preg_match('/page_(\d+)$/', $callbackData, $matches)) {
                $page = (int)$matches[1];
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
            
            // Obtener TODOS los reportes
            $allReports = (clone $query)
                ->with(['items.product', 'categories'])
                ->whereHas('items') // Solo reportes que tengan items
                ->latest()
                ->get();
            
            // Configuración de paginación
            $perPage = 4; // 4 reportes por página
            $totalPages = ceil($totalReports / $perPage);
            $page = max(0, min($page, $totalPages - 1));
            
            // Obtener reportes de la página actual y reindexar
            $latestReports = $allReports->slice($page * $perPage, $perPage)->values();
            
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
            
            if ($totalPages > 0) {
                $text .= "📄 Página " . ($page + 1) . " de {$totalPages}\n\n";
            }
            
            if ($latestReports->isEmpty()) {
                $text .= "ℹ️ No hay reportes registrados para esta categoría en esta parroquia.";
            } else {
                $text .= "📋 *Reportes:*\n\n";
                
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
                                return "{$item->product->name} {$cantidad} {$unidad}";
                            }
                            return null;
                        })->filter()->values();
                        
                        $productosText = $productos->count() > 0 ? $productos->implode(', ') : 'Sin productos';
                        $cantidadItems = $report->items->count();
                        
                        // Número correlativo del reporte en la lista completa
                        $reportNumber = ($page * $perPage) + $index + 1;
                        
                        $text .= "{$reportNumber}. {$statusEmoji} *{$report->report_code}*\n";
                        $text .= "   • Productos: {$productosText}\n";
                        $text .= "   • Entregas: {$cantidadItems}\n";
                        $text .= "   • Beneficiario: {$report->beneficiary_full_name}\n";
                        $text .= "   • Fecha: " . ($report->delivery_date ? $report->delivery_date->format('d/m/Y') : 'N/A') . "\n\n";
                    } catch (\Exception $itemError) {
                        // Si hay error con un reporte específico, continuar con el siguiente
                        $this->error('Error procesando reporte: ' . $itemError->getMessage());
                        continue;
                    }
                }
            }
            
            // Crear botones inline para descargar PDFs y navegación
            $buttons = [];
            
            // Botones de PDF (2 por fila)
            $pdfButtons = [];
            $buttonRow = [];
            
            foreach ($latestReports as $index => $report) {
                $reportNumber = ($page * $perPage) + $index + 1;
                $buttonRow[] = [
                    'text' => "📄 #{$reportNumber}",
                    'callback_data' => "download_pdf_{$report->id}"
                ];
                
                // Agregar 2 botones por fila
                if (count($buttonRow) == 2) {
                    $pdfButtons[] = $buttonRow;
                    $buttonRow = [];
                }
            }
            
            if (count($buttonRow) > 0) {
                $pdfButtons[] = $buttonRow;
            }
            
            $buttons = array_merge($buttons, $pdfButtons);
            
            // Botones de navegación (solo si hay más de una página)
            if ($totalPages > 1) {
                $navButtons = [];
                
                // Botón anterior
                if ($page > 0) {
                    $navButtons[] = [
                        'text' => '⬅️ Anterior',
                        'callback_data' => "parish_{$parishSlug}_cat_{$category}_page_" . ($page - 1)
                    ];
                }
                
                // Indicador de página (cambié el ícono de 📄 a 📑)
                $navButtons[] = [
                    'text' => "📑 " . ($page + 1) . "/{$totalPages}",
                    'callback_data' => "noop"
                ];
                
                // Botón siguiente
                if ($page < $totalPages - 1) {
                    $navButtons[] = [
                        'text' => 'Siguiente ➡️',
                        'callback_data' => "parish_{$parishSlug}_cat_{$category}_page_" . ($page + 1)
                    ];
                }
                
                $buttons[] = $navButtons;
            }
            
            // Enviar o editar mensaje
            if ($messageId) {
                // Editar mensaje existente (navegación)
                Telegram::editMessageText([
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'text' => $text,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ])
                ]);
            } else {
                // Enviar nuevo mensaje
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $text,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ])
                ]);
            }
            
            $this->info("✅ Reportes enviados");
            
            // Registrar actividad de consulta de reportes
            $categoryDisplay = is_array($categoryName) ? 'Otros' : $categoryName;
            if ($category === 'ayudas') {
                $categoryDisplay = 'Ayudas Técnicas';
            }
            
            self::logTelegramActivity(
                "Consultó reportes de categoría: {$categoryDisplay} en parroquia: {$parish} (Página " . ($page + 1) . ")",
                [
                    'parish' => $parish,
                    'category' => $categoryDisplay,
                    'action' => 'parish_category_reports',
                    'total_reports' => $totalReports ?? 0,
                    'page' => $page + 1,
                    'total_pages' => $totalPages
                ],
                $telegramUser
            );
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            // Registrar error
            self::logTelegramActivity(
                "Error al consultar reportes de categoría: {$category} en parroquia: {$parish}",
                [
                    'parish' => $parish,
                    'category' => $category,
                    'action' => 'parish_category_reports_error',
                    'error' => $e->getMessage()
                ],
                $telegramUser
            );
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Error al obtener reportes: " . $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Manejar descarga de PDF
     */
    protected function handlePdfDownload($chatId, $callbackData, $from)
    {
        try {
            // Extraer el ID del reporte del callback_data
            $reportId = str_replace('download_pdf_', '', $callbackData);
            
            $this->info("📄 Buscando reporte ID: {$reportId}");
            
            // Obtener el reporte
            $report = \App\Models\Report::with(['items.product', 'categories', 'user'])->findOrFail($reportId);
            
            // Usar el servicio de PDF
            $pdfService = app(\App\Services\ReportPdfService::class);
            
            // Verificar si el PDF existe, si no, generarlo
            if (!$pdfService->pdfExists($report)) {
                $this->info("📄 Generando PDF para reporte {$report->report_code}...");
                $pdfService->generatePdf($report);
            }
            
            // Obtener la ruta del PDF
            $pdfPath = $pdfService->getPdfPath($report);
            
            if (!$pdfPath || !file_exists($pdfPath)) {
                throw new \Exception('El archivo PDF no existe');
            }
            
            $this->info("📄 Enviando PDF: {$pdfPath}");
            
            // Enviar el PDF como documento
            Telegram::sendDocument([
                'chat_id' => $chatId,
                'document' => \Telegram\Bot\FileUpload\InputFile::create($pdfPath),
                'caption' => "📄 *Reporte:* {$report->report_code}\n" .
                            "📅 *Fecha:* " . ($report->delivery_date ? $report->delivery_date->format('d/m/Y') : 'N/A') . "\n" .
                            "👤 *Beneficiario:* {$report->beneficiary_full_name}\n" .
                            "📍 *Parroquia:* {$report->parish}",
                'parse_mode' => 'Markdown'
            ]);
            
            $this->info("✅ PDF enviado exitosamente");
            
            // Crear datos del usuario de Telegram
            $telegramUser = [
                'id' => $from->getId(),
                'username' => $from->getUsername(),
                'first_name' => $from->getFirstName(),
                'last_name' => $from->getLastName()
            ];
            
            // Registrar actividad
            self::logTelegramActivity(
                "Descargó PDF del reporte: {$report->report_code}",
                [
                    'report_id' => $report->id,
                    'report_code' => $report->report_code,
                    'parish' => $report->parish,
                    'action' => 'download_report_pdf'
                ],
                $telegramUser
            );
            
        } catch (\Exception $e) {
            $this->error("❌ Error descargando PDF: " . $e->getMessage());
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ *Error al descargar el PDF*\n\n" .
                         "No se pudo generar o enviar el PDF del reporte.\n" .
                         "Error: " . $e->getMessage(),
                'parse_mode' => 'Markdown'
            ]);
        }
    }
    
    /**
     * Generar URL de gráfico tipo pastel usando QuickChart
     */
    protected function generatePieChart($title, $labels, $data, $colors)
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
     * Manejar reportes de beneficiario con paginación
     */
    protected function handleBeneficiaryReports($chatId, $messageId, $callbackData, $from)
    {
        try {
            // Extraer ID del beneficiario del callback
            // Formato: beneficiary_{id}_{page}
            $parts = explode('_', $callbackData);
            $beneficiaryId = $parts[1] ?? null;
            $page = isset($parts[2]) ? (int)$parts[2] : 0;
            
            if (!$beneficiaryId) {
                throw new \Exception('ID de beneficiario no válido');
            }
            
            // Obtener beneficiario
            $beneficiary = \App\Models\Beneficiary::find($beneficiaryId);
            
            if (!$beneficiary) {
                throw new \Exception('Beneficiario no encontrado');
            }
            
            // Obtener todos los reportes del beneficiario
            $allReports = \App\Models\Report::where('beneficiary_cedula', $beneficiary->cedula)
                ->with(['items.product.category', 'categories'])
                ->orderBy('delivery_date', 'desc')
                ->get();
            
            $totalReports = $allReports->count();
            
            if ($totalReports === 0) {
                // Si no hay messageId, enviar nuevo mensaje; si no, editar
                if (!$messageId) {
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => "👤 *{$beneficiary->full_name}*\n\n❌ No hay reportes registrados para este beneficiario.",
                        'parse_mode' => 'Markdown'
                    ]);
                } else {
                    Telegram::editMessageText([
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        'text' => "👤 *{$beneficiary->full_name}*\n\n❌ No hay reportes registrados para este beneficiario.",
                        'parse_mode' => 'Markdown'
                    ]);
                }
                return;
            }
            
            // Configuración de paginación
            $perPage = 4; // 4 reportes por página
            $totalPages = ceil($totalReports / $perPage);
            $page = max(0, min($page, $totalPages - 1)); // Asegurar que la página esté en rango
            
            // Obtener reportes de la página actual y reindexar
            $reports = $allReports->slice($page * $perPage, $perPage)->values();
            
            // Construir mensaje
            $text = "👤 *REPORTES DE: {$beneficiary->full_name}*\n";
            $text .= "📋 Cédula: {$beneficiary->full_cedula}\n";
            $text .= "📍 {$beneficiary->parish}, {$beneficiary->municipality}\n\n";
            $text .= "📊 Total de reportes: *{$totalReports}*\n";
            $text .= "📄 Página " . ($page + 1) . " de {$totalPages}\n\n";
            $text .= "━━━━━━━━━━━━━━━━━━\n\n";
            
            foreach ($reports as $index => $report) {
                $statusIcon = match($report->status) {
                    'delivered' => '✅',
                    'in_process' => '🔄',
                    'not_delivered' => '❌',
                    default => '❓'
                };
                
                $statusText = match($report->status) {
                    'delivered' => 'Entregado',
                    'in_process' => 'En proceso',
                    'not_delivered' => 'No entregado',
                    default => 'Desconocido'
                };
                
                $text .= "{$statusIcon} *{$report->report_code}*\n";
                $text .= "📅 Fecha: " . $report->delivery_date->format('d/m/Y') . "\n";
                $text .= "📊 Estado: {$statusText}\n";
                
                // Mostrar productos
                if ($report->items->count() > 0) {
                    $productNames = $report->items->map(function($item) {
                        return "{$item->product->name} ({$item->quantity})";
                    })->take(2)->implode(', ');
                    
                    $text .= "📦 Productos: {$productNames}";
                    if ($report->items->count() > 2) {
                        $more = $report->items->count() - 2;
                        $text .= " y {$more} más";
                    }
                    $text .= "\n";
                }
                
                $text .= "\n";
            }
            
            // Crear botones de paginación y descarga
            $buttons = [];
            
            // Crear botones de PDF (2 por fila)
            $pdfButtons = [];
            $row = [];
            foreach ($reports as $index => $report) {
                $reportNumber = ($page * $perPage) + $index + 1;
                $row[] = [
                    'text' => "📄 #{$reportNumber}",
                    'callback_data' => "download_pdf_{$report->id}"
                ];
                
                if (count($row) == 2) {
                    $pdfButtons[] = $row;
                    $row = [];
                }
            }
            if (count($row) > 0) {
                $pdfButtons[] = $row;
            }
            
            // Agregar botones de PDF
            $buttons = array_merge($buttons, $pdfButtons);
            
            // Botones de navegación (solo si hay más de una página)
            if ($totalPages > 1) {
                $navButtons = [];
                
                // Botón anterior
                if ($page > 0) {
                    $navButtons[] = [
                        'text' => '⬅️ Anterior',
                        'callback_data' => "beneficiary_{$beneficiaryId}_" . ($page - 1)
                    ];
                }
                
                // Indicador de página (cambié el ícono de 📄 a 📑)
                $navButtons[] = [
                    'text' => "📑 " . ($page + 1) . "/{$totalPages}",
                    'callback_data' => "noop"
                ];
                
                // Botón siguiente
                if ($page < $totalPages - 1) {
                    $navButtons[] = [
                        'text' => 'Siguiente ➡️',
                        'callback_data' => "beneficiary_{$beneficiaryId}_" . ($page + 1)
                    ];
                }
                
                $buttons[] = $navButtons;
            }
            
            // Enviar o editar el mensaje con los reportes
            if (!$messageId) {
                // Si no hay messageId (viene de inline query), enviar nuevo mensaje
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $text,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ])
                ]);
            } else {
                // Si hay messageId, editar el mensaje existente
                Telegram::editMessageText([
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'text' => $text,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ])
                ]);
            }
            
            // Registrar actividad
            $telegramUser = [
                'id' => $from->getId(),
                'username' => $from->getUsername(),
                'first_name' => $from->getFirstName(),
                'last_name' => $from->getLastName()
            ];
            
            self::logTelegramActivity(
                "Consultó reportes del beneficiario: {$beneficiary->full_name} (Página " . ($page + 1) . ")",
                [
                    'beneficiary_id' => $beneficiary->id,
                    'beneficiary_name' => $beneficiary->full_name,
                    'total_reports' => $totalReports,
                    'page' => $page + 1,
                    'action' => 'view_beneficiary_reports'
                ],
                $telegramUser
            );
            
        } catch (\Exception $e) {
            $this->error("❌ Error mostrando reportes de beneficiario: " . $e->getMessage());
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ *Error*\n\nNo se pudieron cargar los reportes del beneficiario.\nError: " . $e->getMessage(),
                'parse_mode' => 'Markdown'
            ]);
        }
    }
    
    /**
     * Alias para handleBeneficiaryReports (para paginación)
     */
    protected function handleReportPagination($chatId, $messageId, $callbackData, $from)
    {
        // Convertir page_beneficiary_id_page a beneficiary_id_page
        $callbackData = str_replace('page_', '', $callbackData);
        $this->handleBeneficiaryReports($chatId, $messageId, $callbackData, $from);
    }
}
