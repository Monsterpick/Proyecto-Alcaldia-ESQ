#!/bin/bash

echo "🚀 Iniciando aplicación Laravel..."

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders solo la primera vez (si no existe archivo flag)
FLAG_FILE="/var/www/storage/app/.seeders_executed"

if [ ! -f "$FLAG_FILE" ]; then
    echo "🌱 Primera inicialización - Cargando datos del sistema..."
    
    # Seeders base
    php artisan db:seed --class=PermissionSeeder --force
    php artisan db:seed --class=RoleSeeder --force
    php artisan db:seed --class=SettingsSeeder --force
    
    # Datos geográficos
    php artisan db:seed --class=EstadoSeeder --force
    php artisan db:seed --class=MunicipioSeeder --force
    php artisan db:seed --class=ParroquiaSeeder --force
    php artisan db:seed --class=CircuitoComunalSeeder --force
    
    # Catálogos
    php artisan db:seed --class=EstatusSeeder --force
    php artisan db:seed --class=PaymentTypeSeeder --force
    php artisan db:seed --class=PaymentOriginSeeder --force
    php artisan db:seed --class=CategorySeeder --force
    php artisan db:seed --class=WarehouseSeeder --force
    
    # Usuario Super Admin
    php artisan db:seed --class=SuperAdminSeeder --force
    
    # Crear archivo flag para no volver a ejecutar
    touch "$FLAG_FILE"
    echo "✅ Datos iniciales cargados correctamente"
else
    echo "⏭️ Base de datos ya inicializada, omitiendo seeders"
fi

# Optimizar para producción
echo "🔧 Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico de storage
echo "📁 Configurando storage..."
php artisan storage:link

# Configurar webhook de Telegram
echo "🤖 Configurando webhook de Telegram..."
php artisan telegram:setup-webhook

# Configurar Nginx para el puerto de Railway
echo "🌐 Configurando Nginx..."
sed -i "s/listen 8080;/listen ${PORT:-8080};/" /etc/nginx/sites-available/default

# Iniciar PHP-FPM en background
echo "⚙️ Iniciando PHP-FPM..."
php-fpm -D

# Iniciar Nginx en foreground
echo "✅ Iniciando Nginx..."
nginx -g "daemon off;"
