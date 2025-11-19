#!/bin/bash

echo "🚀 Iniciando aplicación..."

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Limpiar y cachear configuración
echo "🔧 Optimizando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico de storage
php artisan storage:link

# Configurar webhook de Telegram automáticamente
echo "🤖 Configurando webhook de Telegram..."
php artisan telegram:setup-webhook

# Iniciar servidor PHP
echo "✅ Aplicación lista!"
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
