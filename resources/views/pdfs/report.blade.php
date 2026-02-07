<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte {{ $report->report_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #1e40af;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #64748b;
            font-size: 14px;
        }
        
        .report-code {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 10px 15px;
            margin: 20px 0;
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background: #f1f5f9;
            padding: 8px 12px;
            border-left: 4px solid #64748b;
            font-size: 14px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 10px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 35%;
            padding: 6px 10px;
            font-weight: bold;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 10px;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-delivered {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-in-process {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-not-delivered {
            background: #fee2e2;
            color: #991b1b;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table thead {
            background: #f8fafc;
        }
        
        table th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
            color: #475569;
            border-bottom: 2px solid #cbd5e1;
        }
        
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        
        table tr:hover {
            background: #f8fafc;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }
        
        .footer p {
            margin: 3px 0;
        }
        
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>SISTEMA 1X10 ESCUQUE</h1>
        <p>Alcaldía del Municipio Escuque - Estado Trujillo</p>
        <p>Reporte de Entrega de Beneficios</p>
    </div>
    
    <!-- Código del Reporte -->
    <div class="report-code">
        [REPORTE] {{ $report->report_code }}
    </div>
    
    <!-- Información del Beneficiario -->
    <div class="section">
        <div class="section-title">► INFORMACIÓN DEL BENEFICIARIO</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre Completo:</div>
                <div class="info-value">{{ $report->beneficiary_full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Cédula:</div>
                <div class="info-value">{{ $report->beneficiary_cedula }}</div>
            </div>
        </div>
    </div>
    
    <!-- Información del Reporte -->
    <div class="section">
        <div class="section-title">► INFORMACIÓN DEL REPORTE</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Parroquia:</div>
                <div class="info-value">{{ $report->parish ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Entrega:</div>
                <div class="info-value">{{ $report->delivery_date ? $report->delivery_date->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    @if($report->status === 'delivered')
                        <span class="status-badge status-delivered">✓ Entregado</span>
                    @elseif($report->status === 'in_process')
                        <span class="status-badge status-in-process">◷ En Proceso</span>
                    @elseif($report->status === 'not_delivered')
                        <span class="status-badge status-not-delivered">✗ No Entregado</span>
                    @else
                        <span class="status-badge">{{ $report->status }}</span>
                    @endif
                </div>
            </div>
            @if($report->observation)
            <div class="info-row">
                <div class="info-label">Observaciones:</div>
                <div class="info-value">{{ $report->observation }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Creado por:</div>
                <div class="info-value">{{ $report->user->name ?? 'Sistema' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Creación:</div>
                <div class="info-value">{{ $report->created_at->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Categorías -->
    @if($report->categories && $report->categories->count() > 0)
    <div class="section">
        <div class="section-title">► CATEGORÍAS</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Categorías Asignadas:</div>
                <div class="info-value">
                    {{ $report->categories->pluck('name')->implode(', ') }}
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Productos/Items Entregados -->
    @if($report->items && $report->items->count() > 0)
    <div class="section">
        <div class="section-title">► PRODUCTOS ENTREGADOS</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">#</th>
                    <th style="width: 40%;">Producto</th>
                    <th style="width: 25%;">Categoría</th>
                    <th style="width: 15%;">Cantidad</th>
                    <th style="width: 10%;">Unidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->category->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity ?? 0 }}</td>
                    <td>{{ $item->product->unit ?? 'unidades' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- Resumen -->
    <div class="section">
        <div class="section-title">► RESUMEN</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Total de Productos:</div>
                <div class="info-value">{{ $report->items->count() }} producto(s)</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total de Items:</div>
                <div class="info-value">{{ $report->items->sum('quantity') }} unidad(es)</div>
            </div>
        </div>
    </div>
    
    <!-- Pie de página -->
    <div class="footer">
        <p><strong>Sistema Web de Gestion de la Alcaldia del Municipio Escuque</strong></p>
        <p>Estado Trujillo - Venezuela</p>
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin-top: 10px; font-size: 9px;">Este documento es una representación digital del reporte de entrega. Para cualquier consulta, contacte con la administración.</p>
    </div>
</body>
</html>
