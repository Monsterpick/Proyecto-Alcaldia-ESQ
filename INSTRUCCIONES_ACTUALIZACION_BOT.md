# 🚀 Instrucciones de Actualización del Bot de Telegram

## ✅ Verificación Previa

Antes de iniciar el bot, verifica que todo está en orden:

### 1. **Verificar Configuración del .env**

Abre el archivo `.env` y confirma que tienes:

```env
TELEGRAM_BOT_TOKEN=tu_token_aqui
TELEGRAM_WEBHOOK_URL=https://tu-dominio.com/api/telegram/webhook
```

### 2. **Limpiar Cache de Laravel**

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## 🔄 Opciones de Actualización

Tienes dos opciones para ejecutar el bot:

### **Opción 1: Polling (Desarrollo/Local)**

Recomendado para desarrollo y pruebas locales.

```bash
# Detener cualquier polling anterior (Ctrl+C si está corriendo)

# Iniciar nuevo polling
php artisan telegram:polling
```

**Ventajas:**
- No requiere HTTPS
- Fácil de probar localmente
- Ver logs en tiempo real

**Desventajas:**
- Debe estar corriendo constantemente
- No recomendado para producción

---

### **Opción 2: Webhook (Producción)**

Recomendado para producción.

#### Paso 1: Configurar el Webhook

```bash
# Desde la línea de comandos de Laravel
php artisan tinker
```

Luego dentro de Tinker:

```php
// Configurar webhook
Telegram::setWebhook([
    'url' => 'https://tu-dominio.com/api/telegram/webhook'
]);

// Verificar que se configuró correctamente
$info = Telegram::getWebhookInfo();
dd($info);

// Salir de tinker
exit
```

#### Paso 2: Verificar el Webhook

```bash
php artisan tinker
```

```php
// Ver información del webhook actual
$info = Telegram::getWebhookInfo();
print_r($info);

exit
```

**Deberías ver algo como:**
```
url: https://tu-dominio.com/api/telegram/webhook
has_custom_certificate: false
pending_update_count: 0
```

---

## 🧪 Probar el Bot

### 1. **Comandos Básicos**

Abre Telegram y envía estos comandos al bot:

```
/start
```
✅ **Debe mostrar:** Mensaje de bienvenida con botones de parroquias

```
/menu
```
✅ **Debe mostrar:** Menú principal con descripción de parroquias

```
/stats
```
✅ **Debe mostrar:** Estadísticas globales con gráficos

```
/help
```
✅ **Debe mostrar:** 7 mensajes con guía completa

---

### 2. **Probar Navegación por Parroquias**

#### Test 1: Parroquia Sabana Libre
1. Presiona el botón `📍 Parroquia Sabana Libre`
2. ✅ Debe aparecer mensaje con 4 botones numerados
3. Presiona `1️⃣ Medicamentos`
4. ✅ Debe mostrar reportes de medicamentos de Sabana Libre

#### Test 2: Estadísticas por Parroquia
1. Presiona `📍 Parroquia La Unión`
2. Presiona `4️⃣ Estadísticas`
3. ✅ Debe mostrar estadísticas SOLO de La Unión
4. ✅ Debe mostrar gráficos específicos de esa parroquia

#### Test 3: Otras Categorías
1. Presiona `📍 Parroquia Santa Rita`
2. Presiona `2️⃣ Ayudas Técnicas`
3. ✅ Debe mostrar reportes de "Apoyo Social" de Santa Rita
4. Vuelve a entrar
5. Presiona `3️⃣ Otros`
6. ✅ Debe mostrar reportes de otras categorías de Santa Rita

---

### 3. **Probar Estadísticas Globales**

1. Presiona el botón `📊 Estadísticas` del menú principal
2. ✅ Debe mostrar:
   - Resumen general de beneficiarios y reportes
   - Estadísticas desglosadas por cada parroquia
   - 3 gráficos:
     - Beneficiarios globales
     - Reportes globales
     - Comparación entre parroquias

---

### 4. **Probar Ayuda Completa**

1. Presiona el botón `❓ Ayuda`
2. ✅ Debe enviar 7 mensajes consecutivos:
   - Mensaje 1: Bienvenida y descripción
   - Mensaje 2: Navegación por parroquias
   - Mensaje 3: Estadísticas
   - Mensaje 4: Reportes por categoría
   - Mensaje 5: Búsqueda de beneficiarios
   - Mensaje 6: Comandos y botones
   - Mensaje 7: Tips y solución de problemas

---

## 🔍 Búsqueda Inline (Opcional)

Para probar la búsqueda inline:

1. En cualquier chat de Telegram
2. Escribe: `@nombre_de_tu_bot` + nombre o cédula
3. ✅ Debe aparecer lista de resultados
4. Toca un resultado
5. ✅ Se debe enviar la información del beneficiario

---

## 🐛 Solución de Problemas

### Problema: "El bot no responde"

**Solución:**

```bash
# 1. Verificar logs
tail -f storage/logs/laravel.log

# 2. Limpiar cache
php artisan config:clear
php artisan cache:clear

# 3. Reiniciar polling (si usas polling)
# Ctrl+C para detener
php artisan telegram:polling

# 4. Verificar webhook (si usas webhook)
php artisan tinker
Telegram::getWebhookInfo();
exit
```

---

### Problema: "Los botones no aparecen"

**Solución:**

1. En el chat del bot, presiona el ícono de teclado 🎹
2. Si no aparece, envía `/menu` o `/start`
3. Verifica que el código tiene `'persistent' => true` en los keyboards

---

### Problema: "Error al presionar botones inline"

**Solución:**

```bash
# Verificar logs en tiempo real
tail -f storage/logs/laravel.log

# Buscar errores relacionados con 'callback_query'
grep "callback" storage/logs/laravel.log
```

**Verifica que:**
- El callback_data tiene el formato correcto: `parish_{ParishName}_cat_{category}`
- El método `handleParishCallback` está procesando correctamente

---

### Problema: "No se muestran gráficos"

**Verificar:**

1. Conexión a internet (QuickChart requiere internet)
2. URL del gráfico es válida
3. Los datos no están vacíos

**Debug:**

```bash
# Ver URL del gráfico en logs
tail -f storage/logs/laravel.log | grep "quickchart"
```

---

### Problema: "Los reportes están vacíos"

**Verificar datos en la BD:**

```bash
php artisan tinker
```

```php
// Verificar reportes por parroquia
use App\Models\Report;

$reports = Report::where('parish', 'Sabana Libre')->count();
echo "Reportes en Sabana Libre: " . $reports . "\n";

// Verificar categorías
use App\Models\Category;
Category::all()->pluck('name');

exit
```

---

## 📊 Verificar Estado del Bot

### Script de Verificación Rápida

```bash
php artisan tinker
```

```php
use App\Models\Report;
use App\Models\Beneficiary;
use App\Models\Category;

// Verificar parroquias
$parishes = ['Sabana Libre', 'La Unión', 'Santa Rita', 'Escuque'];

foreach ($parishes as $parish) {
    $count = Report::where('parish', $parish)->count();
    echo "$parish: $count reportes\n";
}

// Verificar categorías
echo "\nCategorías disponibles:\n";
Category::all()->each(function($cat) {
    echo "- {$cat->name} (ID: {$cat->id})\n";
});

// Verificar beneficiarios por parroquia
echo "\nBeneficiarios por parroquia:\n";
foreach ($parishes as $parish) {
    $count = Beneficiary::whereHas('parroquia', function($q) use ($parish) {
        $q->where('parroquia', $parish);
    })->count();
    echo "$parish: $count beneficiarios\n";
}

exit
```

---

## 🔐 Seguridad

### Remover Webhook (si es necesario)

```bash
php artisan tinker
```

```php
Telegram::removeWebhook();
exit
```

### Ver Información del Bot

```bash
php artisan tinker
```

```php
$me = Telegram::getMe();
print_r($me);
exit
```

---

## 📝 Logs Importantes

### Ver logs en tiempo real

```bash
# Todos los logs
tail -f storage/logs/laravel.log

# Solo errores
tail -f storage/logs/laravel.log | grep ERROR

# Solo actividad de Telegram
tail -f storage/logs/laravel.log | grep Telegram
```

### Archivo de debug específico de Telegram

Si existe:
```bash
tail -f storage/logs/telegram_debug.txt
```

---

## ✅ Checklist Final

Antes de considerar la actualización completa:

- [ ] El bot responde a `/start`
- [ ] Se muestran los 6 botones del teclado
- [ ] Al presionar una parroquia aparecen los 4 botones numerados
- [ ] Al presionar un número se muestran los reportes correctos
- [ ] Las estadísticas globales funcionan
- [ ] Las estadísticas por parroquia funcionan
- [ ] Los gráficos se generan correctamente
- [ ] La ayuda muestra los 7 mensajes
- [ ] No hay errores en los logs
- [ ] La búsqueda inline funciona (opcional)

---

## 📞 Soporte

Si encuentras algún problema que no puedes resolver:

1. **Revisa los logs:** `storage/logs/laravel.log`
2. **Verifica la configuración:** `.env` y `config/telegram.php`
3. **Consulta la documentación:** `TELEGRAM_BOT_PARROQUIAS_CHANGELOG.md`
4. **Contacta al desarrollador**

---

**¡Todo listo! Tu bot ahora tiene el sistema de navegación por parroquias completamente funcional.** 🎉
