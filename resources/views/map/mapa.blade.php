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
        // Datos de las parroquias con estadísticas desde PHP
        const parroquiasData = @json($parroquiasMap);

        // Función para inicializar el mapa
        function initMap() {
            // Centro del mapa en Escuque, Trujillo
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: { lat: 9.3114, lng: -70.7592 },
            });

            setMarkers(map);
        }

        function setMarkers(map) {
            // Icono personalizado de marcador (bandera)
            const image = {
                url: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
                size: new google.maps.Size(20, 32),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(0, 32),
            };

            const shape = {
                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                type: "poly",
            };

            // Crear un marcador por cada parroquia
            for (let i = 0; i < parroquiasData.length; i++) {
                const parroquia = parroquiasData[i];

                // Crear contenido de la ventana de información
                const contentString = `
                    <div style="padding: 15px; min-width: 280px;">
                        <h3 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 12px; border-bottom: 3px solid ${parroquia.color}; padding-bottom: 8px;">
                            📍 ${parroquia.nombre}
                        </h3>
                        
                        <div style="background-color: #f3f4f6; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between; padding: 5px 0;">
                                <span style="color: #6b7280; font-weight: 600;">📊 Total de Reportes:</span>
                                <strong style="color: #1f2937; font-size: 18px;">${parroquia.total_reportes}</strong>
                            </div>
                        </div>
                        
                        <div style="display: grid; gap: 8px;">
                            <div style="display: flex; justify-content: space-between; padding: 10px; background-color: #d1fae5; border-radius: 6px; border-left: 4px solid #10b981;">
                                <span style="color: #065f46; font-weight: 600;">✅ Entregados:</span>
                                <strong style="color: #047857; font-size: 16px;">${parroquia.entregados}</strong>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; padding: 10px; background-color: #fef3c7; border-radius: 6px; border-left: 4px solid #f59e0b;">
                                <span style="color: #92400e; font-weight: 600;">🔄 En Proceso:</span>
                                <strong style="color: #b45309; font-size: 16px;">${parroquia.en_proceso}</strong>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; padding: 10px; background-color: #fee2e2; border-radius: 6px; border-left: 4px solid #ef4444;">
                                <span style="color: #991b1b; font-weight: 600;">❌ No Entregados:</span>
                                <strong style="color: #dc2626; font-size: 16px;">${parroquia.no_entregados}</strong>
                            </div>
                        </div>
                        
                        <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                            <div style="color: #6b7280; font-size: 11px; text-align: center;">
                                Parroquia del Municipio Escuque, Trujillo
                            </div>
                        </div>
                    </div>
                `;

                // Crear ventana de información
                const infoWindow = new google.maps.InfoWindow({
                    content: contentString,
                });

                // Crear marcador
                const marker = new google.maps.Marker({
                    position: { lat: parroquia.lat, lng: parroquia.lng },
                    map: map,
                    icon: image,
                    shape: shape,
                    title: parroquia.nombre,
                    zIndex: i + 1,
                });

                // Agregar evento click para mostrar ventana de información
                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });
            }
        }

        window.initMap = initMap;
    </script>

    <!-- Cargar Google Maps API con la key del profesor -->
    <script 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&v=weekly"
        defer>
    </script>
</body>
</html>
