<?php

namespace App\Traits;

use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity as BaseLogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Log;

trait LogsActivity
{
    use BaseLogsActivity;

    /**
     * Registra una actividad genérica
     */
    public static function logActivity(string $description, string $logName = 'default', array $properties = [], $subject = null, $causer = null)
    {
        $log = activity($logName)
            ->withProperties($properties)
            ->causedBy($causer ?? auth()->user());

        if ($subject) {
            $log->performedOn($subject);
        }

        $log->log($description);
    }

    /**
     * Registra una actividad del sistema
     */
    public static function logSystemActivity(string $description, array $properties = [])
    {
        activity('system')
            ->withProperties(array_merge($properties, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]))
            ->causedBy(auth()->user())
            ->log($description);
    }

    /**
     * Registrar actividad específica de Telegram
     */
    public static function logTelegramActivity($description, $properties = [], $telegramUser = null)
    {
        try {
            // Asegurar que properties sea un array
            $properties = $properties ?? [];
            $properties['source'] = 'telegram_bot';
            $properties['timestamp'] = now()->toDateTimeString();
            
            // Inicializar variables
            $systemUser = null;
            $telegramInfo = '';
            
            // Agregar información del usuario de Telegram si está disponible
            if ($telegramUser && is_array($telegramUser)) {
                $properties['telegram_user'] = $telegramUser;
                
                // Buscar usuario del sistema vinculado
                if (isset($telegramUser['id'])) {
                    try {
                        $systemUser = User::where('telegram_chat_id', $telegramUser['id'])->first();
                    } catch (\Exception $e) {
                        Log::error('Error buscando usuario del sistema: ' . $e->getMessage());
                    }
                }
                
                // Agregar información del usuario al description
                $telegramInfo = sprintf(
                    ' [%s | @%s | TG:%s]',
                    $telegramUser['first_name'] ?? 'Usuario',
                    $telegramUser['username'] ?? 'sin_username',
                    $telegramUser['id'] ?? 'desconocido'
                );
            }
            
            // Construir descripción completa
            $fullDescription = $description . $telegramInfo;
            
            // Crear el log de actividad
            $activityLog = activity('telegram')
                ->withProperties($properties);
            
            // Agregar el causante si existe
            if ($systemUser) {
                $activityLog->causedBy($systemUser);
            }
            
            // Guardar el log
            $activityLog->log($fullDescription);
            
            // Log de éxito para debug (puedes comentar esto después)
            Log::info('Actividad de Telegram registrada: ' . $fullDescription, [
                'properties' => $properties,
                'user_id' => $systemUser ? $systemUser->id : null
            ]);
            
        } catch (\Exception $e) {
            // Log del error pero NO lanzar excepción para no interrumpir el flujo
            Log::error('Error al registrar actividad de Telegram: ' . $e->getMessage(), [
                'description' => $description ?? 'sin descripción',
                'properties' => $properties ?? [],
                'telegram_user' => $telegramUser ?? null,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Registra creación de modelo
     */
    public static function logCreated(string $modelName, $model, array $additionalProperties = [])
    {
        activity('model')
            ->performedOn($model)
            ->causedBy(auth()->user())
            ->withProperties(array_merge([
                'model' => $modelName,
                'action' => 'created',
                'attributes' => $model->getAttributes(),
            ], $additionalProperties))
            ->log("Creó {$modelName}");
    }

    /**
     * Registra actualización de modelo
     */
    public static function logUpdated(string $modelName, $model, array $changes, array $additionalProperties = [])
    {
        activity('model')
            ->performedOn($model)
            ->causedBy(auth()->user())
            ->withProperties(array_merge([
                'model' => $modelName,
                'action' => 'updated',
                'old' => $changes['old'] ?? [],
                'new' => $changes['new'] ?? [],
            ], $additionalProperties))
            ->log("Actualizó {$modelName}");
    }

    /**
     * Registra eliminación de modelo
     */
    public static function logDeleted(string $modelName, $model, array $additionalProperties = [])
    {
        activity('model')
            ->performedOn($model)
            ->causedBy(auth()->user())
            ->withProperties(array_merge([
                'model' => $modelName,
                'action' => 'deleted',
                'attributes' => $model->getAttributes(),
            ], $additionalProperties))
            ->log("Eliminó {$modelName}");
    }

    /**
     * Registra una acción de autenticación
     */
    public static function logAuth(string $action, $user = null, array $properties = [])
    {
        activity('auth')
            ->causedBy($user ?? auth()->user())
            ->withProperties(array_merge([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'action' => $action,
            ], $properties))
            ->log($action);
    }

    /**
     * Registra un error
     */
    public static function logError(string $description, \Throwable $exception, array $additionalContext = [])
    {
        activity('error')
            ->causedBy(auth()->user())
            ->withProperties([
                'error_message' => $exception->getMessage(),
                'error_file' => $exception->getFile(),
                'error_line' => $exception->getLine(),
                'stack_trace' => $exception->getTraceAsString(),
                'context' => $additionalContext,
            ])
            ->log($description);
    }

    /**
     * Configuración por defecto del trait de Spatie
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
