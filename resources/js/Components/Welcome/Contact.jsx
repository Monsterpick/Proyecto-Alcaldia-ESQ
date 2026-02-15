import { useTheme } from '@/Components/Theme/ThemeProvider';
import Icon from '@/Components/Icons/Icon';
import SectionReveal from '@/Components/UI/SectionReveal';

export default function Contact({ settings }) {
    const theme = useTheme();
    
    const lat = '9.295866608435222';
    const lon = '-70.67296915830971';
    const bbox_lon1 = parseFloat(lon) - 0.01;
    const bbox_lon2 = parseFloat(lon) + 0.01;
    const bbox_lat1 = parseFloat(lat) - 0.005;
    const bbox_lat2 = parseFloat(lat) + 0.005;
    
    const horarioAtencion = settings?.horario_atencion || 'Lunes a Viernes: 8:00 AM - 4:00 PM';

    return (
        <SectionReveal id="contacto" className="py-12 sm:py-16 lg:py-24 bg-white">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center mb-8 sm:mb-16">
                    <h2 className="text-2xl sm:text-4xl lg:text-5xl font-extrabold mb-4 sm:mb-6">
                        <span style={{ color: theme.colors.primary }}>Contáctanos</span>
                    </h2>
                    <p className="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">
                        Estamos aquí para servirte. Visítanos o comunícate con nosotros
                    </p>
                </div>
                <div className="grid md:grid-cols-2 gap-8 sm:gap-12 items-start">
                    <div className="space-y-6">
                        {/* Ubicación */}
                        <div 
                            className="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg hover-lift transition-all"
                            onMouseEnter={(e) => e.currentTarget.style.borderColor = theme.colors.primary}
                            onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                        >
                            <div className="flex items-start gap-4">
                                <div 
                                    className="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] rounded-lg flex items-center justify-center flex-shrink-0"
                                    style={{ backgroundColor: theme.colors.primary }}
                                >
                                    <Icon name="map-pin" className="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-bold text-gray-800 mb-2">Ubicación</h3>
                                    <p className="text-gray-600">Calle Páez, Sector La Loma</p>
                                    <p className="text-gray-600">Parroquia Escuque, Estado Trujillo, Venezuela</p>
                                </div>
                            </div>
                        </div>

                        {/* Teléfono */}
                        <div 
                            className="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg hover-lift transition-all"
                            onMouseEnter={(e) => e.currentTarget.style.borderColor = '#16a34a'}
                            onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                        >
                            <div className="flex items-start gap-4">
                                <div className="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <Icon name="phone" className="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-bold text-gray-800 mb-2">Teléfono</h3>
                                    <p className="text-gray-600">0271-2950133</p>
                                </div>
                            </div>
                        </div>

                        {/* Horario de Atención */}
                        <div 
                            className="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg hover-lift transition-all"
                            onMouseEnter={(e) => e.currentTarget.style.borderColor = theme.colors.secondary}
                            onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                        >
                            <div className="flex items-start gap-4">
                                <div 
                                    className="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] rounded-lg flex items-center justify-center flex-shrink-0"
                                    style={{ backgroundColor: theme.colors.secondary }}
                                >
                                    <Icon name="clock" className="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-bold text-gray-800 mb-2">Horario de Atención</h3>
                                    <p className="text-gray-600">{horarioAtencion}</p>
                                </div>
                            </div>
                        </div>

                        {/* Redes Sociales */}
                        <div 
                            className="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg hover-lift transition-all"
                            onMouseEnter={(e) => e.currentTarget.style.borderColor = '#2563eb'}
                            onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                        >
                            <div className="flex items-start gap-4">
                                <div className="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <Icon name="instagram" className="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-bold text-gray-800 mb-2">Redes Sociales</h3>
                                    <a 
                                        href="https://instagram.com/alcaldiadeescuque" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        className="text-blue-600 hover:text-blue-800 font-semibold transition-colors"
                                    >
                                        @alcaldiadeescuque
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Mapa */}
                    <div className="bg-white rounded-xl p-4 border-2 border-gray-200 shadow-xl">
                        <iframe 
                            className="w-full h-[400px] sm:h-[500px] rounded-lg"
                            src={`https://www.openstreetmap.org/export/embed.html?bbox=${bbox_lon1}%2C${bbox_lat1}%2C${bbox_lon2}%2C${bbox_lat2}&amp;layer=mapnik&amp;marker=${lat}%2C${lon}`}
                            style={{ border: 'none' }}
                            title="Ubicación Alcaldía de Escuque"
                        />
                        <div className="mt-4 text-center">
                            <a 
                                href={`https://www.google.com/maps/search/?api=1&query=${lat},${lon}`}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-3 px-8 py-4 text-white rounded-lg font-bold shadow-xl transition-smooth hover:scale-105"
                                style={{ backgroundColor: theme.colors.primary }}
                                onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = '#991b1b'; }}
                                onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            >
                                <Icon name="map-pin" className="w-5 h-5 text-white" />
                                <span>Ver en Google Maps</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </SectionReveal>
    );
}
