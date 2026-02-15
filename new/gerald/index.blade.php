<?php
use Livewire\Volt\Component;
use App\Models\Miembro;
use App\Models\Comision;
use App\Models\SesionMunicipal;
use App\Models\Noticia;
use App\Models\DerechoDePalabra;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

new class extends Component {  
    public $lineData = [];
    public $barData = [];
    public $doughnutData = [];
    public $suggestion = '';
    public $lat = null;
    public $lng = null;
    public $currentTime = null;
    public $lastConnection = null;
    
    // Variables para Tarjetas
    public $totalMiembros = 0;
    public $totalComisiones = 0;
    
    // 📊 Propiedades Públicas para Derecho de Palabra
    public $meses = []; 
    public $datosAprobadas = [];
    public $datosPendientes = [];
    public $datosRechazadas = [];
    
    // 📊 Propiedades Públicas para Sesiones Completadas
    public $mesesSesiones = []; 
    public $datosSesiones = [];

    // 📊 Propiedades Públicas para Gráfica de Torta (Concejales por Comisión)
    public $comisionesLabels = [];
    public $comisionesData = [];
    public $comisionesColores = [];

    // --- Propiedades Calculadas ---
    
    #[Computed]
    public function sesionesActivas()
    {
        return SesionMunicipal::where('estado', 'proxima')->count();
    }

    #[Computed]
    public function totalNoticias()
    {
        return Noticia::count();
    }

    // --- Ciclo de Vida ---
    
    public function mount()
    {
        $this->totalMiembros = Miembro::count();
        $this->totalComisiones = Comision::count();
        $this->currentTime = now()->format('Y-m-d H:i:s');
        $this->lastConnection = now()->subMinutes(5)->format('Y-m-d H:i:s');

        $this->cargarDatosGraficaDerecho();
        $this->cargarDatosGraficaSesiones();
        $this->cargarDatosGraficaTorta();
        
        // Datos de prueba originales
        $this->lineData = [['time' => '2025-01-01', 'value' => 120]]; // resumido
        $this->barData = ['labels' => ['Enero'], 'values' => [50]]; // resumido
        $this->doughnutData = ['labels' => ['A'], 'values' => [10]]; // resumido
    }

    // --- Lógica de la Gráfica de Sesiones ---
    
    public function cargarDatosGraficaSesiones()
    {
        $this->mesesSesiones = [];
        $this->datosSesiones = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            
            $this->mesesSesiones[] = ucfirst($fecha->translatedFormat('M')); 
            
            $sesiones = SesionMunicipal::where(function($query) {
                    $query->where('estado', 'Completada')
                          ->orWhere('estado', 'completada');
                })
                ->whereMonth('fecha_hora', $fecha->month) 
                ->whereYear('fecha_hora', $fecha->year)
                ->count();

            $this->datosSesiones[] = $sesiones;
        }
    }
    
    // --- Lógica de Derecho de Palabra ---
    public function cargarDatosGraficaDerecho()
    {
        $this->meses = [];
        $this->datosAprobadas = [];
        $this->datosPendientes = [];
        $this->datosRechazadas = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            
            $this->meses[] = ucfirst($fecha->translatedFormat('M')); 
            
            $this->datosAprobadas[] = DerechoDePalabra::where('estado', 'aprobada')
                ->whereMonth('created_at', $fecha->month)->whereYear('created_at', $fecha->year)->count();
            
            $this->datosPendientes[] = DerechoDePalabra::where('estado', 'pendiente')
                ->whereMonth('created_at', $fecha->month)->whereYear('created_at', $fecha->year)->count();
            
            $this->datosRechazadas[] = DerechoDePalabra::where('estado', 'rechazada')
                ->whereMonth('created_at', $fecha->month)->whereYear('created_at', $fecha->year)->count();
        }
    }
// --- Lógica de Gráfica de Torta (Concejales por Comisión) ---
public function cargarDatosGraficaTorta()
{
    $this->comisionesLabels = [];
    $this->comisionesData = [];
    $this->comisionesColores = [];

    $colores = [
        'rgba(59, 130, 246, 0.8)',      // Azul
        'rgba(16, 185, 129, 0.8)',      // Verde
        'rgba(251, 146, 60, 0.8)',      // Naranja
        'rgba(139, 92, 246, 0.8)',      // Púrpura
        'rgba(236, 72, 153, 0.8)',      // Rosa
        'rgba(245, 158, 11, 0.8)',      // Ámbar
        'rgba(34, 197, 94, 0.8)',       // Verde Lima
        'rgba(168, 85, 247, 0.8)',      // Violeta
        'rgba(14, 165, 233, 0.8)',      // Cielo
    ];

    // Query: Concejales Y Comisiones
    $datos = \DB::table('comision_concejal')
        ->join('comisions', 'comision_concejal.comision_id', '=', 'comisions.id')
        ->join('concejal', 'comision_concejal.concejal_id', '=', 'concejal.id')
        ->join('miembros', 'comision_concejal.miembro_id', '=', 'miembros.id')
        ->select(
            'comisions.nombre as comision',
            \DB::raw("CONCAT(concejal.nombre, ' ', concejal.apellido) as nombre_concejal"),
            'miembros.estado',
            \DB::raw('COUNT(*) as cantidad')
        )
        ->where('miembros.estado', 'Activo')
        ->groupBy('comisions.nombre', 'concejal.nombre', 'concejal.apellido', 'miembros.estado')
        ->orderBy('comisions.nombre')
        ->get();

    $colorIndex = 0;
    $etiquetasUnicas = [];
    
    foreach ($datos as $registro) {
        // Formato: "Comisión - Concejal (Miembro Activo)"
        $etiqueta = $registro->comision . ' - ' . $registro->nombre_concejal;
        
        if (!in_array($etiqueta, $etiquetasUnicas)) {
            $this->comisionesLabels[] = $etiqueta;
            $this->comisionesData[] = $registro->cantidad;
            $this->comisionesColores[] = $colores[$colorIndex % count($colores)];
            $etiquetasUnicas[] = $etiqueta;
            $colorIndex++;
        }
    }

    // Si no hay datos, añadir placeholder
    if (empty($this->comisionesLabels)) {
        $this->comisionesLabels = ['Sin datos disponibles'];
        $this->comisionesData = [1];
        $this->comisionesColores = ['rgba(229, 231, 235, 0.8)'];
    }
}
    // --- Métodos Públicos ---
    
    public function refreshData()
    {
        $this->totalMiembros = Miembro::count();
        $this->totalComisiones = Comision::count();
        $this->cargarDatosGraficaDerecho();
        $this->cargarDatosGraficaSesiones();
        $this->cargarDatosGraficaTorta();
    }
    
    public function submitSuggestion()
    {
        if ($this->suggestion !== '') {
            session()->flash('message', 'Gracias.');
            $this->suggestion = '';
        }
    }
    
// Método para PDF de Gráfica de Línea

#[On('generateLineChartPDF')]
#[Rule('renderless')] 
public function generateLineChartPDF($chartImage)
{
    try {
        $logoPath = Setting::get('logo_horizontal');
        $logoIcon = null;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $imageContent = Storage::disk('public')->get($logoPath);
            $mimeType = Storage::disk('public')->mimeType($logoPath);
            $logoIcon = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
        }

        $primaryColor = Setting::get('primary_color', '#0f2440');
        $secondaryColor = Setting::get('secondary_color', '#00d4ff');

        $fields = [
            ['label' => 'Total Solicitudes', 'value' => array_sum($this->datosAprobadas) + array_sum($this->datosPendientes) + array_sum($this->datosRechazadas), 'highlight' => true],
            ['label' => 'Aprobadas', 'value' => array_sum($this->datosAprobadas)],
            ['label' => 'Pendientes', 'value' => array_sum($this->datosPendientes)],
            ['label' => 'Rechazadas', 'value' => array_sum($this->datosRechazadas)],
            ['label' => 'Período', 'value' => '12 meses'],
            ['label' => 'Generado', 'value' => now()->format('d/m/Y H:i')],
        ];

        $html = view('livewire.pages.admin.pdf.pdf-layout', [
            'fields' => $fields,
            'title' => 'Participación Ciudadana',
            'subtitle' => 'Análisis Mensual de Solicitudes',
            'logo_icon' => $logoIcon,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
            'tags' => ['Participación', 'Ciudadana', 'Análisis', 'Solicitudes'],
            'badgeTitle' => 'Resumen de Solicitudes',
            'sectionTitle' => 'Estadísticas de Participación',
            'chartImage' => $chartImage
        ])->render();

        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('default_font', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('enable_remote', true);

        $filename = "participacion_ciudadana_" . now()->format('d-m-Y_H-i') . ".pdf";
        
        // Guardar en public/temp/
        $tempDir = public_path('temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        file_put_contents($tempDir . '/' . $filename, $pdf->output());

        // Disparar descarga
        $this->dispatch('downloadPDF', filename: $filename);
        
    } catch (\Exception $e) {
        \Log::error('Error en generateLineChartPDF: ' . $e->getMessage());
        
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Error al generar el PDF: ' . $e->getMessage(),
        ]);
    }
}


// Método para PDF de Gráfica de Barras
#[On('generateBarChartPDF')]
#[Rule('renderless')] 
public function generateBarChartPDF($chartImage)
{
    try {
        $logoPath = Setting::get('logo_horizontal');
        $logoIcon = null;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $imageContent = Storage::disk('public')->get($logoPath);
            $mimeType = Storage::disk('public')->mimeType($logoPath);
            $logoIcon = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
        }

        $primaryColor = Setting::get('primary_color', '#0f2440');
        $secondaryColor = Setting::get('secondary_color', '#00d4ff');

        // Calcular estadísticas de sesiones
        $totalSesiones = array_sum($this->datosSesiones);
        $promedio = count($this->datosSesiones) > 0 ? number_format(array_sum($this->datosSesiones) / count($this->datosSesiones), 1) : 0;
        $mayor = count($this->datosSesiones) > 0 ? max($this->datosSesiones) : 0;
        $menor = count($this->datosSesiones) > 0 ? min($this->datosSesiones) : 0;

        $fields = [
            ['label' => 'Total Sesiones', 'value' => $totalSesiones, 'highlight' => true],
            ['label' => 'Promedio Mensual', 'value' => $promedio],
            ['label' => 'Mayor Mes', 'value' => $mayor],
            ['label' => 'Menor Mes', 'value' => $menor],
            ['label' => 'Período', 'value' => '12 meses'],
            ['label' => 'Generado', 'value' => now()->format('d/m/Y H:i')],
        ];

        $html = view('livewire.pages.admin.pdf.pdf-layout', [
            'fields' => $fields,
            'title' => 'Sesiones Municipales',
            'subtitle' => 'Análisis Mensual de Sesiones Completadas',
            'logo_icon' => $logoIcon,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
            'tags' => ['Sesiones', 'Municipales', 'Análisis', 'Completadas'],
            'badgeTitle' => 'Resumen de Sesiones',
            'sectionTitle' => 'Estadísticas de Sesiones Completadas',
            'chartImage' => $chartImage
        ])->render();

        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('default_font', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('enable_remote', true);

        $filename = "sesiones_completadas_" . now()->format('d-m-Y_H-i') . ".pdf";
        
        // Guardar en public/temp/
        $tempDir = public_path('temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        file_put_contents($tempDir . '/' . $filename, $pdf->output());

        // Disparar descarga
        $this->dispatch('downloadPDF', filename: $filename);
        
    } catch (\Exception $e) {
        \Log::error('Error en generateBarChartPDF: ' . $e->getMessage());
        
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Error al generar el PDF: ' . $e->getMessage(),
        ]);
    }
}

// Método para PDF de Gráfica de Torta (Doughnut)
#[On('generateDoughnutChartPDF')]
#[Rule('renderless')] 
public function generateDoughnutChartPDF($chartImage)
{
    try {
        $logoPath = Setting::get('logo_horizontal');
        $logoIcon = null;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $imageContent = Storage::disk('public')->get($logoPath);
            $mimeType = Storage::disk('public')->mimeType($logoPath);
            $logoIcon = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
        }

        $primaryColor = Setting::get('primary_color', '#0f2440');
        $secondaryColor = Setting::get('secondary_color', '#00d4ff');

        // Calcular estadísticas de comisiones
        $totalConcejales = array_sum($this->comisionesData);
        $totalComisiones = count($this->comisionesData);
        $mayorCantidad = count($this->comisionesData) > 0 ? max($this->comisionesData) : 0;
        $promedioCantidad = $totalComisiones > 0 ? number_format($totalConcejales / $totalComisiones, 1) : 0;

        $fields = [
            ['label' => 'Total Concejales', 'value' => $totalConcejales, 'highlight' => true],
            ['label' => 'Total Comisiones', 'value' => $totalComisiones],
            ['label' => 'Mayor Cantidad', 'value' => $mayorCantidad],
            ['label' => 'Promedio', 'value' => $promedioCantidad],
            ['label' => 'Período', 'value' => 'Actual'],
            ['label' => 'Generado', 'value' => now()->format('d/m/Y H:i')],
        ];

        $html = view('livewire.pages.admin.pdf.pdf-layout', [
            'fields' => $fields,
            'title' => 'Distribución por Comisiones',
            'subtitle' => 'Análisis de Concejales Activos',
            'logo_icon' => $logoIcon,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
            'tags' => ['Comisiones', 'Concejales', 'Distribución', 'Análisis'],
            'badgeTitle' => 'Resumen de Comisiones',
            'sectionTitle' => 'Estadísticas de Distribución',
            'chartImage' => $chartImage
        ])->render();

        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('default_font', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('enable_remote', true);

        $filename = "distribucion_comisiones_" . now()->format('d-m-Y_H-i') . ".pdf";
        
        // Guardar en public/temp/
        $tempDir = public_path('temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        file_put_contents($tempDir . '/' . $filename, $pdf->output());

        // Disparar descarga
        $this->dispatch('downloadPDF', filename: $filename);
        
    } catch (\Exception $e) {
        \Log::error('Error en generateDoughnutChartPDF: ' . $e->getMessage());
        
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Error al generar el PDF: ' . $e->getMessage(),
        ]);
    }
}

};
?>

<div class="mt-6">
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Dashboard',
            ],
        ]" />
    </x-slot>

    <x-container class="w-full px-6">
        <!-- Tarjetas Estadísticas Mejoradas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 mt-6 px-2 sm:px-0">
            
            <!-- Tarjeta Noticias -->
            <a href="{{ route('admin.noticias.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Total Noticias</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $this->totalNoticias }}</h3>
                        <p class="text-blue-500 text-xs mt-1 sm:mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-newspaper"></i> Publicadas
                        </p>
                    </div>
                    <div class="p-3 sm:p-4 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                        <i class="fa-solid fa-newspaper text-2xl sm:text-3xl text-blue-500 dark:text-blue-400"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Sesiones -->
            <a href="{{ route('admin.sesion_municipal.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Sesiones Activas</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $this->sesionesActivas }}</h3>
                        <p class="text-green-500 text-xs mt-1 sm:mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-arrow-trend-up"></i> Disponibles ahora
                        </p>
                    </div>
                    <div class="p-3 sm:p-4 bg-green-100 dark:bg-green-900/30 rounded-xl">
                        <i class="fa-solid fa-users text-2xl sm:text-3xl text-green-500 dark:text-green-400"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Comisiones -->
            <a href="{{ route('admin.comisiones.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-orange-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Total Comisiones</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalComisiones }}</h3>
                        <p class="text-blue-500 text-xs mt-1 sm:mt-2">Activas</p>
                    </div>
                    <div class="p-3 sm:p-4 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                        <i class="fa-solid fa-layer-group text-2xl sm:text-3xl text-orange-500 dark:text-orange-400"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Miembros -->
            <a href="{{ route('admin.miembros.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-purple-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Miembros Totales</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMiembros }}</h3>
                        <p class="text-purple-500 text-xs mt-1 sm:mt-2">En comisiones</p>
                    </div>
                    <div class="p-3 sm:p-4 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                        <i class="fa-solid fa-user-group text-2xl sm:text-3xl text-purple-500 dark:text-purple-400"></i>
                    </div>
                </div>
            </a>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">  
            @include('livewire.pages.admin.dashboard.form.chartjs_linea') 
            @include('livewire.pages.admin.dashboard.form.chartjs_barras')
            @include('livewire.pages.admin.dashboard.form.chartjs_Torta')
        </div>
    </x-container>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
       // chart lineas
    window.downloadLineChartPDF = function () {
        const canvas = document.getElementById('lineChart');

        if (!canvas) {
            Swal.fire({
                title: 'Sin datos',
                text: 'No hay gráfica disponible',
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        Swal.fire({
            title: 'Generando PDF',
            text: 'Por favor espera...',
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        try {
            const chartImage = canvas.toDataURL('image/png', 1.0);
            Livewire.dispatch('generateLineChartPDF', { chartImage: chartImage });

        } catch (error) {
            console.error('Error:', error);
            Swal.close();
            Swal.fire({
                title: 'Error',
                text: 'Error al procesar la gráfica',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        }
    }

    // Escuchar evento de descarga desde Livewire
    Livewire.on('downloadPDF', (data) => {
        const { filename } = data;
        
        const link = document.createElement('a');
        link.href = `/temp/${filename}`;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        Swal.close();
    });

    // Escuchar evento de alerta
    Livewire.on('showAlert', (data) => {
        Swal.fire(data);
    });
</script>

<script>
      // chart barras
    function downloadBarPDF(chartId) {
    const canvas = document.getElementById(chartId);    
    if (!canvas) {
        Swal.fire({
            title: 'Sin datos',
            text: 'No hay gráfica disponible',
            icon: 'warning',
            confirmButtonColor: '#3085d6'
        });
        return;
    }
    Swal.fire({
        title: 'Generando PDF',
        text: 'Por favor espera...',
        didOpen: () => {
            Swal.showLoading();
        },
        allowOutsideClick: false,
        allowEscapeKey: false
    });
    try {
        const chartImage = canvas.toDataURL('image/png', 1.0);
        Livewire.dispatch('generateBarChartPDF', { chartImage: chartImage });

    } catch (error) {
        console.error('Error:', error);
        Swal.close();
        Swal.fire({
            title: 'Error',
            text: 'Error al procesar la gráfica',
            icon: 'error',
            confirmButtonColor: '#3085d6'
        });
    }}
</script>


<script>
// chart torta - MEJORADO
function downloadDoughnutPDF() {
    const canvas = document.getElementById('doughnutChart');    
    if (!canvas) {
        Swal.fire({
            title: 'Sin datos',
            text: 'No hay gráfica disponible',
            icon: 'warning',
            confirmButtonColor: '#3085d6'
        });
        return;
    }  
    window.comisionesDataBeforePDF = [...window.comisionesDataGlobal];
    window.comisionesLabelsBeforePDF = [...window.comisionesLabelsGlobal];
    window.comisionesColoresBeforePDF = [...window.comisionesColoresGlobal];
    Swal.fire({
        title: 'Generando PDF',
        text: 'Por favor espera...',
        didOpen: () => {
            Swal.showLoading();
        },
        allowOutsideClick: false,
        allowEscapeKey: false
    });
    try {
        const chartImage = canvas.toDataURL('image/png', 1.0);
        Livewire.dispatch('generateDoughnutChartPDF', { chartImage: chartImage });
    } catch (error) {
        console.error('Error:', error);
        Swal.close();
        Swal.fire({
            title: 'Error',
            text: 'Error al procesar la gráfica',
            icon: 'error',
            confirmButtonColor: '#3085d6'
        });
    }
}
Livewire.on('downloadPDF', (data) => {
    const { filename } = data;    
    const link = document.createElement('a');
    link.href = `/temp/${filename}`;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);    
    Swal.close();   
    setTimeout(() => {
        if (window.comisionesDataBeforePDF && window.comisionesLabelsBeforePDF) {
            window.comisionesDataGlobal = window.comisionesDataBeforePDF;
            window.comisionesLabelsGlobal = window.comisionesLabelsBeforePDF;
            window.comisionesColoresGlobal = window.comisionesColoresBeforePDF;
            initDoughnutChart(window.comisionesLabelsGlobal, window.comisionesDataGlobal, window.comisionesColoresGlobal);
        }
    }, 500);
});
Livewire.on('showAlert', (data) => {
    Swal.fire(data);
});
</script>
@endpush