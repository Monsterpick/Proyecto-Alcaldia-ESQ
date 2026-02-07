<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\LogsActivity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestTelegramLogging extends Command
{
    use LogsActivity;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test-logging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el sistema de logging de Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('====================================');
        $this->info('PRUEBA DEL SISTEMA DE LOGGING');
        $this->info('====================================');
        
        // 1. Verificar conexión a la base de datos
        $this->info("\n1. Verificando conexión a la base de datos...");
        try {
            DB::connection()->getPdo();
            $this->info('✅ Conexión exitosa');
        } catch (\Exception $e) {
            $this->error('❌ Error de conexión: ' . $e->getMessage());
            return 1;
        }
        
        // 2. Verificar tabla activity_log
        $this->info("\n2. Verificando tabla activity_log...");
        if (DB::getSchemaBuilder()->hasTable('activity_log')) {
            $this->info('✅ Tabla activity_log existe');
            $count = DB::table('activity_log')->count();
            $this->info("   Total de registros: {$count}");
            $telegramCount = DB::table('activity_log')->where('log_name', 'telegram')->count();
            $this->info("   Registros de Telegram: {$telegramCount}");
        } else {
            $this->error('❌ Tabla activity_log NO existe');
            $this->warn('Ejecuta: php artisan migrate');
            return 1;
        }
        
        // 3. Verificar usuarios con Telegram
        $this->info("\n3. Verificando usuarios con telegram_chat_id...");
        $usersWithTelegram = User::whereNotNull('telegram_chat_id')->get();
        if ($usersWithTelegram->count() > 0) {
            $this->info('✅ Usuarios con Telegram: ' . $usersWithTelegram->count());
            foreach ($usersWithTelegram as $user) {
                $this->info("   - {$user->name} (ID: {$user->id}, Chat ID: {$user->telegram_chat_id})");
            }
        } else {
            $this->warn('⚠️ No hay usuarios con telegram_chat_id vinculado');
        }
        
        // 4. Probar crear un log
        $this->info("\n4. Probando crear un log de actividad...");
        try {
            $testUser = [
                'id' => 123456789,
                'username' => 'test_user',
                'first_name' => 'Test',
                'last_name' => 'User'
            ];
            
            self::logTelegramActivity(
                'Prueba de logging desde comando',
                [
                    'test' => true,
                    'command' => 'telegram:test-logging',
                    'timestamp' => now()->toDateTimeString()
                ],
                $testUser
            );
            
            $this->info('✅ Log creado exitosamente');
            
            // Verificar que se guardó
            $lastLog = DB::table('activity_log')
                ->where('log_name', 'telegram')
                ->orderBy('id', 'desc')
                ->first();
                
            if ($lastLog && strpos($lastLog->description, 'Prueba de logging') !== false) {
                $this->info('✅ Log verificado en la base de datos');
                $this->info("   Descripción: {$lastLog->description}");
            } else {
                $this->warn('⚠️ El log se creó pero no se puede verificar');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error al crear log: ' . $e->getMessage());
            $this->error('   Stack trace: ' . $e->getTraceAsString());
        }
        
        // 5. Verificar archivos de log
        $this->info("\n5. Verificando archivos de log...");
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $this->info('✅ Archivo laravel.log existe');
            $size = filesize($logPath);
            $this->info("   Tamaño: " . $this->formatBytes($size));
            
            // Verificar permisos
            if (is_writable($logPath)) {
                $this->info('✅ El archivo es escribible');
            } else {
                $this->error('❌ El archivo NO es escribible');
            }
        } else {
            $this->warn('⚠️ Archivo laravel.log NO existe');
            $this->info('   Creando archivo...');
            
            try {
                Log::info('Archivo de log creado por comando de prueba');
                $this->info('✅ Archivo creado');
            } catch (\Exception $e) {
                $this->error('❌ No se pudo crear el archivo: ' . $e->getMessage());
            }
        }
        
        // 6. Ver últimos 5 logs de Telegram
        $this->info("\n6. Últimos 5 logs de Telegram:");
        $recentLogs = DB::table('activity_log')
            ->where('log_name', 'telegram')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        if ($recentLogs->count() > 0) {
            foreach ($recentLogs as $log) {
                $this->info("   [{$log->created_at}] {$log->description}");
            }
        } else {
            $this->warn('   No hay logs de Telegram');
        }
        
        // 7. Resumen
        $this->info("\n====================================");
        $this->info("RESUMEN:");
        $this->info("====================================");
        
        $checks = [
            'Base de datos' => DB::connection()->getPdo() ? true : false,
            'Tabla activity_log' => DB::getSchemaBuilder()->hasTable('activity_log'),
            'Usuarios con Telegram' => $usersWithTelegram->count() > 0,
            'Archivo de logs' => file_exists($logPath),
            'Logs escribibles' => file_exists($logPath) && is_writable($logPath),
        ];
        
        $allGood = true;
        foreach ($checks as $check => $status) {
            if ($status) {
                $this->info("✅ {$check}");
            } else {
                $this->error("❌ {$check}");
                $allGood = false;
            }
        }
        
        if ($allGood) {
            $this->info("\n✅ ¡TODO ESTÁ FUNCIONANDO CORRECTAMENTE!");
        } else {
            $this->warn("\n⚠️ Hay problemas que necesitan ser corregidos");
        }
        
        return 0;
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
