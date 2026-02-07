<?php

namespace App\Http\Controllers;

use App\Models\TenantPayment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PaymentReceiptController extends Controller
{
    public function download(TenantPayment $payment, string $type = 'thermal')
    {
        $view = $type === 'thermal' 
            ? 'pdf.receipts.thermal' 
            : 'pdf.receipts.formal';

        $pdf = PDF::loadView($view, [
            'payment' => $payment,
            'tenant' => $payment->tenant,
            'settings' => [
                'currency_symbol' => Setting::get('currency_symbol'),
                'company_name' => Setting::get('company_name', 'Tu Empresa'),
                'company_address' => Setting::get('company_address', 'Dirección de la empresa'),
                'company_phone' => Setting::get('company_phone', 'Teléfono de la empresa'),
                'company_email' => Setting::get('company_email', 'email@empresa.com'),
            ]
        ]);

        if ($type === 'thermal') {
            // Configurar tamaño para recibo térmico (58mm = 219.685px)
            $pdf->setPaper([0, 0, 219.685, 841.89], 'portrait');
        }

        return $pdf->download("recibo-{$payment->id}-{$type}.pdf");
    }
} 