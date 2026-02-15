<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SolicitudPdfController extends Controller
{
    public function download(Solicitud $solicitud)
    {
        $filename = 'solicitud-' . $solicitud->id . '-' . now()->format('Y-m-d') . '.pdf';
        $pdf = $this->crearPdf($solicitud);
        return $pdf->download($filename);
    }

    /**
     * Genera el contenido binario del PDF (para envío por WhatsApp u otros usos).
     */
    public static function generarPdfContent(Solicitud $solicitud): string
    {
        $controller = new self();
        return $controller->crearPdf($solicitud)->output();
    }

    private function crearPdf(Solicitud $solicitud)
    {
        $solicitud->load(['ciudadano', 'tipoSolicitud', 'parroquia', 'circuitoComunal']);
        $fechaVenezuela = Carbon::parse($solicitud->created_at)
            ->timezone('America/Caracas')
            ->locale('es')
            ->format('d/m/Y H:i');

        $pdf = Pdf::loadView('pdfs.solicitud', [
            'solicitud' => $solicitud,
            'fechaVenezuela' => $fechaVenezuela,
        ]);
        $pdf->setPaper('letter', 'portrait');
        return $pdf;
    }
}
