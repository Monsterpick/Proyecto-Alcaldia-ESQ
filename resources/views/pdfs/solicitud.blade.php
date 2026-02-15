<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud #{{ $solicitud->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; padding: 24px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 3px solid #1e40af; padding-bottom: 12px; }
        .header h1 { color: #1e40af; font-size: 20px; }
        .header p { color: #64748b; font-size: 12px; margin-top: 4px; }
        .section { margin-bottom: 16px; }
        .section-title { background: #f1f5f9; padding: 6px 10px; font-weight: bold; color: #334155; margin-bottom: 8px; }
        .row { margin-bottom: 6px; }
        .label { font-weight: 600; color: #475569; }
        .footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Solicitud ciudadana #{{ $solicitud->id }}</h1>
        <p>Alcaldía del Municipio Escuque — {{ $fechaVenezuela }}</p>
    </div>

    <div class="section">
        <div class="section-title">Datos del ciudadano</div>
        <div class="row"><span class="label">Nombre completo:</span> {{ $solicitud->ciudadano?->nombre }} {{ $solicitud->ciudadano?->apellido }}</div>
        <div class="row"><span class="label">Cédula:</span> {{ $solicitud->ciudadano?->cedula }}</div>
        <div class="row"><span class="label">Email:</span> {{ $solicitud->ciudadano?->email }}</div>
        <div class="row"><span class="label">Teléfono / WhatsApp:</span> {{ $solicitud->ciudadano?->telefono_movil ?? $solicitud->ciudadano?->whatsapp }}</div>
    </div>

    <div class="section">
        <div class="section-title">Solicitud</div>
        <div class="row"><span class="label">Tipo:</span> {{ $solicitud->tipoSolicitud?->nombre ?? '—' }}</div>
        <div class="row"><span class="label">Estado:</span> {{ ucfirst($solicitud->estado) }}</div>
        <div class="row"><span class="label">Parroquia:</span> {{ $solicitud->parroquia?->parroquia ?? '—' }}</div>
        <div class="row"><span class="label">Circuito comunal:</span> {{ $solicitud->circuitoComunal?->nombre ?? '—' }}</div>
        <div class="row"><span class="label">Sector:</span> {{ $solicitud->sector ?? '—' }}</div>
        <div class="row"><span class="label">Dirección:</span> {{ $solicitud->direccion_exacta ?? $solicitud->direccion ?? '—' }}</div>
        <div class="row" style="margin-top: 8px;"><span class="label">Descripción:</span></div>
        <div style="white-space: pre-wrap; padding: 8px; background: #f8fafc; border-radius: 4px;">{{ $solicitud->descripcion }}</div>
    </div>

    <div class="footer">
        Fecha y hora (Venezuela): {{ $fechaVenezuela }} — Sistema de solicitudes ciudadanas
    </div>
</body>
</html>
