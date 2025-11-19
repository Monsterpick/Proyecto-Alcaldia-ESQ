# 🤖 Guía de Implementación del Bot de Telegram

## 📋 Índice
1. [Configuración Inicial](#configuración-inicial)
2. [Comandos Disponibles](#comandos-disponibles)
3. [Uso del Servicio](#uso-del-servicio)
4. [Notificaciones Automáticas](#notificaciones-automáticas)
5. [API del Bot](#api-del-bot)

---

## 🔧 Configuración Inicial

### 1. Crear el Bot en Telegram

1. Abre Telegram y busca **@BotFather**
2. Envía `/newbot`
3. Sigue las instrucciones:
   - Nombre: `Escuque Bot`
   - Username: `escuque_bot` (debe terminar en _bot)
4. Copia el **token** que te proporciona

### 2. Configurar en Laravel

Edita tu archivo `.env` y agrega:

```env
TELEGRAM_BOT_TOKEN=123456789:ABCdefGHIjklMNOpqrsTUVwxyz
TELEGRAM_BOT_NAME="Escuque Bot"
TELEGRAM_ASYNC_REQUESTS=false
```

### 3. Verificar la Configuración

```bash
# Probar conexión con el bot
curl http://127.0.0.1:8000/api/telegram/me
```

---

## 📱 Comandos Disponibles

### `/start` - Comando de inicio
Muestra el mensaje de bienvenida y lista de comandos disponibles.

### `/stats` - Estadísticas del sistema
Muestra:
- Total de beneficiarios (activos/inactivos)
- Reportes de entregas (total/entregados/en proceso)
- Total de productos en inventario

### `/beneficiaries` - Consultar beneficiarios
- **Sin argumentos**: Muestra resumen y últimos 5 registrados
- **Con cédula**: `/beneficiaries V12345678` - Busca información específica

### `/reports` - Ver reportes
- **Sin argumentos**: Muestra resumen y últimos 5 reportes
- **Con código**: `/reports RPT-20251026-0001` - Ver detalle

### `/inventory` - Estado del inventario
Muestra productos disponibles, stock bajo y últimos movimientos.

### `/help` - Ayuda
Lista todos los comandos disponibles.

---

## 💻 Uso del Servicio de Telegram

### Ejemplo Básico

```php
use App\Services\TelegramService;

// En tu controlador o componente Livewire
$telegram = app(TelegramService::class);

// Enviar mensaje simple
$telegram->sendMessage(
    '123456789', // Chat ID del destinatario
    '¡Hola desde Escuque!'
);
```

### Notificar Nuevo Beneficiario

```php
use App\Services\TelegramService;
use App\Models\Beneficiary;

// Después de crear un beneficiario
$beneficiary = Beneficiary::create([...]);

$telegram = app(TelegramService::class);
$telegram->notifyNewBeneficiary(
    $beneficiary,
    '123456789' // Chat ID del administrador
);
```

### Notificar Nueva Entrega

```php
use App\Services\TelegramService;
use App\Models\Report;

$report = Report::create([...]);

$telegram = app(TelegramService::class);
$telegram->notifyNewDelivery(
    $report,
    '123456789'
);
```

### Alerta de Stock Bajo

```php
$telegram = app(TelegramService::class);
$telegram->notifyLowStock(
    $product,
    5, // Cantidad disponible
    '123456789'
);
```

---

## 🔔 Notificaciones Automáticas

### Implementar en Eventos de Eloquent

Puedes agregar notificaciones automáticas en tus modelos:

#### En `app/Models/Beneficiary.php`

```php
protected static function booted()
{
    static::created(function ($beneficiary) {
        if (config('telegram.notifications.enabled', false)) {
            $telegram = app(\App\Services\TelegramService::class);
            $chatId = config('telegram.notifications.chat_id');
            
            $telegram->notifyNewBeneficiary($beneficiary, $chatId);
        }
    });
}
```

#### En `app/Models/Report.php`

```php
protected static function booted()
{
    static::created(function ($report) {
        if (config('telegram.notifications.enabled', false)) {
            $telegram = app(\App\Services\TelegramService::class);
            $chatId = config('telegram.notifications.chat_id');
            
            $telegram->notifyNewDelivery($report, $chatId);
        }
    });
    
    static::updated(function ($report) {
        if ($report->wasChanged('status') && $report->status === 'delivered') {
            if (config('telegram.notifications.enabled', false)) {
                $telegram = app(\App\Services\TelegramService::class);
                $chatId = config('telegram.notifications.chat_id');
                
                $telegram->notifyDeliveryCompleted($report, $chatId);
            }
        }
    });
}
```

### Agregar Configuración

Agrega al archivo `.env`:

```env
TELEGRAM_NOTIFICATIONS_ENABLED=true
TELEGRAM_NOTIFICATIONS_CHAT_ID=123456789
```

Y en `config/telegram.php` agrega:

```php
'notifications' => [
    'enabled' => env('TELEGRAM_NOTIFICATIONS_ENABLED', false),
    'chat_id' => env('TELEGRAM_NOTIFICATIONS_CHAT_ID'),
],
```

---

## 🌐 API del Bot

### Obtener Información del Bot

```bash
GET /api/telegram/me
```

### Configurar Webhook (Para producción)

```bash
POST /api/telegram/set-webhook
Content-Type: application/json

{
  "url": "https://tu-dominio.com/api/telegram/webhook"
}
```

### Eliminar Webhook

```bash
POST /api/telegram/remove-webhook
```

### Enviar Mensaje de Prueba

```bash
POST /api/telegram/test
Content-Type: application/json

{
  "chat_id": "123456789",
  "message": "Mensaje de prueba"
}
```

---

## 🔍 Obtener tu Chat ID

Para recibir notificaciones, necesitas tu Chat ID:

1. **Inicia conversación con tu bot** en Telegram
2. **Envía cualquier mensaje** al bot
3. **Obtén tu Chat ID** visitando:
   ```
   https://api.telegram.org/bot<TU_TOKEN>/getUpdates
   ```
4. **Busca** el campo `"chat":{"id":123456789}`

O usa este bot: [@userinfobot](https://t.me/userinfobot)

---

## 📊 Reporte Diario Programado

Para enviar reportes diarios automáticos, crea un comando programado:

### 1. Crear el comando artisan

```bash
php artisan make:command SendDailyTelegramReport
```

### 2. Editar el comando

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class SendDailyTelegramReport extends Command
{
    protected $signature = 'telegram:daily-report';
    protected $description = 'Enviar reporte diario por Telegram';

    public function handle(TelegramService $telegram)
    {
        $chatId = config('telegram.notifications.chat_id');
        
        if ($telegram->sendDailyReport($chatId)) {
            $this->info('Reporte diario enviado exitosamente');
        } else {
            $this->error('Error al enviar reporte diario');
        }
    }
}
```

### 3. Programar en `app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('telegram:daily-report')
        ->dailyAt('18:00'); // Enviar a las 6 PM
}
```

---

## ⚠️ Notas Importantes

1. **Seguridad**: Nunca expongas tu token en repositorios públicos
2. **Rate Limits**: Telegram tiene límites de mensajes por segundo
3. **Modo Polling vs Webhook**: En desarrollo usa polling, en producción usa webhooks
4. **Chat IDs**: Cada usuario/grupo tiene un chat_id único
5. **Markdown**: Los mensajes soportan formato Markdown

---

## 🆘 Solución de Problemas

### El bot no responde a comandos

1. Verifica que el token sea correcto
2. Asegúrate de haber iniciado conversación con el bot
3. Revisa los logs: `php artisan pail`

### Error de conexión

```bash
# Limpiar cache de configuración
php artisan config:clear

# Verificar token
php artisan tinker
>>> Telegram::getMe();
```

### Comandos no se registran

```bash
# Registrar comandos manualmente
php artisan tinker
>>> use Telegram\Bot\Laravel\Facades\Telegram;
>>> Telegram::setMyCommands(['commands' => [
    ['command' => 'start', 'description' => 'Iniciar el bot'],
    ['command' => 'stats', 'description' => 'Ver estadísticas'],
    ['command' => 'beneficiaries', 'description' => 'Consultar beneficiarios'],
    ['command' => 'reports', 'description' => 'Ver reportes'],
    ['command' => 'inventory', 'description' => 'Estado del inventario'],
    ['command' => 'help', 'description' => 'Ayuda'],
]]);
```

---

## 📚 Recursos Adicionales

- [Documentación Oficial de Telegram Bot API](https://core.telegram.org/bots/api)
- [Repositorio del SDK](https://github.com/irazasyed/telegram-bot-sdk)
- [BotFather - Crear y gestionar bots](https://t.me/botfather)

---

**¡Tu bot de Telegram está listo para usar!** 🎉
