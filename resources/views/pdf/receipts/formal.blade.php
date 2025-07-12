<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura #{{ $payment->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-info {
            float: left;
            width: 60%;
        }
        .receipt-info {
            float: right;
            width: 35%;
            text-align: right;
            border: 1px solid #333;
            padding: 10px;
        }
        .clear { clear: both; }
        .mb-4 { margin-bottom: 16px; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        .total-section {
            margin-top: 20px;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .fiscal-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="fiscal-info">
        DOCUMENTO FISCAL
        <br>
        RIF: {{ $settings['company_rif'] ?? 'J-XXXXXXXX-X' }}
    </div>

    <div class="header">
        <div class="company-info">
            <div class="company-name">{{ $settings['company_name'] }}</div>
            <div>{{ $settings['company_address'] }}</div>
            <div>Tel: {{ $settings['company_phone'] }}</div>
            <div>Email: {{ $settings['company_email'] }}</div>
        </div>
        <div class="receipt-info">
            <div class="receipt-title">FACTURA</div>
            <div>N° {{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</div>
            <div>Fecha: {{ $payment->payment_date->format('d/m/Y H:i:s') }}</div>
            <div>Control N°: {{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="mb-4">
        <div class="font-bold">DATOS DEL CLIENTE:</div>
        <table>
            <tr>
                <td width="20%"><strong>Razón Social:</strong></td>
                <td>{{ $tenant->name }}</td>
                <td width="20%"><strong>RIF/CI:</strong></td>
                <td>{{ $tenant->rif ?? 'J-XXXXXXXX-X' }}</td>
            </tr>
            <tr>
                <td><strong>Dirección:</strong></td>
                <td colspan="3">{{ $tenant->address ?? 'Dirección del cliente' }}</td>
            </tr>
            <tr>
                <td><strong>Teléfono:</strong></td>
                <td>{{ $tenant->phone ?? 'N/A' }}</td>
                <td><strong>Email:</strong></td>
                <td>{{ $tenant->email ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="mb-4">
        <div class="font-bold">DETALLES DE LA FACTURA:</div>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">IVA (16%)</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>001</td>
                    <td>{{ $payment->concept ?? 'Pago de Servicio' }}</td>
                    <td class="text-right">1</td>
                    <td class="text-right">{{ number_format($payment->amount / 1.16, 2) }}</td>
                    <td class="text-right">{{ number_format($payment->amount - ($payment->amount / 1.16), 2) }}</td>
                    <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <table>
            <tr>
                <td width="80%" class="text-right"><strong>Sub-Total:</strong></td>
                <td class="text-right">{{ number_format($payment->amount / 1.16, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>IVA (16%):</strong></td>
                <td class="text-right">{{ number_format($payment->amount - ($payment->amount / 1.16), 2) }}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>{{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    @if($payment->notes)
    <div class="mb-4">
        <div class="font-bold">OBSERVACIONES:</div>
        <p>{{ $payment->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>"ESTE COMPROBANTE NO TENDRÁ EFECTOS FISCALES Y LEGALES SI PRESENTA TACHADURAS, BORRONES O ENMENDADURAS"</p>
        <p>Original: Cliente - Copia: Emisor</p>
        <p>Para consultar la validez de este documento fiscal ingrese a: consulta.thefactoryhka.com.ve</p>
    </div>
</body>
</html> 