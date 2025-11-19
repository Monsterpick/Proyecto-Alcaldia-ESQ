# Sistema de Registro de Actividades (Logs)

## Descripción General

El sistema ahora cuenta con un **registro completo de actividades** que monitorea todas las acciones realizadas en el sistema, incluyendo:

- ✅ Acciones en el sistema web
- ✅ Comandos del bot de Telegram
- ✅ Búsquedas inline de Telegram
- ✅ Operaciones CRUD en modelos
- ✅ Errores del sistema

## Características Principales

### 1. **Trait Reutilizable: LogsActivity**

Ubicación: `app/Traits/LogsActivity.php`

Este trait proporciona métodos estáticos para registrar diferentes tipos de actividades:

#### Métodos Disponibles:

```php
// Actividad genérica
LogsActivity::logActivity($description, $logName, $properties, $subject, $causer);

// Actividad del sistema
LogsActivity::logSystemActivity($description, $properties);

// Actividad del bot de Telegram
LogsActivity::logTelegramActivity($description, $properties, $telegramUser);

// Operaciones CRUD
LogsActivity::logCreated($modelName, $model, $additionalProperties);
LogsActivity::logUpdated($modelName, $model, $changes, $additionalProperties);
LogsActivity::logDeleted($modelName, $model, $additionalProperties);

// Autenticación
LogsActivity::logAuth($action, $user, $properties);

// Errores
LogsActivity::logError($description, $exception, $additionalContext);
```

### 2. **Modelos con Logging Automático**

Los siguientes modelos ya tienen logging automático implementado:

- ✅ `User` - Acciones de usuarios
- ✅ `Beneficiary` - Gestión de beneficiarios
- ✅ `Product` - Gestión de productos
- ✅ `Inventory` - Movimientos de inventario
- ✅ `Report` - Reportes de entrega

El logging automático registra:
- Creación de registros
- Actualización (solo campos modificados)
- Eliminación

### 3. **Bot de Telegram con Logging**

El bot de Telegram registra automáticamente:

- ✅ Comandos ejecutados (ej. /stats, /menu, etc.)
- ✅ Búsquedas inline
- ✅ Interacciones con botones
- ✅ Mensajes de texto
- ✅ Errores en el webhook

**Ejemplo de log de Telegram:**
```json
{
  "source": "telegram_bot",
  "command": "stats",
  "telegram_user": {
    "id": 123456789,
    "username": "usuario_telegram",
    "first_name": "Juan",
    "last_name": "Pérez"
  },
  "timestamp": "2025-10-29 21:00:00"
}
```

### 4. **Interfaz Web de Visualización**

Acceso: `/activity-logs` o desde el menú "Registro de Actividades"

**Características:**

- 📊 **Estadísticas en tiempo real:**
  - Total de registros
  - Actividades del día
  - Actividades de la semana
  - Logs de Telegram
  - Logs del sistema
  - Errores registrados

- 🔍 **Filtros avanzados:**
  - Búsqueda por descripción
  - Filtro por tipo de log (telegram, system, model, auth, error)
  - Rango de fechas personalizado
  - Paginación configurable (10, 25, 50, 100)

- 📋 **Vista detallada:**
  - Fecha y hora exacta
  - Tipo de actividad con badge de color
  - Descripción de la acción
  - Usuario que realizó la acción
  - Propiedades JSON expandibles con todos los detalles

## Tipos de Logs

### 1. **telegram** (Logs del Bot)
- Color: Cyan
- Incluye: comandos, búsquedas, interacciones
- Información del usuario de Telegram incluida

### 2. **system** (Logs del Sistema)
- Color: Amarillo
- Incluye: acciones generales del sistema
- IP y User-Agent incluidos

### 3. **model** (Operaciones CRUD)
- Color: Azul
- Incluye: created, updated, deleted
- Cambios old/new incluidos en updates

### 4. **auth** (Autenticación)
- Color: Verde
- Incluye: login, logout, register
- IP y User-Agent incluidos

### 5. **error** (Errores)
- Color: Rojo
- Incluye: excepciones, errores del sistema
- Stack trace completo incluido

## Uso en el Código

### Ejemplo 1: Registrar una actividad del sistema

```php
use App\Traits\LogsActivity;

class MiControlador extends Controller
{
    use LogsActivity;

    public function miMetodo()
    {
        // Tu código aquí...
        
        self::logSystemActivity(
            'Usuario exportó reporte de ventas',
            [
                'reporte_tipo' => 'ventas',
                'fecha_inicio' => '2025-01-01',
                'fecha_fin' => '2025-01-31',
            ]
        );
    }
}
```

### Ejemplo 2: Registrar una actividad del bot de Telegram

```php
use App\Traits\LogsActivity;

class MiComando extends Command
{
    use LogsActivity;

    public function handle()
    {
        $from = $this->getUpdate()->getMessage()->getFrom();
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
        ];

        // Tu código del comando...

        self::logTelegramActivity(
            'Usuario consultó información',
            [
                'command' => 'mi_comando',
                'parametros' => ['foo' => 'bar']
            ],
            $telegramUser
        );
    }
}
```

### Ejemplo 3: Registrar un error

```php
use App\Traits\LogsActivity;

try {
    // Código que puede fallar...
} catch (\Exception $e) {
    LogsActivity::logError(
        'Error al procesar solicitud',
        $e,
        [
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]
    );
}
```

## Configuración

### Variables de Entorno

```env
ACTIVITY_LOGGER_ENABLED=true
ACTIVITY_LOGGER_TABLE_NAME=activity_log
ACTIVITY_LOGGER_DB_CONNECTION=null
```

### Limpieza Automática

Los logs más antiguos de **365 días** se eliminan automáticamente.

Puedes configurar esto en: `config/activitylog.php`

```php
'delete_records_older_than_days' => 365,
```

## Permisos

Actualmente el acceso al registro de actividades usa el permiso `view-dashboard`.

Para cambiar esto, edita: `routes/admin.php`

```php
Volt::route('/activity-logs', 'pages.admin.activity-logs.index')
    ->middleware('permission:tu-permiso-aqui')
    ->name('activity-logs.index');
```

## Base de Datos

### Tabla: `activity_log`

Campos principales:
- `log_name`: Tipo de log (telegram, system, model, etc.)
- `description`: Descripción de la actividad
- `subject_type`: Tipo del modelo afectado (si aplica)
- `subject_id`: ID del modelo afectado (si aplica)
- `causer_type`: Tipo del usuario que causó la acción
- `causer_id`: ID del usuario que causó la acción
- `properties`: JSON con información adicional
- `created_at`: Fecha y hora del registro

## Próximos Pasos Recomendados

1. **Agregar logging a más acciones específicas:**
   - Exportación de reportes
   - Cambios de configuración
   - Envío de notificaciones

2. **Implementar alertas:**
   - Notificaciones para errores críticos
   - Alertas de actividades sospechosas

3. **Crear dashboard de analytics:**
   - Gráficos de actividad por usuario
   - Tendencias de uso del sistema
   - Métricas del bot de Telegram

4. **Exportación de logs:**
   - Exportar a CSV/Excel
   - Reportes programados por email

## Soporte Técnico

Para más información sobre el paquete utilizado:
- [Spatie Activity Log Documentation](https://spatie.be/docs/laravel-activitylog/v4/introduction)
