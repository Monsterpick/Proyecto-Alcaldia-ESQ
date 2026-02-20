# Bot de Telegram: cambio de servidor

Si has movido la aplicación a un **nuevo servidor** y el bot de Telegram **deja de responder** (y no ves errores en consola), casi siempre es porque **Telegram sigue enviando las actualizaciones a la URL del servidor anterior**.

## Qué ocurre

- Telegram guarda **una sola URL de webhook** por bot.
- Esa URL se configuró cuando el bot estaba en el servidor viejo.
- Al cambiar de servidor, **nadie le dice a Telegram la nueva URL**, así que sigue enviando los mensajes al servidor antiguo.
- El servidor nuevo **nunca recibe las peticiones** → no hay errores en consola y el bot no responde.

## Solución (en el servidor nuevo)

### 1. Configurar la URL del nuevo servidor

En el **.env del servidor nuevo** debe estar la URL pública del sitio (con `https://`):

```env
APP_URL=https://tu-dominio-actual.com
```

Sin `https://` o con la URL del servidor antiguo, el webhook se configurará mal.

### 2. Volver a registrar el webhook en Telegram

En el servidor nuevo, ejecuta:

```bash
php artisan telegram:setup-webhook
```

Eso llama a la API de Telegram y **reemplaza** la URL del webhook por:

`https://tu-dominio-actual.com/api/telegram/webhook`

A partir de ese momento, Telegram enviará todas las actualizaciones a este servidor.

### 3. Comprobar que el webhook es el correcto

Puedes ver qué URL tiene configurada Telegram de dos formas:

**Opción A – Navegador o curl (desde un equipo con acceso a internet):**

```text
GET https://tu-dominio-actual.com/api/telegram/webhook-info
```

En la respuesta verás algo como:

- `current_url`: URL a la que Telegram envía las actualizaciones. Debe ser `https://tu-dominio-actual.com/api/telegram/webhook`.
- Si sigue siendo la URL del servidor viejo, repite el paso 2 en el servidor nuevo.

**Opción B – Artisan:**

El comando `telegram:setup-webhook` hace la configuración y, si todo va bien, indica que el webhook está verificado.

### 4. Comprobar que las peticiones llegan a este servidor

Cada vez que alguien escribe al bot, tu aplicación escribe una línea en el log tipo:

```text
Telegram webhook: petición recibida en este servidor
```

- Revisa `storage/logs/laravel.log` (o el canal que uses).
- Si **nunca** aparece esa línea al escribir al bot, las peticiones no están llegando a este servidor → vuelve a revisar `APP_URL` y a ejecutar `php artisan telegram:setup-webhook`.

### 5. Requisitos del servidor

- **HTTPS:** Telegram solo acepta webhooks con `https://`. En local (`http://localhost`) el webhook no funcionará; en producción la URL debe ser `https://...`.
- **Token:** En `.env` debe estar el mismo `TELEGRAM_BOT_TOKEN` que usabas en el servidor anterior (el de @BotFather).

## Resumen rápido

| Paso | Acción |
|------|--------|
| 1 | En el servidor nuevo: `APP_URL=https://tu-dominio-actual.com` en `.env` |
| 2 | Ejecutar: `php artisan telegram:setup-webhook` |
| 3 | Abrir `https://tu-dominio-actual.com/api/telegram/webhook-info` y comprobar que `current_url` es la de este servidor |
| 4 | Probar el bot; si no responde, revisar `storage/logs/laravel.log` para ver si aparece "Telegram webhook: petición recibida" |

## Configurar el webhook por API (alternativa)

Si prefieres no usar Artisan, puedes llamar a tu propia API desde el servidor (o desde Postman) con la URL que quieras:

```bash
curl -X POST "https://tu-dominio-actual.com/api/telegram/set-webhook" \
  -H "Content-Type: application/json" \
  -d '{"url": "https://tu-dominio-actual.com/api/telegram/webhook"}'
```

El controlador usa por defecto `url('/api/telegram/webhook')`, que depende de `APP_URL`, así que en producción lo más sencillo es tener bien `APP_URL` y usar `php artisan telegram:setup-webhook`.
