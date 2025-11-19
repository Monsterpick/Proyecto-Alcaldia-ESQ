# Ejemplos de Uso del Sistema de Logs

Este documento muestra ejemplos prácticos de cómo usar el sistema de logging en diferentes escenarios.

## Índice
1. [Logging en Controladores](#logging-en-controladores)
2. [Logging en Livewire Components](#logging-en-livewire-components)
3. [Logging Automático en Modelos](#logging-automático-en-modelos)
4. [Logging en Comandos de Telegram](#logging-en-comandos-de-telegram)
5. [Logging de Errores](#logging-de-errores)

---

## Logging en Controladores

### Ejemplo 1: Registrar exportación de datos

```php
<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    use LogsActivity;

    public function exportBeneficiaries(Request $request)
    {
        // Tu lógica de exportación
        $format = $request->input('format', 'excel');
        $filters = $request->except('format');
        
        // Generar el archivo...
        $file = $this->generateExport($format, $filters);
        
        // Registrar la actividad
        self::logSystemActivity(
            'Exportó listado de beneficiarios',
            [
                'format' => $format,
                'filters' => $filters,
                'total_records' => 100, // Número de registros exportados
                'file_name' => $file->getFilename(),
            ]
        );
        
        return response()->download($file);
    }
}
```

### Ejemplo 2: Registrar cambio de configuración

```php
<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use LogsActivity;

    public function updateSettings(Request $request)
    {
        $oldSettings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Actualizar configuración
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        $newSettings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Registrar cambios
        self::logSystemActivity(
            'Actualizó configuración del sistema',
            [
                'old_values' => $oldSettings,
                'new_values' => $newSettings,
                'changed_keys' => array_keys(array_diff($oldSettings, $newSettings)),
            ]
        );
        
        return redirect()->back()->with('success', 'Configuración actualizada');
    }
}
```

---

## Logging en Livewire Components

### Ejemplo 1: Registrar creación de beneficiario

```php
<?php

namespace App\Livewire\Pages\Admin\Beneficiaries;

use App\Models\Beneficiary;
use App\Traits\LogsActivity;
use Livewire\Component;

class Create extends Component
{
    use LogsActivity;
    
    public $first_name;
    public $last_name;
    public $cedula;
    // ... otros campos
    
    public function save()
    {
        $validated = $this->validate();
        
        $beneficiary = Beneficiary::create($validated);
        
        // El modelo ya registra automáticamente la creación,
        // pero puedes agregar información adicional si lo deseas
        self::logSystemActivity(
            'Creó nuevo beneficiario desde formulario web',
            [
                'beneficiary_id' => $beneficiary->id,
                'beneficiary_name' => $beneficiary->full_name,
                'beneficiary_cedula' => $beneficiary->full_cedula,
                'source' => 'web_form',
            ]
        );
        
        session()->flash('success', 'Beneficiario creado exitosamente');
        return redirect()->route('admin.beneficiaries.index');
    }
}
```

### Ejemplo 2: Registrar eliminación múltiple

```php
<?php

namespace App\Livewire\Pages\Admin\Products;

use App\Models\Product;
use App\Traits\LogsActivity;
use Livewire\Component;

class Index extends Component
{
    use LogsActivity;
    
    public $selected = [];
    
    public function deleteSelected()
    {
        $products = Product::whereIn('id', $this->selected)->get();
        $productNames = $products->pluck('name')->toArray();
        
        Product::whereIn('id', $this->selected)->delete();
        
        // Registrar eliminación múltiple
        self::logSystemActivity(
            'Eliminó productos en lote',
            [
                'total_deleted' => count($this->selected),
                'product_ids' => $this->selected,
                'product_names' => $productNames,
                'action' => 'bulk_delete',
            ]
        );
        
        $this->selected = [];
        session()->flash('success', 'Productos eliminados exitosamente');
    }
}
```

---

## Logging Automático en Modelos

Los modelos con el trait `LogsActivity` registran automáticamente las operaciones CRUD:

### Ejemplo 1: Beneficiary Model (Ya implementado)

```php
<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use LogsActivity; // ← Esto habilita el logging automático
    
    // ... resto del modelo
}
```

**Qué se registra automáticamente:**
- ✅ Creación: Todos los atributos del nuevo registro
- ✅ Actualización: Solo los campos que cambiaron (old vs new)
- ✅ Eliminación: Todos los atributos del registro eliminado

**Ejemplo de log automático generado:**

Cuando ejecutas:
```php
$beneficiary = Beneficiary::create([
    'first_name' => 'Juan',
    'last_name' => 'Pérez',
    'cedula' => '12345678',
]);
```

Se genera automáticamente:
```json
{
  "log_name": "model",
  "description": "created",
  "properties": {
    "attributes": {
      "first_name": "Juan",
      "last_name": "Pérez",
      "cedula": "12345678",
      "status": "active"
    }
  },
  "causer": { "id": 1, "name": "Admin User" }
}
```

### Ejemplo 2: Personalizar el logging del modelo

Si necesitas personalizar qué se registra:

```php
<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class CustomModel extends Model
{
    use LogsActivity;
    
    // Personalizar opciones de logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'price']) // Solo estos campos
            ->logOnlyDirty() // Solo si cambiaron
            ->dontSubmitEmptyLogs() // No guardar si no hay cambios
            ->useLogName('custom_model') // Nombre personalizado
            ->setDescriptionForEvent(fn(string $eventName) => "El modelo fue {$eventName}");
    }
}
```

---

## Logging en Comandos de Telegram

### Ejemplo: Comando personalizado con logging

```php
<?php

namespace App\Telegram\Commands;

use App\Models\Beneficiary;
use App\Traits\LogsActivity;
use Telegram\Bot\Commands\Command;

class CustomReportCommand extends Command
{
    use LogsActivity;
    
    protected string $name = 'customreport';
    protected string $description = 'Generar reporte personalizado';

    public function handle()
    {
        // Obtener información del usuario de Telegram
        $from = $this->getUpdate()->getMessage()->getFrom();
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        
        // Obtener argumentos del comando
        $arguments = $this->getArguments();
        $reportType = $arguments['type'] ?? 'general';
        
        try {
            // Generar el reporte
            $data = $this->generateReport($reportType);
            
            // Enviar el reporte
            $this->replyWithMessage([
                'text' => $data,
                'parse_mode' => 'Markdown',
            ]);
            
            // Registrar actividad exitosa
            self::logTelegramActivity(
                'Generó reporte personalizado',
                [
                    'command' => 'customreport',
                    'report_type' => $reportType,
                    'records_count' => count($data),
                    'success' => true,
                ],
                $telegramUser
            );
            
        } catch (\Exception $e) {
            // Registrar error
            self::logError(
                'Error al generar reporte en Telegram',
                $e,
                [
                    'command' => 'customreport',
                    'telegram_user' => $telegramUser,
                    'report_type' => $reportType,
                ]
            );
            
            $this->replyWithMessage([
                'text' => '❌ Error al generar el reporte',
            ]);
        }
    }
}
```

---

## Logging de Errores

### Ejemplo 1: Capturar y registrar excepciones

```php
<?php

namespace App\Services;

use App\Traits\LogsActivity;
use Exception;

class PaymentService
{
    use LogsActivity;
    
    public function processPayment($orderId, $amount)
    {
        try {
            // Lógica de procesamiento de pago
            $result = $this->chargePayment($orderId, $amount);
            
            // Registrar pago exitoso
            self::logSystemActivity(
                'Procesó pago exitosamente',
                [
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'transaction_id' => $result->transaction_id,
                ]
            );
            
            return $result;
            
        } catch (Exception $e) {
            // Registrar error detallado
            self::logError(
                'Error al procesar pago',
                $e,
                [
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'user_id' => auth()->id(),
                    'ip' => request()->ip(),
                ]
            );
            
            throw $e; // Re-lanzar la excepción
        }
    }
}
```

### Ejemplo 2: Logging en excepciones personalizadas

```php
<?php

namespace App\Exceptions;

use App\Traits\LogsActivity;
use Exception;

class CustomBusinessException extends Exception
{
    use LogsActivity;
    
    protected $context;
    
    public function __construct($message, $context = [])
    {
        parent::__construct($message);
        $this->context = $context;
        
        // Registrar automáticamente cuando se lanza la excepción
        self::logError(
            $message,
            $this,
            array_merge($context, [
                'exception_type' => 'business_logic',
                'severity' => 'warning',
            ])
        );
    }
}
```

### Ejemplo 3: Registrar errores de validación

```php
<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FormController extends Controller
{
    use LogsActivity;

    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email',
            ]);
            
            // Procesar datos...
            
        } catch (ValidationException $e) {
            // Registrar errores de validación
            self::logSystemActivity(
                'Error de validación en formulario',
                [
                    'errors' => $e->errors(),
                    'input' => $request->except(['password', 'token']),
                    'route' => $request->path(),
                ]
            );
            
            throw $e;
        }
    }
}
```

---

## Consultar Logs Programáticamente

### Ejemplo: Obtener logs de un usuario específico

```php
use Spatie\Activitylog\Models\Activity;

// Todos los logs de un usuario
$userLogs = Activity::where('causer_id', auth()->id())
    ->where('causer_type', 'App\Models\User')
    ->latest()
    ->get();

// Logs del bot de Telegram
$telegramLogs = Activity::where('log_name', 'telegram')
    ->whereDate('created_at', today())
    ->get();

// Logs de errores
$errors = Activity::where('log_name', 'error')
    ->where('created_at', '>=', now()->subHours(24))
    ->get();

// Logs de un modelo específico
$beneficiaryLogs = Activity::where('subject_type', 'App\Models\Beneficiary')
    ->where('subject_id', $beneficiaryId)
    ->latest()
    ->get();
```

---

## Tips y Mejores Prácticas

### ✅ DO (Hacer):
- Registra acciones importantes del usuario
- Incluye contexto relevante en las propiedades
- Usa nombres descriptivos para las actividades
- Registra errores con información suficiente para debugging

### ❌ DON'T (No hacer):
- No registres información sensible (contraseñas, tokens, etc.)
- No registres cada consulta a base de datos
- No uses el logging para debugging en producción intensivamente
- No guardes datos de usuario sin sanitizar

### 🔒 Seguridad:
```php
// ❌ INCORRECTO - Expone información sensible
self::logSystemActivity('Login exitoso', [
    'password' => $request->password,
    'token' => $token,
]);

// ✅ CORRECTO - Solo información necesaria
self::logSystemActivity('Login exitoso', [
    'username' => $request->username,
    'ip' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

---

## Próximos Pasos

1. **Crear dashboard de analytics** basado en los logs
2. **Implementar alertas** para eventos críticos
3. **Exportar logs** en diferentes formatos
4. **Crear reportes programados** por email

Para más información, consulta: [SISTEMA_LOGS.md](./SISTEMA_LOGS.md)
