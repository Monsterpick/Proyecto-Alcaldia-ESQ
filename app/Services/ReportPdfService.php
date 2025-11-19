<?php

namespace App\Services;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportPdfService
{
    /**
     * Generar PDF para un reporte
     */
    public function generatePdf(Report $report): string
    {
        // Cargar relaciones necesarias
        $report->load(['items.product.category', 'categories', 'user']);
        
        // Generar el PDF
        $pdf = Pdf::loadView('pdfs.report', ['report' => $report]);
        
        // Configurar opciones del PDF
        $pdf->setPaper('letter', 'portrait');
        
        // Generar nombre del archivo
        $filename = $this->generateFilename($report);
        
        // Guardar el PDF en storage
        $path = "reports/pdfs/{$filename}";
        Storage::disk('public')->put($path, $pdf->output());
        
        // Actualizar el reporte con la ruta del PDF
        $report->update(['pdf_path' => $path]);
        
        return $path;
    }
    
    /**
     * Regenerar PDF de un reporte
     */
    public function regeneratePdf(Report $report): string
    {
        // Eliminar PDF anterior si existe
        if ($report->pdf_path && Storage::disk('public')->exists($report->pdf_path)) {
            Storage::disk('public')->delete($report->pdf_path);
        }
        
        // Generar nuevo PDF
        return $this->generatePdf($report);
    }
    
    /**
     * Obtener la ruta completa del PDF
     */
    public function getPdfPath(Report $report): ?string
    {
        if (!$report->pdf_path) {
            return null;
        }
        
        return Storage::disk('public')->path($report->pdf_path);
    }
    
    /**
     * Obtener la URL pública del PDF
     */
    public function getPdfUrl(Report $report): ?string
    {
        if (!$report->pdf_path) {
            return null;
        }
        
        return Storage::disk('public')->url($report->pdf_path);
    }
    
    /**
     * Verificar si el PDF existe
     */
    public function pdfExists(Report $report): bool
    {
        if (!$report->pdf_path) {
            return false;
        }
        
        return Storage::disk('public')->exists($report->pdf_path);
    }
    
    /**
     * Descargar el PDF
     */
    public function downloadPdf(Report $report)
    {
        if (!$this->pdfExists($report)) {
            // Si no existe, generarlo
            $this->generatePdf($report);
        }
        
        $filename = $this->generateFilename($report);
        
        return Storage::disk('public')->download($report->pdf_path, $filename);
    }
    
    /**
     * Generar nombre del archivo PDF
     */
    private function generateFilename(Report $report): string
    {
        // Sanitizar el código del reporte para usar como nombre de archivo
        $code = Str::slug($report->report_code);
        $timestamp = now()->format('YmdHis');
        
        return "{$code}_{$timestamp}.pdf";
    }
    
    /**
     * Eliminar PDF de un reporte
     */
    public function deletePdf(Report $report): bool
    {
        if (!$report->pdf_path) {
            return false;
        }
        
        if (Storage::disk('public')->exists($report->pdf_path)) {
            Storage::disk('public')->delete($report->pdf_path);
        }
        
        $report->update(['pdf_path' => null]);
        
        return true;
    }
    
    /**
     * Generar PDFs para múltiples reportes
     */
    public function generateBulkPdfs(array $reportIds): array
    {
        $results = [
            'success' => [],
            'failed' => []
        ];
        
        foreach ($reportIds as $reportId) {
            try {
                $report = Report::findOrFail($reportId);
                $path = $this->generatePdf($report);
                $results['success'][] = [
                    'report_id' => $reportId,
                    'path' => $path
                ];
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'report_id' => $reportId,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
}
