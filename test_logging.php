<?php
// Script de prueba para verificar el sistema de logging

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Configurar la aplicación
$app->boot();

echo "====================================\n";
echo "PRUEBA DEL SISTEMA DE LOGGING\n";
echo "====================================\n\n";

// 1. Verificar conexión a la base de datos
echo "1. Verificando conexión a la base de datos...\n";
try {
    DB::connection()->getPdo();
    echo "✅ Conexión exitosa\n";
} catch (\Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Verificar tabla activity_log
echo "\n2. Verificando tabla activity_log...\n";
if (DB::getSchemaBuilder()->hasTable('activity_log')) {
    echo "✅ Tabla activity_log existe\n";
    $count = DB::table('activity_log')->count();
    echo "   Total de registros: {$count}\n";
    $telegramCount = DB::table('activity_log')->where('log_name', 'telegram')->count();
    echo "   Registros de Telegram: {$telegramCount}\n";
} else {
    echo "❌ Tabla activity_log NO existe\n";
    echo "   Ejecuta: php artisan migrate\n";
    exit(1);
}

// 3. Verificar usuarios con Telegram
echo "\n3. Verificando usuarios con telegram_chat_id...\n";
$usersWithTelegram = \App\Models\User::whereNotNull('telegram_chat_id')->get();
if ($usersWithTelegram->count() > 0) {
    echo "✅ Usuarios con Telegram: " . $usersWithTelegram->count() . "\n";
    foreach ($usersWithTelegram as $user) {
        echo "   - {$user->name} (ID: {$user->id}, Chat ID: {$user->telegram_chat_id})\n";
    }
} else {
    echo "⚠️ No hay usuarios con telegram_chat_id vinculado\n";
}

// 4. Probar crear un log
echo "\n4. Probando crear un log de actividad...\n";
try {
    // Crear un log de prueba sin usar el trait
    activity('telegram')
        ->withProperties([
            'test' => true,
            'source' => 'telegram_bot',
            'timestamp' => now()->toDateTimeString()
        ])
        ->log('PRUEBA DE LOGGING - Script de verificación');
    
    echo "✅ Log creado exitosamente\n";
    
    // Verificar que se guardó
    $lastLog = DB::table('activity_log')
        ->where('log_name', 'telegram')
        ->orderBy('id', 'desc')
        ->first();
        
    if ($lastLog && strpos($lastLog->description, 'PRUEBA DE LOGGING') !== false) {
        echo "✅ Log verificado en la base de datos\n";
        echo "   ID: {$lastLog->id}\n";
        echo "   Descripción: {$lastLog->description}\n";
        echo "   Creado: {$lastLog->created_at}\n";
    } else {
        echo "⚠️ El log se creó pero no se puede verificar\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error al crear log: " . $e->getMessage() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// 5. Ver últimos 5 logs de Telegram
echo "\n5. Últimos 5 logs de Telegram:\n";
$recentLogs = DB::table('activity_log')
    ->where('log_name', 'telegram')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();
    
if ($recentLogs->count() > 0) {
    foreach ($recentLogs as $log) {
        echo "   [{$log->created_at}] {$log->description}\n";
    }
} else {
    echo "   No hay logs de Telegram\n";
}

// 6. Verificar que el trait funciona
echo "\n6. Probando el trait LogsActivity...\n";
try {
    $testUser = [
        'id' => 123456789,
        'username' => 'test_user',
        'first_name' => 'Test',
        'last_name' => 'User'
    ];
    
    \App\Traits\LogsActivity::logTelegramActivity(
        'Prueba desde script de verificación',
        [
            'test' => true,
            'script' => 'test_logging.php'
        ],
        $testUser
    );
    
    echo "✅ Trait LogsActivity funciona correctamente\n";
} catch (\Exception $e) {
    echo "❌ Error en el trait: " . $e->getMessage() . "\n";
}

// 7. Resumen
echo "\n====================================\n";
echo "RESUMEN:\n";
echo "====================================\n";

$checks = [
    'Base de datos conectada' => true,
    'Tabla activity_log existe' => DB::getSchemaBuilder()->hasTable('activity_log'),
    'Usuarios con Telegram' => $usersWithTelegram->count() > 0,
    'Logs de Telegram' => $telegramCount > 0,
];

$allGood = true;
foreach ($checks as $check => $status) {
    if ($status) {
        echo "✅ {$check}\n";
    } else {
        echo "❌ {$check}\n";
        $allGood = false;
    }
}

if ($allGood) {
    echo "\n✅ ¡TODO ESTÁ FUNCIONANDO CORRECTAMENTE!\n";
    echo "\nSi los logs no aparecen en el bot, el problema puede ser:\n";
    echo "1. El webhook no está configurado correctamente\n";
    echo "2. El bot no está recibiendo los mensajes\n";
    echo "3. Hay un error en el flujo del controlador\n";
} else {
    echo "\n⚠️ Hay problemas que necesitan ser corregidos\n";
}
