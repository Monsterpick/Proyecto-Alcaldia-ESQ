# 🔍 Guía para Verificar y Monitorear Logs del Bot de Telegram

---

## 📊 Opción 1: Verificar en el Panel Administrativo

### Pasos:

1. **Accede al panel administrativo**
   - URL: `http://localhost:8000/admin`
   - Inicia sesión con tu cuenta

2. **Ve a Logs de Actividad**
   - Menú lateral → Configuración → Logs de Actividad
   - O busca la sección de "Activity Log"

3. **Filtra por Telegram**
   - En la tabla de logs, busca la columna "Log Name"
   - Filtra por: `telegram`

4. **Revisa los detalles**
   - Haz clic en cualquier log para ver detalles completos
   - Verás:
     - Descripción de la acción
     - Usuario que la realizó
     - Fecha y hora
     - Propiedades adicionales (JSON)

---

## 🗄️ Opción 2: Verificar Directamente en la Base de Datos

### Consultas SQL Útiles:

#### **Ver todos los logs de Telegram (últimos 20)**
```sql
SELECT 
    id,
    log_name,
    description,
    causer_id,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
ORDER BY created_at DESC 
LIMIT 20;
```

#### **Ver logs de un usuario específico**
```sql
SELECT 
    id,
    log_name,
    description,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
AND causer_id = 1  -- Reemplaza con el ID del usuario
ORDER BY created_at DESC 
LIMIT 50;
```

#### **Ver logs de un comando específico**
```sql
SELECT 
    id,
    description,
    causer_id,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
AND JSON_EXTRACT(properties, '$.command') = 'help'
ORDER BY created_at DESC;
```

#### **Ver logs por tipo de acción**
```sql
SELECT 
    id,
    description,
    created_at,
    JSON_EXTRACT(properties, '$.action') as action_type,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
AND JSON_EXTRACT(properties, '$.action') = 'keyboard_button'
ORDER BY created_at DESC;
```

#### **Ver intentos de acceso sin autenticación**
```sql
SELECT 
    id,
    description,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
AND description LIKE '%sin autenticación%'
ORDER BY created_at DESC;
```

#### **Contar actividades por comando**
```sql
SELECT 
    JSON_EXTRACT(properties, '$.command') as comando,
    COUNT(*) as total,
    MAX(created_at) as ultima_actividad
FROM activity_log 
WHERE log_name = 'telegram' 
GROUP BY JSON_EXTRACT(properties, '$.command')
ORDER BY total DESC;
```

#### **Ver actividades por usuario de Telegram**
```sql
SELECT 
    JSON_EXTRACT(properties, '$.telegram_user.username') as telegram_user,
    COUNT(*) as total_actividades,
    MAX(created_at) as ultima_actividad
FROM activity_log 
WHERE log_name = 'telegram' 
GROUP BY JSON_EXTRACT(properties, '$.telegram_user.username')
ORDER BY total_actividades DESC;
```

---

## 📱 Opción 3: Prueba Manual en Tiempo Real

### Pasos para probar:

1. **Abre el bot de Telegram**
   - Busca tu bot en Telegram
   - O abre el chat existente

2. **Ejecuta diferentes acciones:**

   **Comando de texto:**
   ```
   /help
   ```
   
   **Presiona botones:**
   - 📊 Estadísticas
   - ❓ Ayuda
   - 📍 Parroquia Sabana Libre
   
   **Búsqueda inline:**
   - En cualquier chat, escribe: `@nombre_del_bot beneficiario`

3. **Verifica inmediatamente en el panel**
   - Ve a Logs de Actividad
   - Filtra por `telegram`
   - Deberías ver tus acciones registradas

---

## 🎯 Qué Deberías Ver Registrado

### Después de ejecutar `/help`:
```
Consultó la ayuda del bot [Usuario Nombre | @username | TG:123456789]
```

### Después de presionar "📊 Estadísticas":
```
Comando ejecutado: stats
```

### Después de presionar "📍 Parroquia Sabana Libre":
```
Accedió a parroquia: Sabana Libre
```

### Después de una búsqueda inline:
```
Búsqueda inline realizada
```

---

## 📈 Análisis de Logs

### Estadísticas Útiles:

#### **Actividad por hora**
```sql
SELECT 
    DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as hora,
    COUNT(*) as actividades
FROM activity_log 
WHERE log_name = 'telegram' 
AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')
ORDER BY hora DESC;
```

#### **Comandos más utilizados**
```sql
SELECT 
    JSON_EXTRACT(properties, '$.command') as comando,
    COUNT(*) as usos
FROM activity_log 
WHERE log_name = 'telegram' 
GROUP BY JSON_EXTRACT(properties, '$.command')
ORDER BY usos DESC;
```

#### **Usuarios más activos**
```sql
SELECT 
    u.name,
    u.email,
    COUNT(al.id) as actividades_telegram
FROM activity_log al
JOIN users u ON al.causer_id = u.id
WHERE al.log_name = 'telegram'
GROUP BY al.causer_id
ORDER BY actividades_telegram DESC;
```

#### **Actividad en los últimos 7 días**
```sql
SELECT 
    DATE(created_at) as fecha,
    COUNT(*) as actividades
FROM activity_log 
WHERE log_name = 'telegram' 
AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY DATE(created_at)
ORDER BY fecha DESC;
```

---

## 🚨 Monitoreo de Errores

### Ver errores del bot:
```sql
SELECT 
    id,
    description,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
AND description LIKE '%error%'
ORDER BY created_at DESC;
```

### Ver intentos fallidos de login:
```sql
SELECT 
    id,
    description,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
AND JSON_EXTRACT(properties, '$.reason') = 'invalid_credentials'
ORDER BY created_at DESC;
```

---

## 💾 Exportar Logs

### Exportar a CSV:
```sql
SELECT 
    id,
    log_name,
    description,
    causer_id,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
INTO OUTFILE '/tmp/telegram_logs.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n';
```

### Exportar a JSON (desde PHP):
```php
// En un controlador o comando
$logs = \Spatie\Activitylog\Models\Activity::where('log_name', 'telegram')
    ->orderBy('created_at', 'desc')
    ->get();

return response()->json($logs);
```

---

## 🔔 Configurar Alertas

### Crear alerta para múltiples intentos fallidos:

```php
// En un comando o job
$failedAttempts = \Spatie\Activitylog\Models\Activity::where('log_name', 'telegram')
    ->where('properties->reason', 'invalid_credentials')
    ->where('created_at', '>=', now()->subHour())
    ->count();

if ($failedAttempts > 5) {
    // Enviar alerta
    \Log::warning("Múltiples intentos fallidos de login en Telegram: {$failedAttempts}");
}
```

---

## 📋 Checklist de Verificación

- [ ] Ejecuté `/help` y se registró en los logs
- [ ] Presioné "📊 Estadísticas" y se registró
- [ ] Presioné "❓ Ayuda" y se registró
- [ ] Presioné una parroquia y se registró
- [ ] Ejecuté una búsqueda inline y se registró
- [ ] Intenté acceder sin autenticación y se registró
- [ ] Puedo ver todos los logs en el panel administrativo
- [ ] Las consultas SQL funcionan correctamente
- [ ] Los datos se ven completos y detallados

---

## 🆘 Solución de Problemas

### **Problema: No veo logs de Telegram**

**Solución:**
1. Verifica que el bot esté funcionando
2. Ejecuta una acción en el bot
3. Espera 2-3 segundos
4. Recarga la página de logs
5. Asegúrate de filtrar por `log_name = 'telegram'`

### **Problema: Los logs no tienen detalles**

**Solución:**
1. Verifica que el usuario esté autenticado
2. Revisa que el comando tenga el logging implementado
3. Comprueba que `LogsActivity` esté siendo usado

### **Problema: Las consultas SQL no funcionan**

**Solución:**
1. Verifica que la tabla `activity_log` exista
2. Comprueba que el campo `properties` sea JSON
3. Usa `phpMyAdmin` o `MySQL Workbench` para verificar

---

## 📞 Soporte

Si encuentras problemas:
1. Revisa los logs de Laravel en `storage/logs/laravel.log`
2. Verifica que Spatie Activity Log esté instalado
3. Comprueba que las migraciones se hayan ejecutado
4. Contacta al administrador del sistema

---

**Última actualización:** 18 de Noviembre, 2025
