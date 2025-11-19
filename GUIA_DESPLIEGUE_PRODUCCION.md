# 🚀 Guía de Despliegue a Producción

**Sistema Web de Gestión de la Alcaldía del Municipio Escuque**

---

## ✅ Estado Actual del Sistema

### Lo que YA está listo:
- ✅ Código base funcional y probado
- ✅ Assets compilados (CSS/JS)
- ✅ Sistema de autenticación
- ✅ Bot de Telegram funcional
- ✅ Generación de PDFs
- ✅ Sistema de logs
- ✅ Paginación implementada
- ✅ Búsqueda de beneficiarios
- ✅ Geolocalización con Google Maps

---

## ⚠️ Ajustes Necesarios ANTES de Producción

### 1. **Configuración de .env**

Actualiza estos valores en `.env`:

```env
# AMBIENTE - Cambiar a producción
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

# BASE DE DATOS - Cambiar de SQLite a MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario_db
DB_PASSWORD=contraseña_segura

# CACHE - Mejorar rendimiento
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# REDIS (recomendado para producción)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# EMAIL - Configurar SMTP real
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="Sistema Alcaldía Escuque"

# TELEGRAM - Ya configurado
TELEGRAM_BOT_TOKEN=tu_token_actual
TELEGRAM_BOT_NAME="Escuque Bot"

# GOOGLE MAPS - Verificar API Key
GOOGLE_MAPS_API_KEY=tu_api_key_actual
```

---

### 2. **Actualizar .env.example**

Ejecuta:
```bash
php artisan env:example
```

O actualiza manualmente el `.env.example` con las variables necesarias (sin valores sensibles).

---

### 3. **Compilar Assets para Producción**

```bash
npm run build
```

Esto optimiza CSS/JS para máximo rendimiento.

---

### 4. **Optimizar Laravel**

```bash
# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache

# Optimizar autoload
composer install --optimize-autoloader --no-dev
```

---

### 5. **Configurar Permisos en el Servidor**

```bash
# Dar permisos a storage y bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Cambiar propietario (ajustar según tu servidor)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

### 6. **Configurar HTTPS/SSL**

- ✅ Obtener certificado SSL (Let's Encrypt gratis)
- ✅ Configurar redirección HTTP → HTTPS
- ✅ Actualizar `APP_URL` en `.env`

---

### 7. **Configurar Servidor Web**

#### Para Apache (.htaccess):
El proyecto ya incluye `.htaccess` en `/public`

#### Para Nginx:
```nginx
server {
    listen 80;
    server_name tudominio.com;
    root /ruta/al/proyecto/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

### 8. **Configurar Cola de Trabajos (Queue)**

```bash
# Instalar Supervisor
sudo apt-get install supervisor

# Crear archivo de configuración
sudo nano /etc/supervisor/conf.d/escuque-worker.conf
```

Contenido:
```ini
[program:escuque-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/al/proyecto/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/ruta/al/proyecto/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Recargar Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start escuque-worker:*
```

---

### 9. **Configurar Cron Jobs**

```bash
# Editar crontab
crontab -e

# Agregar esta línea:
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

---

### 10. **Bot de Telegram en Producción**

Tienes 2 opciones:

#### Opción A: Polling (Actual)
Ejecutar con Supervisor:
```ini
[program:telegram-bot]
process_name=%(program_name)s
command=php /ruta/al/proyecto/artisan telegram:polling
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/ruta/al/proyecto/storage/logs/telegram.log
```

#### Opción B: Webhook (Recomendado para producción)
```bash
# Configurar webhook
php artisan telegram:set-webhook https://tudominio.com/telegram/webhook
```

---

### 11. **Seguridad Adicional**

```env
# En .env
APP_DEBUG=false
APP_ENV=production

# Generar nueva APP_KEY
php artisan key:generate
```

Actualizar `.htaccess` o Nginx para:
- ✅ Bloquear acceso a `.env`
- ✅ Bloquear acceso a carpetas sensibles
- ✅ Habilitar CORS si es necesario

---

### 12. **Base de Datos en Producción**

```bash
# Migrar base de datos
php artisan migrate --force

# Sembrar datos iniciales
php artisan db:seed --class=SettingsSeeder
php artisan db:seed --class=RolesAndPermissionsSeeder

# NO ejecutar DatabaseSeeder completo en producción
```

---

### 13. **Monitoreo y Logs**

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Ver logs de Telegram
tail -f storage/logs/telegram.log

# Ver logs de workers
tail -f storage/logs/worker.log
```

Configurar rotación de logs en `/etc/logrotate.d/escuque`

---

### 14. **Backup Automático**

Crear script de backup:
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/ruta/backups"

# Backup Base de Datos
mysqldump -u usuario -p contraseña nombre_db > "$BACKUP_DIR/db_$DATE.sql"

# Backup Archivos
tar -czf "$BACKUP_DIR/files_$DATE.tar.gz" /ruta/al/proyecto/storage

# Eliminar backups antiguos (más de 7 días)
find $BACKUP_DIR -type f -mtime +7 -delete
```

Configurar en cron:
```bash
0 2 * * * /ruta/al/backup.sh
```

---

## 📋 Checklist Final Pre-Producción

- [ ] `.env` configurado correctamente
- [ ] Base de datos MySQL configurada
- [ ] Redis instalado y configurado
- [ ] SSL/HTTPS habilitado
- [ ] Assets compilados (`npm run build`)
- [ ] Configuración cacheada
- [ ] Permisos correctos en storage/
- [ ] Cron jobs configurados
- [ ] Queue workers corriendo
- [ ] Bot de Telegram funcionando
- [ ] Logs monitoreados
- [ ] Backups configurados
- [ ] Google Maps API Key válida
- [ ] Email SMTP configurado
- [ ] Firewall configurado
- [ ] Sistema probado en staging

---

## 🔄 Comandos de Despliegue

Script completo para actualizar en producción:

```bash
#!/bin/bash
# deploy.sh

echo "🚀 Iniciando despliegue..."

# Modo mantenimiento
php artisan down

# Actualizar código
git pull origin main

# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Migrar base de datos
php artisan migrate --force

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cachear optimizaciones
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compilar assets
npm install
npm run build

# Reiniciar workers
php artisan queue:restart

# Salir de mantenimiento
php artisan up

echo "✅ Despliegue completado"
```

---

## 🌐 Proveedores de Hosting Recomendados

### Opción 1: VPS (Control Total)
- **DigitalOcean** - $6/mes
- **Vultr** - $6/mes
- **Linode** - $5/mes

### Opción 2: Hosting Compartido (Más Fácil)
- **Hostinger** - Laravel optimizado
- **SiteGround** - Soporte Laravel
- **A2 Hosting** - Buen rendimiento

### Opción 3: Cloud (Escalable)
- **AWS Lightsail** - $5/mes
- **Google Cloud Platform**
- **Azure**

---

## ⚡ Requisitos del Servidor

**Mínimos:**
- PHP 8.1+
- MySQL 8.0+ o MariaDB 10.3+
- Nginx o Apache
- Composer
- Node.js 18+
- Redis (opcional pero recomendado)

**Recomendados:**
- PHP 8.2
- MySQL 8.0
- Nginx
- Redis
- Supervisor
- 2GB RAM mínimo
- 20GB almacenamiento

---

## 📞 Soporte

Para más información:
- Email: ag@gmail.com
- Documentación: Ver archivos MD en el proyecto

---

**¡Sistema listo para producción con esta guía! 🎉**
