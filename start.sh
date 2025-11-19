#!/bin/bash

echo "🚀 Iniciando aplicación Laravel..."

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

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
