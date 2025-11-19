# Sistema de Autenticación de Telegram

## 📋 Descripción General

El bot de Telegram ahora cuenta con un **sistema de autenticación completo** que vincula a los usuarios de Telegram con las cuentas del sistema web. Esto garantiza que solo usuarios autorizados puedan acceder a la información del sistema a través del bot.

---

## 🎯 ¿Cómo Funciona?

### Flujo de Autenticación

```
Usuario abre el bot → /start → Solicita /login → 
Ingresa usuario → Ingresa contraseña → 
Sistema valida credenciales → Vincula chat_id al usuario → 
Acceso completo al bot ✅
```

### Ejemplo Práctico

**Primera vez que accede:**
1. Usuario: "Hola bot" → Bot solicita autenticación
2. Usuario: `/login`
3. Bot: "Ingresa tu usuario o email"
4. Usuario: `angel`
5. Bot: "Ingresa tu contraseña"
6. Usuario: `1234`
7. Bot verifica credenciales ✅
8. Bot guarda el `chat_id` del usuario en la BD
9. Bot: "¡Bienvenido Angel! Ahora puedes usar el bot"

**Segunda vez que accede:**
1. Usuario: `/start`
2. Bot detecta que el `chat_id` ya está vinculado
3. Bot: "¡Hola de nuevo Angel!" (acceso directo)

---

## 🔧 Cambios Implementados

### 1. **Nueva Columna en Base de Datos**

**Tabla:** `users`  
**Nueva columna:** `telegram_chat_id`

```sql
telegram_chat_id VARCHAR(255) NULLABLE UNIQUE
```

Esta columna almacena el ID único del chat de Telegram del usuario.

**Migración ejecutada:**
```
2025_10_29_212717_add_telegram_chat_id_to_users_table.php
```

---

### 2. **Nuevos Comandos del Bot**

#### `/login` - Iniciar Sesión
Inicia el proceso de autenticación en 2 pasos:
1. Solicita nombre de usuario o email
2. Solicita contraseña
3. Valida credenciales contra la base de datos
4. Vincula `chat_id` si es correcto

**Características:**
- ✅ Timeout de 5 minutos para completar el proceso
- ✅ Valida con `Hash::check()` (seguridad)
- ✅ Detecta si la cuenta ya está vinculada a otro chat
- ✅ Registra intentos fallidos en el log
- ✅ Mensaje personalizado para primer login vs re-login

#### `/logout` - Cerrar Sesión
Desvincula el `chat_id` de la cuenta del usuario.

**Uso:**
```
/logout → "¿Estás seguro?" → Elimina chat_id de BD → "Sesión cerrada"
```

---

### 3. **Comandos Protegidos**

Todos los comandos principales ahora requieren autenticación:

| Comando | Descripción | Requiere Auth |
|---------|-------------|---------------|
| `/start` | Iniciar bot | ⚠️ Parcial (muestra mensaje si no auth) |
| `/login` | Autenticarse | ❌ No (es para autenticarse) |
| `/logout` | Cerrar sesión | ✅ Sí |
| `/menu` | Ver menú | ✅ Sí |
| `/stats` | Ver estadísticas | ✅ Sí |
| `/beneficiaries` | Lista de beneficiarios | ✅ Sí |
| `/reports` | Ver reportes | ✅ Sí |
| `/inventory` | Ver inventario | ✅ Sí |
| `/search` | Buscar beneficiarios | ✅ Sí |
| **Búsquedas inline** | Buscar en cualquier chat | ✅ Sí |

---

### 4. **Trait `RequiresAuth`**

Creado en: `app/Telegram/Traits/RequiresAuth.php`

Este trait proporciona funcionalidad de autenticación reutilizable:

**Métodos disponibles:**

```php
// Verificar si está autenticado
$user = $this->checkAuth(); // Retorna User o null

// Requerir autenticación (envía mensaje si no auth)
$user = $this->requireAuth(); // Retorna User o false
if (!$user) {
    return; // Mensaje enviado automáticamente
}

// Enviar mensaje de no autenticado
$this->sendUnauthenticatedMessage();
```

**Uso en comandos:**

```php
use App\Telegram\Traits\RequiresAuth;

class MiComando extends Command
{
    use RequiresAuth;

    public function handle()
    {
        // Verificar autenticación
        $user = $this->requireAuth();
        if (!$user) {
            return; // Automáticamente envía mensaje de error
        }
        
        // Usuario autenticado - continuar con la lógica
        // $user contiene el modelo User de Laravel
    }
}
```

---

### 5. **Protección de Búsquedas Inline**

Las búsquedas inline (buscar desde cualquier chat de Telegram) también requieren autenticación.

**Comportamiento:**
- Usuario no autenticado intenta buscar
- Bot muestra: "🔐 Inicia sesión para buscar"
- Al hacer clic, redirige al bot con `/login`

**Implementación en:** `TelegramBotController::webhook()`

---

## 🔒 Seguridad Implementada

### 1. **Hash de Contraseñas**
```php
Hash::check($password, $user->password)
```
No se almacena la contraseña en texto plano ni en logs.

### 2. **Validación de Cuenta Vinculada**
Si un usuario intenta autenticarse con una cuenta que ya está vinculada a otro chat:
```
⚠️ Cuenta ya vinculada
Esta cuenta ya está vinculada a otro chat de Telegram.
Cierra sesión desde el otro dispositivo primero.
```

### 3. **Timeout de Login**
El proceso de login tiene un timeout de 5 minutos (300 segundos) usando Laravel Cache.

### 4. **Logging Completo**
Todos los intentos de autenticación se registran:
- ✅ Login exitoso
- ❌ Credenciales incorrectas  
- ⚠️ Intento de acceso sin autenticación
- 🔄 Primer login vs re-login

Ver en: `/activity-logs` → Filtrar por tipo "auth"

---

## 📊 Logging de Autenticación

### Eventos Registrados

#### Login Exitoso
```json
{
  "log_name": "auth",
  "description": "Autenticación exitosa en Telegram",
  "properties": {
    "chat_id": "123456789",
    "telegram_user": {
      "id": 123456789,
      "username": "angel_user",
      "first_name": "Angel",
      "last_name": "Pérez"
    },
    "is_first_login": true
  },
  "causer": "User ID 5"
}
```

#### Login Fallido
```json
{
  "log_name": "telegram",
  "description": "Intento de login fallido",
  "properties": {
    "username": "angel",
    "reason": "invalid_credentials",
    "telegram_user": { ... }
  }
}
```

#### Acceso Sin Autenticación
```json
{
  "log_name": "telegram",
  "description": "Intento de acceso sin autenticación",
  "properties": {
    "command": "stats",
    "authenticated": false,
    "telegram_user": { ... }
  }
}
```

#### Logout
```json
{
  "log_name": "auth",
  "description": "Cerró sesión en Telegram",
  "properties": {
    "chat_id": "123456789",
    "telegram_user": { ... }
  },
  "causer": "User ID 5"
}
```

---

## 🧪 Pruebas del Sistema

### Caso 1: Primera Autenticación

```
Usuario: /start
Bot: 👋 ¡Hola Juan!
     🎯 Bienvenido al Sistema de Control de Beneficios 1X10 Escuque
     🔐 Para usar el bot, necesitas autenticarte con tu cuenta del sistema.
     📝 Usa el comando /login para iniciar sesión.

Usuario: /login
Bot: 🔐 Autenticación Requerida
     Para usar el bot, necesitas autenticarte con tu cuenta del sistema.
     📝 Por favor, ingresa tu nombre de usuario o email:
     (Tienes 5 minutos para completar el proceso)

Usuario: angel@example.com
Bot: 🔑 Ahora ingresa tu contraseña:

Usuario: mipassword123
Bot: 🎉 ¡Bienvenido Angel!
     ✅ Tu cuenta ha sido vinculada exitosamente.
     Ahora puedes usar todos los comandos del bot.
     Usa /menu para ver las opciones disponibles.
     [Teclado con botones mostrado]
```

### Caso 2: Credenciales Incorrectas

```
Usuario: /login
Bot: 📝 Por favor, ingresa tu nombre de usuario o email:

Usuario: angel
Bot: 🔑 Ahora ingresa tu contraseña:

Usuario: password_incorrecto
Bot: ❌ Credenciales incorrectas
     Usuario o contraseña inválidos.
     Intenta nuevamente con /login
```

### Caso 3: Usuario Ya Autenticado

```
Usuario: /start
Bot: 👋 ¡Hola Angel!
     🎯 Bienvenido al Sistema de Control de Beneficios 1X10 Escuque
     Usa /menu para ver todas las opciones disponibles.
     💡 Comandos rápidos:
     • /menu - Ver menú principal
     • /search - Buscar beneficiario
     • /help - Ver ayuda
     • /logout - Cerrar sesión
     [Teclado con botones mostrado]

Usuario: /stats
Bot: [Muestra estadísticas - acceso permitido]
```

### Caso 4: Acceso Sin Autenticación

```
Usuario: /stats
Bot: 🔐 Acceso Restringido
     Necesitas autenticarte para usar este comando.
     📝 Usa /login para iniciar sesión con tu cuenta del sistema.
```

### Caso 5: Cerrar Sesión

```
Usuario: /logout
Bot: 👋 Sesión Cerrada
     Tu cuenta Angel ha sido desvinculada de este chat.
     Para volver a usar el bot, usa /login
```

---

## 💻 Integración con Sistema Web

### Ver Usuarios Autenticados en Telegram

Desde el panel web, puedes ver qué usuarios tienen su Telegram vinculado:

**Consulta SQL:**
```sql
SELECT id, name, email, telegram_chat_id
FROM users
WHERE telegram_chat_id IS NOT NULL;
```

**En Laravel:**
```php
$usersWithTelegram = User::whereNotNull('telegram_chat_id')->get();
```

### Desvincular Usuario (Desde Web)

Si necesitas desvincular un usuario desde el panel web:

```php
$user = User::find($userId);
$user->telegram_chat_id = null;
$user->save();
```

---

## 🔧 Mantenimiento

### Comandos Útiles

**Actualizar comandos del bot en Telegram:**
```bash
php artisan telegram:commands
```

**Ver información del bot:**
```bash
php artisan telegram:info
```

### Cache de Login

El proceso de login usa Laravel Cache con timeout de 5 minutos.

**Keys usadas:**
- `telegram_login_step_{chatId}` - Paso actual del login
- `telegram_login_username_{chatId}` - Username temporal

**Limpiar cache manualmente:**
```php
Cache::forget("telegram_login_step_{$chatId}");
Cache::forget("telegram_login_username_{$chatId}");
```

---

## 📝 Archivos Modificados/Creados

### Nuevos Archivos
```
database/migrations/
└── 2025_10_29_212717_add_telegram_chat_id_to_users_table.php

app/Telegram/Commands/
├── LoginCommand.php     ✅ Nuevo
└── LogoutCommand.php    ✅ Nuevo

app/Telegram/Traits/
└── RequiresAuth.php     ✅ Nuevo
```

### Archivos Modificados
```
app/Models/User.php
└── Agregado 'telegram_chat_id' a $fillable

app/Http/Controllers/TelegramBotController.php
├── Agregado handleLoginFlow()
└── Verificación de auth en inline queries

app/Telegram/Commands/
├── StartCommand.php      → Verifica autenticación
├── MenuCommand.php       → Requiere auth
├── StatsCommand.php      → Requiere auth
├── BeneficiariesCommand.php → Requiere auth
├── SearchCommand.php     → Requiere auth
├── ReportsCommand.php    → Requiere auth
└── InventoryCommand.php  → Requiere auth

config/telegram.php
└── Agregados LoginCommand y LogoutCommand
```

---

## 🎯 Próximos Pasos Recomendados

1. **Agregar comando `/myaccount`**
   - Ver información de la cuenta vinculada
   - Última conexión
   - Roles y permisos

2. **Notificaciones Push**
   - Enviar notificaciones del sistema web al Telegram
   - Alertas de reportes nuevos
   - Cambios importantes

3. **Autenticación con código QR**
   - Generar código QR en el panel web
   - Escanear con Telegram para vincular

4. **Roles y permisos**
   - Restringir comandos según rol del usuario
   - Admin puede ver todo, otros roles acceso limitado

5. **Sesiones múltiples**
   - Permitir un usuario en varios chats
   - Gestionar sesiones activas

---

## ⚠️ Consideraciones de Seguridad

### ✅ Buenas Prácticas Implementadas
- Contraseñas hasheadas (nunca en texto plano)
- Timeout de login (5 minutos)
- Logging completo de autenticaciones
- Validación de cuenta ya vinculada
- Chat ID único por usuario

### ⚠️ Recomendaciones Adicionales
1. **Rate limiting:** Limitar intentos de login por IP/chat
2. **2FA:** Implementar autenticación de dos factores
3. **Alertas:** Notificar al usuario cuando se vincule su cuenta
4. **Expiración:** Opcional - desvincular después de X días de inactividad
5. **Blacklist:** Bloquear chat IDs sospechosos

---

## 📞 Soporte

Para más información sobre los packages utilizados:
- [Laravel Telegram Bot](https://telegram-bot-sdk.readme.io/)
- [Spatie Activity Log](https://spatie.be/docs/laravel-activitylog)

---

**Fecha de implementación:** 29 de Octubre, 2025  
**Versión:** 1.0.0  
**Estado:** ✅ Completado y Funcional
