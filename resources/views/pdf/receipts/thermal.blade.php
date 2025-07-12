<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo de Pago #{{ $payment->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .border-bottom { border-bottom: 1px dashed #000; }
        .pt-2 { padding-top: 8px; }
        .company-name {
            font-size: 12px;
            font-weight: bold;
        }
        .receipt-title {
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="text-center mb-2">
        <div class="company-name">{{ $settings['company_name'] }}</div>
        <div>{{ $settings['company_address'] }}</div>
        <div>Tel: {{ $settings['company_phone'] }}</div>
        <div class="border-bottom">{{ $settings['company_email'] }}</div>
    </div>

    <div class="text-center mb-2">
        <div class="receipt-title">RECIBO DE PAGO</div>
        <div># {{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="mb-2 border-bottom">
        <div><span class="font-bold">Fecha:</span> {{ $payment->payment_date->format('d/m/Y') }}</div>
        <div><span class="font-bold">Cliente:</span> {{ $tenant->name }}</div>
        <div><span class="font-bold">Referencia:</span> {{ $payment->reference_number }}</div>
    </div>

    <div class="mb-2 border-bottom">
        <div><span class="font-bold">Tipo de Pago:</span> {{ $payment->paymentType->name }}</div>
        <div><span class="font-bold">Origen:</span> {{ $payment->paymentOrigin->name }}</div>
        <div><span class="font-bold">Periodo:</span> {{ $payment->concept }}</div>
    </div>

    <div class="text-right mb-2 border-bottom">
        <div class="font-bold">TOTAL PAGADO:</div>
        <div style="font-size: 12px;">{{ $settings['currency_symbol'] }} {{ number_format($payment->amount, 2) }}</div>
    </div>

    <div class="text-center pt-2" style="font-size: 9px;">
        <p>¡Gracias por su pago!</p>
        <p>Este documento es un recibo de pago válido</p>
    </div>
</body>
</html> 