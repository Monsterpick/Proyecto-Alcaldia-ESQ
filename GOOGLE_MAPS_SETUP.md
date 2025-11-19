# 🗺️ Mapa de Geolocalización con Leaflet + OpenStreetMap

## ✅ **¡100% GRATIS! Sin necesidad de API Keys ni tarjetas bancarias**

Este sistema utiliza:
- **Leaflet.js** - Librería JavaScript Open Source para mapas interactivos
- **OpenStreetMap** - Mapas gratuitos mantenidos por la comunidad

## 🚀 **¡Ya está listo para usar!**

No necesitas configurar nada adicional. El mapa funciona inmediatamente.

### **Cómo acceder:**

1. Inicia sesión en el sistema
2. Ve a la URL:
   ```
   http://localhost/map
   ```
   O si tu proyecto está en un subdirectorio:
   ```
   http://localhost/tu-proyecto/public/map
   ```

## 🎨 **Características del mapa:**
- Haz clic en la API Key recién creada
- En "Restricciones de aplicación":
  - Selecciona **Referentes HTTP (sitios web)**
  - Agrega tu dominio:
    ```
    localhost
    127.0.0.1
    tu-dominio.com
    *.tu-dominio.com
    ```
- En "Restricciones de API":
  - Selecciona **Restringir clave**
  - Marca **Maps JavaScript API**
- Haz clic en **GUARDAR**

### 6. **Copiar la API Key**
- Copia tu API Key (se ve así: `AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`)

---

## 🔧 Configurar en tu proyecto:

### Opción 1: Directamente en la vista (para desarrollo)

Abre el archivo: `resources/views/map/index.blade.php`

Busca esta línea (al final del archivo):
```html
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">
</script>
```

Reemplaza `YOUR_API_KEY` con tu API Key:
```html
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxxxxxxxxxxxxxxxxxxxxxx&callback=initMap">
</script>
```

### Opción 2: Usar variable de entorno (RECOMENDADO para producción)

1. Abre tu archivo `.env`

2. Agrega esta línea:
```env
GOOGLE_MAPS_API_KEY=AIzaSyBxxxxxxxxxxxxxxxxxxxxxx
```

3. Modifica el archivo `resources/views/map/index.blade.php`:

Cambia:
```html
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">
</script>
```

Por:
```html
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
</script>
```

---

## 🌍 Ajustar coordenadas de las parroquias (IMPORTANTE)

Las coordenadas en el archivo `MapController.php` son aproximadas. Para mayor precisión:

### Opción 1: Obtener coordenadas manualmente
1. Ve a: https://www.google.com/maps
2. Busca cada parroquia: "Escuque, Trujillo, Venezuela"
3. Haz clic derecho en el centro de la parroquia
4. Selecciona las coordenadas que aparecen (ej: 9.3114, -70.7592)
5. Actualiza en `app/Http/Controllers/MapController.php`

### Opción 2: Usar las coordenadas desde la base de datos

Si tienes las coordenadas en tu tabla de parroquias:

1. Agrega columnas `latitude` y `longitude` a la tabla `parishes` si no existen:
```bash
php artisan make:migration add_coordinates_to_parishes_table
```

2. En la migración:
```php
public function up()
{
    Schema::table('parishes', function (Blueprint $table) {
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
    });
}
```

3. Ejecuta la migración:
```bash
php artisan migrate
```

4. Modifica el `MapController.php` para obtener coordenadas desde la BD

---

## 📊 Acceder al mapa:

Una vez configurado, accede a:

```
http://localhost/map
```

O si tu proyecto está en un subdirectorio:

```
http://localhost/tu-proyecto/public/map
```

---

## ⚠️ Solución de problemas:

### Error: "This API project is not authorized to use this API"
- Ve a Google Cloud Console → APIs y servicios → Biblioteca
- Habilita **Maps JavaScript API**

### El mapa no se muestra (pantalla gris)
- Verifica que la API Key esté correctamente configurada
- Revisa la consola del navegador (F12) para ver errores
- Verifica que hayas habilitado la facturación en Google Cloud (gratuita hasta cierto límite)

### Error de facturación
- Google Maps requiere una cuenta de facturación activa
- NO TE PREOCUPES: Google ofrece $200 USD de crédito mensual GRATIS
- Es muy difícil superar ese límite con un proyecto pequeño
- Configura alertas de facturación para estar seguro

---

## 💡 Notas importantes:

- ✅ El uso básico de Google Maps es **GRATIS** ($200/mes de crédito)
- ✅ Siempre RESTRINGE tu API Key por seguridad
- ✅ Las coordenadas actuales son aproximadas, ajústalas según necesites
- ✅ Puedes personalizar los colores y estilos del mapa en el JavaScript

---

## 📞 ¿Necesitas ayuda?

Si tienes problemas con la configuración, verifica:
1. La API Key está correctamente copiada (sin espacios)
2. Maps JavaScript API está habilitada
3. La facturación está activada en Google Cloud
4. No hay errores en la consola del navegador (F12)

---

¡Listo! Tu mapa interactivo estará funcionando. 🎉
