#!/bin/bash

echo "🚀 Iniciando aplicación Laravel..."

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders base (permisos, roles, configuración)
echo "🌱 Ejecutando seeders del sistema..."
php artisan db:seed --class=PermissionSeeder --force
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=SettingsSeeder --force

# Seeders de datos geográficos
echo "🗺️ Cargando datos geográficos..."
php artisan db:seed --class=EstadoSeeder --force
php artisan db:seed --class=MunicipioSeeder --force
php artisan db:seed --class=ParroquiaSeeder --force
php artisan db:seed --class=CircuitoComunalSeeder --force

# Seeders de catálogos
echo "📋 Cargando catálogos..."
php artisan db:seed --class=EstatusSeeder --force
php artisan db:seed --class=PaymentTypeSeeder --force
php artisan db:seed --class=PaymentOriginSeeder --force
php artisan db:seed --class=CategorySeeder --force
php artisan db:seed --class=WarehouseSeeder --force

# Crear usuario Super Admin
echo "👤 Creando usuario Super Admin..."
php artisan db:seed --class=SuperAdminSeeder --force

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
