<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Geolocalización - Municipio Escuque</title>
    
    <style>
        #map {
            height: 100%;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>

        <!-- Leyenda -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Leyenda de Parroquias</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($parroquiasMap as $parroquia)
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $parroquia['color'] }}"></div>
                    <span class="text-sm font-medium text-gray-700">{{ $parroquia['nombre'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Mapa -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div id="map"></div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $totalGeneral = array_sum(array_column($parroquiasMap, 'total_reportes'));
                $entregadosGeneral = array_sum(array_column($parroquiasMap, 'entregados'));
                $noEntregadosGeneral = array_sum(array_column($parroquiasMap, 'no_entregados'));
                $enProcesoGeneral = array_sum(array_column($parroquiasMap, 'en_proceso'));
            @endphp
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-gray-500 text-sm mb-1">Total de Reportes</div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalGeneral }}</div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-gray-500 text-sm mb-1">✅ Entregados</div>
                <div class="text-2xl font-bold text-green-600">{{ $entregadosGeneral }}</div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-gray-500 text-sm mb-1">❌ No Entregados</div>
                <div class="text-2xl font-bold text-red-600">{{ $noEntregadosGeneral }}</div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-gray-500 text-sm mb-1">🔄 En Proceso</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $enProcesoGeneral }}</div>
            </div>
        </div>

    </div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Datos de las parroquias
        const parroquiasData = @json($parroquiasMap);

        // Inicializar el mapa
        const map = L.map('map').setView([9.3114, -70.7592], 12);

        // Agregar capa de OpenStreetMap (GRATIS, sin API Key)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        // Función para crear marcadores
        function createMarker(parroquia) {
            // Crear icono personalizado con color de la parroquia
            const customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="custom-marker" style="background-color: ${parroquia.color}"></div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15],
                popupAnchor: [0, -15]
            });

            // Crear marcador
            const marker = L.marker([parroquia.lat, parroquia.lng], {
                icon: customIcon,
                title: parroquia.nombre
            }).addTo(map);

            // Contenido del popup
            const popupContent = `
                <div style="min-width: 280px; padding: 10px;">
                    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 10px; border-bottom: 2px solid ${parroquia.color}; padding-bottom: 8px;">
                        📍 ${parroquia.nombre}
                    </h3>
                    
                    <div style="background-color: #f3f4f6; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; padding: 5px 0;">
                            <span style="color: #6b7280; font-weight: 500;">📊 Total de Reportes:</span>
                            <strong style="color: #1f2937; font-size: 16px;">${parroquia.total_reportes}</strong>
                        </div>
                    </div>
                    
                    <div style="display: grid; gap: 8px;">
                        <div style="display: flex; justify-content: space-between; padding: 8px; background-color: #d1fae5; border-radius: 6px;">
                            <span style="color: #065f46; font-weight: 500;">✅ Entregados:</span>
                            <strong style="color: #047857; font-size: 15px;">${parroquia.entregados}</strong>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; padding: 8px; background-color: #fef3c7; border-radius: 6px;">
                            <span style="color: #92400e; font-weight: 500;">🔄 En Proceso:</span>
                            <strong style="color: #b45309; font-size: 15px;">${parroquia.en_proceso}</strong>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; padding: 8px; background-color: #fee2e2; border-radius: 6px;">
                            <span style="color: #991b1b; font-weight: 500;">❌ No Entregados:</span>
                            <strong style="color: #dc2626; font-size: 15px;">${parroquia.no_entregados}</strong>
                        </div>
                    </div>
                    
                    <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                        <div style="color: #6b7280; font-size: 11px; text-align: center;">
                            📍 Lat: ${parroquia.lat.toFixed(4)}, Lng: ${parroquia.lng.toFixed(4)}
                        </div>
                    </div>
                </div>
            `;

            // Agregar popup al marcador
            marker.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'custom-popup'
            });

            // Agregar etiqueta permanente con el nombre
            marker.bindTooltip(parroquia.nombre, {
                permanent: true,
                direction: 'top',
                offset: [0, -20],
                className: 'marker-label',
                opacity: 0.9
            });

            // Evento al hacer clic - abrir popup con animación
            marker.on('click', function() {
                map.setView([parroquia.lat, parroquia.lng], 13, {
                    animate: true,
                    duration: 0.5
                });
            });

            return marker;
        }

        // Crear marcadores para todas las parroquias
        parroquiasData.forEach(parroquia => {
            createMarker(parroquia);
        });

        // Agregar control de escala
        L.control.scale({
            imperial: false,
            metric: true
        }).addTo(map);

        // Ajustar vista para mostrar todos los marcadores
        if (parroquiasData.length > 0) {
            const bounds = L.latLngBounds(parroquiasData.map(p => [p.lat, p.lng]));
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    </script>
</x-livewire.layout.admin.admin>
