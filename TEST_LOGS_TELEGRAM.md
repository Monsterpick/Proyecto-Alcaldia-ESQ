# 🧪 Guía de Prueba - Verificar Logs del Bot de Telegram

---

## 📋 Checklist de Prueba Completa

### Fase 1: Preparación

- [ ] Accede al panel administrativo
- [ ] Ve a **Configuración → Logs de Actividad**
- [ ] Anota la hora actual
- [ ] Limpia los filtros (selecciona "Todos" en Tipo de Log)

---

### Fase 2: Pruebas de Comandos

#### Test 1: Comando `/help`
```
1. Abre el bot de Telegram
2. Escribe: /help
3. Espera 2 segundos
4. Ve al panel de logs
5. Filtra por: log_name = 'telegram'
6. Deberías ver: "Consultó la ayuda del bot"
```

**Resultado esperado:** ✅ Aparece en logs

---

#### Test 2: Botón "📊 Estadísticas"
```
1. En el bot, presiona el botón "📊 Estadísticas"
2. Espera 2 segundos
3. Ve al panel de logs
4. Filtra por: log_name = 'telegram'
5. Deberías ver: "Comando ejecutado: stats"
```

**Resultado esperado:** ✅ Aparece en logs

---

#### Test 3: Botón "❓ Ayuda"
```
1. En el bot, presiona el botón "❓ Ayuda"
2. Espera 2 segundos
3. Ve al panel de logs
4. Filtra por: log_name = 'telegram'
5. Deberías ver: "Comando ejecutado: help"
```

**Resultado esperado:** ✅ Aparece en logs

---

### Fase 3: Pruebas de Parroquias (CRÍTICA)

#### Test 4: Acceder a una Parroquia
```
1. En el bot, presiona "📍 Parroquia Sabana Libre"
2. Espera 2 segundos
3. Ve al panel de logs
4. Filtra por: log_name = 'telegram'
5. Deberías ver: "Accedió a parroquia: Sabana Libre"
```

**Resultado esperado:** ✅ Aparece en logs

---

#### Test 5: Consultar Reportes (EL PROBLEMA)
```
1. En el bot, presiona "📍 Parroquia Sabana Libre"
2. Presiona "1️⃣ Medicamentos"
3. Espera 3 segundos (puede tardar más)
4. Ve al panel de logs
5. Filtra por: log_name = 'telegram'
6. Deberías ver: "Consultó reportes de categoría: Medicamentos en parroquia: Sabana Libre"
```

**Resultado esperado:** ✅ Aparece en logs

---

#### Test 6: Consultar Estadísticas de Parroquia
```
1. En el bot, presiona "📍 Parroquia La Unión"
2. Presiona "4️⃣ Estadísticas"
3. Espera 3 segundos
4. Ve al panel de logs
5. Filtra por: log_name = 'telegram'
6. Deberías ver: "Consultó estadísticas de parroquia: La Unión"
```

**Resultado esperado:** ✅ Aparece en logs

---

### Fase 4: Pruebas de Búsqueda

#### Test 7: Búsqueda Inline
```
1. En cualquier chat de Telegram, escribe: @nombre_del_bot beneficiario
2. Presiona un resultado
3. Espera 2 segundos
4. Ve al panel de logs
5. Filtra por: log_name = 'telegram'
6. Deberías ver: "Búsqueda inline realizada"
```

**Resultado esperado:** ✅ Aparece en logs

---

### Fase 5: Pruebas de Errores

#### Test 8: Intento sin Autenticación
```
1. Abre un chat nuevo con el bot
2. Presiona "📍 Parroquia Sabana Libre" (sin estar autenticado)
3. Espera 2 segundos
4. Ve al panel de logs
5. Filtra por: log_name = 'telegram'
6. Deberías ver: "Intento de acceso sin autenticación"
```

**Resultado esperado:** ✅ Aparece en logs

---

## 🗄️ Consultas SQL para Verificar

### Ver todos los logs de Telegram
```sql
SELECT 
    id,
    description,
    created_at,
    properties
FROM activity_log 
WHERE log_name = 'telegram' 
ORDER BY created_at DESC 
LIMIT 20;
```

### Ver logs de reportes específicamente
```sql
SELECT 
    id,
    description,
    created_at,
    JSON_EXTRACT(properties, '$.action') as action,
    JSON_EXTRACT(properties, '$.category') as category
FROM activity_log 
WHERE log_name = 'telegram' 
AND description LIKE '%reportes%'
ORDER BY created_at DESC;
```

### Ver logs de parroquias
```sql
SELECT 
    id,
    description,
    created_at,
    JSON_EXTRACT(properties, '$.parish') as parish
FROM activity_log 
WHERE log_name = 'telegram' 
AND description LIKE '%parroquia%'
ORDER BY created_at DESC;
```

### Contar por tipo de acción
```sql
SELECT 
    JSON_EXTRACT(properties, '$.action') as action,
    COUNT(*) as total
FROM activity_log 
WHERE log_name = 'telegram' 
GROUP BY JSON_EXTRACT(properties, '$.action')
ORDER BY total DESC;
```

---

## 📊 Matriz de Resultados Esperados

| Test | Acción | Log Esperado | Estado |
|------|--------|--------------|--------|
| 1 | `/help` | "Consultó la ayuda del bot" | ✅ |
| 2 | "📊 Estadísticas" | "Comando ejecutado: stats" | ✅ |
| 3 | "❓ Ayuda" | "Comando ejecutado: help" | ✅ |
| 4 | Parroquia | "Accedió a parroquia: ..." | ✅ |
| 5 | Reportes | "Consultó reportes de categoría: ..." | ✅ |
| 6 | Estadísticas Parroquia | "Consultó estadísticas de parroquia: ..." | ✅ |
| 7 | Búsqueda Inline | "Búsqueda inline realizada" | ✅ |
| 8 | Sin Autenticación | "Intento de acceso sin autenticación" | ✅ |

---

## 🎯 Puntuación

- **8/8 tests pasados:** ✅ Sistema funcionando perfectamente
- **6-7 tests pasados:** ⚠️ Algunos comandos funcionan
- **4-5 tests pasados:** ⚠️ Problemas parciales
- **<4 tests pasados:** ❌ Problema crítico

---

## 🚨 Si Algo Falla

### Paso 1: Verificar que el bot responde
- El bot debe mostrar los reportes/estadísticas
- Si no muestra nada, hay un problema con el bot, no con los logs

### Paso 2: Verificar la base de datos
```sql
-- ¿Existe la tabla?
SHOW TABLES LIKE 'activity_log';

-- ¿Hay registros?
SELECT COUNT(*) FROM activity_log;

-- ¿Hay registros de Telegram?
SELECT COUNT(*) FROM activity_log WHERE log_name = 'telegram';
```

### Paso 3: Verificar los logs de Laravel
```bash
# Ver últimos 50 líneas
tail -50 storage/logs/laravel.log

# Ver en tiempo real
tail -f storage/logs/laravel.log
```

### Paso 4: Verificar que el usuario está autenticado
```sql
SELECT id, name, email, telegram_chat_id 
FROM users 
WHERE telegram_chat_id IS NOT NULL;
```

---

## 📝 Notas Importantes

1. **Espera 2-3 segundos** después de ejecutar una acción antes de revisar los logs
2. **Recarga la página** del panel de logs (F5)
3. **Asegúrate de filtrar** por `log_name = 'telegram'`
4. **Verifica la fecha** - los logs deben ser de hoy
5. **Si ves errores**, revisa `storage/logs/laravel.log`

---

## ✅ Resumen

Si todos los tests pasan, el sistema de logging está funcionando correctamente y registrará:
- ✅ Todos los comandos ejecutados
- ✅ Todos los botones presionados
- ✅ Todas las búsquedas realizadas
- ✅ Todos los intentos de acceso
- ✅ Todos los errores

---

**Última actualización:** 18 de Noviembre, 2025
