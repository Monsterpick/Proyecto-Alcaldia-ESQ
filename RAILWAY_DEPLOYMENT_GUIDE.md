# 🚂 Guía de Despliegue en Railway

## 📋 Pasos Rápidos

### 1️⃣ Subir Código a GitHub (Ya hecho ✅)

Tu código ya está en: `https://github.com/Monsterpick/Proyecto-Alcaldia-ESQ`

---

### 2️⃣ Crear Proyecto en Railway

1. **Ve a Railway:** https://railway.app
2. **Inicia sesión** con tu cuenta
3. **Click en "New Project"**
4. **Selecciona "Deploy from GitHub repo"**
5. **Busca y selecciona:** `Monsterpick/Proyecto-Alcaldia-ESQ`
6. **Click en "Deploy Now"**

---

### 3️⃣ Crear Base de Datos MySQL

1. **En tu proyecto de Railway**, click en **"+ New"**
2. **Selecciona "Database"** → **"Add MySQL"**
3. **Railway creará la base de datos automáticamente**
4. **Espera 30 segundos** a que se cree

---

### 4️⃣ Configurar Variables de Entorno

1. **Click en tu servicio** (el que tiene tu código)
2. **Ve a la pestaña "Variables"**
3. **Agrega estas variables** (copia y pega):

```env
# ==========================================
# CONFIGURACIÓN DE LA APLICACIÓN
# ==========================================
APP_NAME="Sistema Web de Gestion de la Alcaldia del Municipio Escuque"
APP_ENV=production
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

# ==========================================
# APP_KEY - GENERAR AUTOMÁTICAMENTE
# ==========================================
# Railway genera esto automáticamente al detectar Laravel
# Si no, usa: php artisan key:generate --show

# ==========================================
# BASE DE DATOS - USAR VARIABLES DE RAILWAY
# ==========================================
DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

# ==========================================
# SESIÓN Y CACHÉ
# ==========================================
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# ==========================================
# TELEGRAM BOT - IMPORTANTE ⚠️
# ==========================================
TELEGRAM_BOT_TOKEN=TU_TOKEN_DE_TELEGRAM_AQUI
TELEGRAM_BOT_NAME="Escuque Bot"
TELEGRAM_ASYNC_REQUESTS=false

# ==========================================
# OTRAS CONFIGURACIONES
# ==========================================
LOG_CHANNEL=stack
LOG_LEVEL=error
FILESYSTEM_DISK=local
MAIL_MAILER=log
```

---

### 5️⃣ Variables Especiales de Railway (Automáticas)

Railway proporciona estas variables automáticamente, **NO las agregues manualmente**:

- ✅ `RAILWAY_PUBLIC_DOMAIN` - Tu dominio público
- ✅ `MYSQL_HOST` - Host de MySQL
- ✅ `MYSQL_PORT` - Puerto de MySQL  
- ✅ `MYSQL_DATABASE` - Nombre de la base de datos
- ✅ `MYSQL_USER` - Usuario de MySQL
- ✅ `MYSQL_PASSWORD` - Contraseña de MySQL
- ✅ `PORT` - Puerto donde corre la app

---

### 6️⃣ Variables que DEBES Cambiar

#### 🤖 Token de Telegram (OBLIGATORIO)

```env
TELEGRAM_BOT_TOKEN=7xxxxxxx:AAHxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**¿Dónde obtener tu token?**
1. Habla con [@BotFather](https://t.me/BotFather) en Telegram
2. Si ya tienes un bot, usa: `/mybots` → Selecciona tu bot → "API Token"
3. Copia el token completo

#### 🔑 APP_KEY (Se genera automáticamente)

Railway lo genera automáticamente. Si no:
1. En tu computadora: `php artisan key:generate --show`
2. Copia el resultado
3. Agrégalo como variable: `APP_KEY=base64:xxxxxxxxxxxx`

---

### 7️⃣ Generar Dominio Público

1. **En Railway, ve a tu servicio**
2. **Pestaña "Settings"**
3. **Sección "Networking"**
4. **Click en "Generate Domain"**
5. **Railway te dará un dominio como:** `proyecto-alcaldia-esq-production.up.railway.app`
6. **Copia ese dominio**

---

### 8️⃣ Actualizar APP_URL

1. **Ve a "Variables"**
2. **Busca `APP_URL`**
3. **Cámbiala a:**
```
https://proyecto-alcaldia-esq-production.up.railway.app
```
(Usa TU dominio de Railway)

---

### 9️⃣ Esperar Deployment

1. **Railway empezará a hacer deploy automáticamente**
2. **Ve a la pestaña "Deployments"**
3. **Espera a ver:**
   - ✅ "Building..." → "Running..." → "Success"
   - ⏱️ Tiempo aproximado: 3-5 minutos

---

### 🔟 Verificar que Todo Funciona

#### A. Verificar la Web

1. **Abre tu dominio:** `https://tu-dominio.up.railway.app`
2. **Deberías ver** la página de login del sistema
3. **Si ves errores:** Revisa logs en Railway

#### B. Verificar el Bot de Telegram

1. **Abre Telegram**
2. **Busca tu bot:** `@TuBot`
3. **Envía:** `/start`
4. **Debería responder** con el mensaje de bienvenida
5. **Prueba:** `/login tu_email@mail.com tu_contraseña`

---

## 🐛 Troubleshooting

### Error: "Application key not set"

**Solución:**
```bash
# En tu computadora:
php artisan key:generate --show

# Copia el resultado y agrégalo en Railway:
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

---

### Error: "Connection refused" (Base de datos)

**Verifica:**
1. ✅ Creaste la base de datos MySQL en Railway
2. ✅ Las variables `DB_*` están configuradas correctamente
3. ✅ Estás usando `${{MYSQL_HOST}}` NO valores manuales

---

### Bot no responde

**Verifica:**
1. ✅ `TELEGRAM_BOT_TOKEN` está correcto
2. ✅ `APP_URL` está configurado con tu dominio de Railway
3. ✅ El webhook se configuró (automático en start.sh)

**Ver webhook:**
```
https://api.telegram.org/bot{TU_TOKEN}/getWebhookInfo
```

---

### Ver Logs en Railway

1. **Ve a tu servicio**
2. **Pestaña "Deployments"**
3. **Click en el deployment activo**
4. **Ve a "View Logs"**
5. **Busca errores en rojo**

---

## 📊 Comandos Útiles (Opcional)

Si necesitas ejecutar comandos en Railway:

1. **Ve a tu servicio → Settings**
2. **Busca "Connect"**
3. **Copia el comando railway CLI**

O desde la interfaz web:
- **Settings → Restart** - Reiniciar servicio
- **Deployments → Redeploy** - Volver a desplegar

---

## 🎯 Checklist Final

- [ ] Proyecto creado en Railway
- [ ] Código conectado desde GitHub
- [ ] Base de datos MySQL creada
- [ ] Variables de entorno configuradas
- [ ] `TELEGRAM_BOT_TOKEN` agregado
- [ ] Dominio público generado
- [ ] `APP_URL` actualizado con el dominio
- [ ] Deployment exitoso (Status: Success)
- [ ] Web accesible desde el navegador
- [ ] Bot responde en Telegram
- [ ] Login funciona correctamente

---

## 🚀 Despliegues Futuros

Cada vez que hagas `git push` a GitHub:
1. ✅ Railway detecta el cambio automáticamente
2. ✅ Hace rebuild y redeploy
3. ✅ Tu app se actualiza en ~3 minutos

**¡No necesitas hacer nada más!**

---

## 💰 Costos

- **Railway Free Tier:**
  - ✅ $5 USD en créditos al mes (gratis)
  - ✅ 500 horas de ejecución
  - ✅ Suficiente para 1 proyecto pequeño-mediano

- **Si te quedas sin créditos:**
  - 💳 Agrega tarjeta para más créditos
  - O espera al próximo mes

---

## 📞 Soporte

Si algo no funciona:
1. **Revisa los logs** en Railway
2. **Verifica las variables** de entorno
3. **Contacta soporte** de Railway: https://railway.app/help

---

## ✅ ¡Listo!

Tu sistema está en producción y el bot de Telegram funcionando con webhooks.

**URL de tu sistema:** https://tu-dominio.up.railway.app  
**Bot de Telegram:** @TuBot

---

**Creado:** 2025-11-18  
**Sistema:** Nevora - Alcaldía del Municipio Escuque
