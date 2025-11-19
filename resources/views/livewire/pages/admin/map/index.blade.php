<div>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📍 Mapa de Geolocalización</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Municipio Escuque - Distribución de Reportes por Parroquia</p>
    </div>

    <!-- Contenedor del Mapa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div id="map" style="height: 600px; width: 100%;"></div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        @php
            $totalGeneral = array_sum(array_column($parroquiasMap, 'total_reportes'));
            $entregadosGeneral = array_sum(array_column($parroquiasMap, 'entregados'));
            $noEntregadosGeneral = array_sum(array_column($parroquiasMap, 'no_entregados'));
            $enProcesoGeneral = array_sum(array_column($parroquiasMap, 'en_proceso'));
        @endphp
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="text-gray-500 dark:text-gray-400 text-sm mb-1">Total de Reportes</div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalGeneral }}</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="text-gray-500 dark:text-gray-400 text-sm mb-1">✅ Entregados</div>
            <div class="text-2xl font-bold text-green-600">{{ $entregadosGeneral }}</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="text-gray-500 dark:text-gray-400 text-sm mb-1">❌ No Entregados</div>
            <div class="text-2xl font-bold text-red-600">{{ $noEntregadosGeneral }}</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="text-gray-500 dark:text-gray-400 text-sm mb-1">🔄 En Proceso</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $enProcesoGeneral }}</div>
        </div>
    </div>
</div>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Inicializar el mapa inmediatamente cuando el DOM esté listo
    setTimeout(function() {
        // Datos de las parroquias con estadísticas
        var parroquiasData = @json($parroquiasMap);
        
        console.log('Iniciando mapa con datos:', parroquiasData);
        
        // Inicializar el mapa centrado en Escuque
        var map = L.map('map').setView([9.296520597950986, -70.67268456421307], 11);

        // Usar OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 18
        }).addTo(map);

        // Forzar redibujado del mapa
        setTimeout(function() { 
            map.invalidateSize(); 
        }, 100);

        // Agregar marcadores para cada parroquia
        parroquiasData.forEach(function(parroquia) {
            // Crear contenido del popup con estadísticas
            var popupContent = 
                '<div style="padding: 10px; min-width: 260px; font-family: system-ui, -apple-system, sans-serif;">' +
                    '<h3 style="font-size: 17px; font-weight: bold; color: #1f2937; margin: 0 0 10px 0; border-bottom: 3px solid ' + parroquia.color + '; padding-bottom: 6px;">' +
                        '📍 ' + parroquia.nombre +
                    '</h3>' +
                    '<div style="background-color: #f3f4f6; padding: 8px; border-radius: 6px; margin-bottom: 8px;">' +
                        '<div style="display: flex; justify-content: space-between; align-items: center;">' +
                            '<span style="color: #6b7280; font-weight: 600; font-size: 13px;">📊 Total de Reportes:</span>' +
                            '<strong style="color: #1f2937; font-size: 18px;">' + parroquia.total_reportes + '</strong>' +
                        '</div>' +
                    '</div>' +
                    '<div style="display: grid; gap: 6px; margin-top: 8px;">' +
                        '<div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; background-color: #d1fae5; border-radius: 5px; border-left: 3px solid #10b981;">' +
                            '<span style="color: #065f46; font-weight: 600; font-size: 12px;">✅ Entregados</span>' +
                            '<strong style="color: #047857; font-size: 15px;">' + parroquia.entregados + '</strong>' +
                        '</div>' +
                        '<div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; background-color: #fef3c7; border-radius: 5px; border-left: 3px solid #f59e0b;">' +
                            '<span style="color: #92400e; font-weight: 600; font-size: 12px;">🔄 En Proceso</span>' +
                            '<strong style="color: #b45309; font-size: 15px;">' + parroquia.en_proceso + '</strong>' +
                        '</div>' +
                        '<div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 8px; background-color: #fee2e2; border-radius: 5px; border-left: 3px solid #ef4444;">' +
                            '<span style="color: #991b1b; font-weight: 600; font-size: 12px;">❌ No Entregados</span>' +
                            '<strong style="color: #dc2626; font-size: 15px;">' + parroquia.no_entregados + '</strong>' +
                        '</div>' +
                    '</div>' +
                    '<div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e5e7eb; text-align: center;">' +
                        '<div style="color: #6b7280; font-size: 10px;">Municipio Escuque, Estado Trujillo</div>' +
                    '</div>' +
                '</div>';

            // Iconos de persona en diferentes colores según la parroquia
            var iconColors = {
                'Escuque': 'blue',
                'La Unión': 'green',
                'Sabana Libre': 'orange',
                'Santa Rita': 'red'
            };
            
            var iconColor = iconColors[parroquia.nombre] || 'blue';
            
            console.log('Creando marcador para:', parroquia.nombre, 'en', parroquia.lat, parroquia.lng);
            
            var coloredIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-' + iconColor + '.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Crear marcador con icono de persona
            var marker = L.marker([parroquia.lat, parroquia.lng], {
                icon: coloredIcon,
                title: parroquia.nombre
            }).addTo(map);

            // Agregar popup al marcador
            marker.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'custom-popup'
            });
        });
        
        // Mensaje en consola confirmando carga
        console.log('✅ Mapa cargado con ' + parroquiasData.length + ' marcadores');
    }, 500);
</script>
