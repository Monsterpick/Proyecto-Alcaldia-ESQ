# 🎉 Instrucciones Finales - Control de beneficios 1X10 Escuque

## ✅ Cambios Completados

Se han realizado los siguientes cambios en el sistema para transformarlo de un sistema de óptica a un sistema de control de beneficios sociales:

### 1. **Branding y Nomenclatura**
- ✅ Nombre del sistema actualizado a "Control de beneficios 1X10 Escuque"
- ✅ Razón social actualizada
- ✅ Descripción del sistema adaptada a beneficios sociales
- ✅ Dominio cambiado a `escuque.nevora.app`
- ✅ Actividad empresarial actualizada
- ✅ Servicios redefinidos para contexto social

### 2. **Roles del Sistema**
Se actualizaron los roles de:
- ❌ ~~Paciente~~ → ✅ **Beneficiario**
- ❌ ~~Doctor~~ → ✅ **Coordinador**
- ❌ ~~Recepcionista~~ → ✅ **Operador**
- ✅ **Administrador** (sin cambios)
- ✅ **Super Admin** (sin cambios)

### 3. **Archivos Modificados**

#### Configuración
- `composer.json` - Nombre y descripción del proyecto
- `.env.example` - APP_NAME actualizado
- `comandos.txt` - Dominio actualizado

#### Seeders
- `database/seeders/SettingsSeeder.php` - Configuración de empresa
- `database/seeders/RoleSeeder.php` - Roles actualizados
- `database/seeders/PermissionSeeder.php` - Permisos adicionales

#### Vistas
- `resources/views/welcome.blade.php` - Meta tags y SEO
- `resources/views/components/app-logo.blade.php` - Logo dinámico
- `resources/views/components/layouts/app/header.blade.php` - Header actualizado
- `resources/views/components/layouts/app/sidebar.blade.php` - Sidebar actualizado
- `resources/views/livewire/auth/login.blade.php` - Roles de login
- `resources/views/livewire/pages/admin/auth/login.blade.php` - Roles admin

#### Documentación
- `README.md` - Documentación completa del proyecto

---

## 🚀 Pasos para Aplicar los Cambios

### **Paso 1: Actualizar el archivo .env**

Edita tu archivo `.env` (si no existe, copia desde `.env.example`):

```bash
cp .env.example .env
```

Luego edita `.env` y actualiza:

```env
APP_NAME="Control de beneficios 1X10 Escuque"
APP_URL=http://localhost

# Si usas un dominio específico:
# APP_URL=https://escuque.nevora.app
```

### **Paso 2: Limpiar caché de configuración**

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### **Paso 3: Refrescar la base de datos**

⚠️ **ADVERTENCIA**: Este comando **BORRARÁ TODOS LOS DATOS** existentes.

```bash
php artisan migrate:fresh --seed
```

Si ya tienes datos en producción y solo quieres actualizar los settings, ejecuta estos SQL manualmente:

```sql
-- Actualizar configuración de la empresa
UPDATE settings SET value = 'Control de beneficios 1X10 Escuque' WHERE key = 'name';
UPDATE settings SET value = 'Control de beneficios 1X10 Escuque' WHERE key = 'razon_social';
UPDATE settings SET value = 'Sistema de control de beneficios sociales' WHERE key = 'description';
UPDATE settings SET value = 'Control de beneficios 1X10 Escuque es un sistema integral para la gestión y control de beneficios sociales. Facilitamos la administración eficiente de programas de ayuda social, garantizando transparencia y acceso equitativo a los beneficiarios.' WHERE key = 'long_description';
UPDATE settings SET value = 'escuque.nevora.app' WHERE key = 'domain';
UPDATE settings SET value = 'Control de Beneficios Sociales' WHERE key = 'actividad';

-- Actualizar roles
UPDATE roles SET name = 'Beneficiario' WHERE name = 'Paciente';
UPDATE roles SET name = 'Coordinador' WHERE name = 'Doctor';
UPDATE roles SET name = 'Operador' WHERE name = 'Recepcionista';

-- Actualizar permisos si es necesario
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('view-setting', 'web', NOW(), NOW()),
('create-setting', 'web', NOW(), NOW()),
('edit-setting', 'web', NOW(), NOW()),
('delete-setting', 'web', NOW(), NOW()),
('download-setting', 'web', NOW(), NOW()),
('view-direccion', 'web', NOW(), NOW()),
('view-general', 'web', NOW(), NOW()),
('profile-setting', 'web', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=name;
```

### **Paso 4: Recompilar assets del frontend**

```bash
npm install
npm run build
```

Para desarrollo:
```bash
npm run dev
```

### **Paso 5: Optimizar para producción (opcional)**

Si estás en producción:

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### **Paso 6: Reiniciar servicios**

```bash
# Reiniciar queue workers
php artisan queue:restart

# Si usas supervisor, reinicia el servicio
# sudo supervisorctl restart all
```

---

## 🔍 Verificación

### 1. **Verificar la página de inicio**
Visita `http://localhost:8000` y verifica que:
- El título muestra "Control de beneficios 1X10 Escuque"
- Los meta tags están actualizados
- El footer muestra el nombre correcto

### 2. **Verificar el panel de administración**
1. Inicia sesión con:
   - Email: `ag@gmail.com`
   - Password: `1234`

2. Verifica que:
   - El logo muestra el nombre correcto
   - Los roles están actualizados
   - La configuración general muestra los datos correctos

### 3. **Verificar la base de datos**
```bash
php artisan tinker
```

Luego ejecuta:
```php
// Verificar settings
App\Models\Setting::where('key', 'name')->first()->value;
// Debe mostrar: "Control de beneficios 1X10 Escuque"

// Verificar roles
Spatie\Permission\Models\Role::pluck('name');
// Debe incluir: Beneficiario, Coordinador, Operador
```

---

## 📝 Tareas Pendientes (Opcional)

Estos cambios son opcionales y dependen de tus necesidades:

### 1. **Actualizar Modelos y Tablas**
Si quieres renombrar completamente los modelos:
- `Patient` → `Beneficiary` (Beneficiario)
- `Doctor` → `Coordinator` (Coordinador)
- `Appointment` → `Benefit` o `Assistance` (Beneficio/Asistencia)

Esto requiere:
- Crear nuevas migraciones
- Renombrar modelos
- Actualizar relaciones
- Actualizar controladores y vistas

### 2. **Actualizar Rutas y Permisos**
Revisar y actualizar:
- Rutas en `routes/admin.php`
- Permisos relacionados con pacientes/doctores
- Middleware de autorización

### 3. **Personalizar el Logo**
Reemplazar el archivo de logo en `public/`:
- `logo_ag.png` (logo principal para navegación y dashboard)
- Favicons en `public/favicons/` (opcional)

### 4. **Actualizar Emails y Notificaciones**
Revisar plantillas de correo en:
- `resources/views/emails/`
- Notificaciones en `app/Notifications/`

### 5. **Configurar Datos de la Empresa**
Desde el panel admin:
1. Ve a **Configuración → General**
2. Actualiza:
   - RIF
   - Dirección fiscal
   - Teléfonos
   - Emails
   - Ubicación (latitud/longitud)
   - Horario de atención

3. Ve a **Configuración → Logos**
4. Sube los logos de tu organización

---

## 🆘 Solución de Problemas

### Error: "Class not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

### Error: "View not found"
```bash
php artisan view:clear
```

### Error: "Permission denied"
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (ejecutar como administrador)
icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
```

### La página no carga los estilos
```bash
npm run build
php artisan optimize:clear
```

### Los cambios no se reflejan
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
# Luego refresca el navegador con Ctrl+F5
```

---

## 📞 Soporte

Si encuentras problemas:
1. Revisa los logs en `storage/logs/laravel.log`
2. Ejecuta `php artisan pail` para ver logs en tiempo real
3. Contacta al equipo de desarrollo:
   - silvio.ramirez.m@gmail.com
   - jhonnytorresforro@gmail.com

---

## ✨ ¡Listo!

Una vez completados estos pasos, tu sistema estará completamente transformado de un sistema de óptica a un sistema de control de beneficios sociales.

**Solo necesitas refrescar la base de datos y ver los cambios en acción.**

```bash
# Comando final para aplicar todo
php artisan migrate:fresh --seed && npm run build && php artisan optimize:clear
```

Luego visita: `http://localhost:8000`

---

**Control de beneficios 1X10 Escuque** - Sistema de gestión de beneficios sociales
Desarrollado por AG 1.0
