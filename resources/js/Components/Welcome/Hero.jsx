import { Link } from '@inertiajs/react';
import { useTheme } from '@/Components/Theme/ThemeProvider';
import Icon from '@/Components/Icons/Icon';
import useInViewAnimation from '@/hooks/useInViewAnimation';

export default function Hero({ settings }) {
    const theme = useTheme();
    const { ref, visible } = useInViewAnimation();

    // Nombre del sistema desde settings (igual que en el login)
    const systemName = (settings?.name || theme.municipality.name || 'Sistema de Gestión').trim();
    // Descripción corta desde settings (Identidad institucional) — se muestra pequeña debajo del nombre
    const shortDescription = settings?.description || 'Plataforma de control, estadísticas, reportes y gestión de beneficios del Municipio Escuque.';

    // Partir el título: "Sistema web estadistico para la" (blanco) + "Alcaldia de escuque" (amarillo)
    const sepParaLa = ' para la ';
    const sepDeLa = ' de la ';
    let titleWhite = systemName;
    let titleYellow = '';
    const nameLower = systemName.toLowerCase();
    if (nameLower.includes(sepParaLa)) {
        const idx = nameLower.indexOf(sepParaLa);
        titleWhite = systemName.slice(0, idx + sepParaLa.length);
        titleYellow = systemName.slice(idx + sepParaLa.length).trim();
    } else if (nameLower.includes(sepDeLa)) {
        const idx = nameLower.indexOf(sepDeLa);
        titleWhite = systemName.slice(0, idx + sepDeLa.length);
        titleYellow = systemName.slice(idx + sepDeLa.length).trim();
    } else {
        const words = systemName.split(/\s+/);
        if (words.length > 1) {
            titleYellow = words.pop();
            titleWhite = words.join(' ');
        }
    }

    const handleNavClick = (e) => {
        e.preventDefault();
        const target = document.querySelector(e.currentTarget.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    return (
        <section 
            id="inicio" 
            ref={ref}
            className={`relative min-h-screen flex items-center justify-center overflow-hidden pt-24 sm:pt-28 transition-[opacity,transform] duration-700 ease-out ${
                visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
            }`}
        >
            {/* Fondo con imagen */}
            <div className="absolute inset-0">
                <img 
                    src="/fondo.png" 
                    alt={theme.municipality.name} 
                    className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
            </div>

            {/* Contenido */}
            <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                {/* Logo */}
                <div className="mb-8 flex justify-center">
                    <img 
                        src="/logo-alcaldia-escuque.png" 
                        alt={`Escudo ${theme.municipality.name}`} 
                        className="h-32 sm:h-40 md:h-48 w-auto"
                        style={{ 
                            filter: 'drop-shadow(0 0 12px rgba(255,255,255,0.8)) drop-shadow(0 0 25px rgba(255,255,255,0.5))' 
                        }}
                    />
                </div>

                {/* Título: "Sistema web estadistico para la" (blanco) + "Alcaldia de escuque" (amarillo) */}
                <h1 className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold mb-3 sm:mb-4 leading-tight text-white drop-shadow-2xl px-2">
                    {titleYellow ? (
                        <>
                            {titleWhite}
                            <span className="block mt-1 sm:mt-2" style={{ color: theme.colors.secondary }}>
                                {titleYellow}
                            </span>
                        </>
                    ) : (
                        systemName
                    )}
                </h1>

                {/* Descripción corta: pequeña debajo del nombre */}
                <p className="text-sm sm:text-base text-white/95 mb-8 sm:mb-12 max-w-2xl mx-auto font-normal drop-shadow-lg px-2">
                    {shortDescription}
                </p>

                {/* Botones */}
                <div className="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center">
                    <Link
                        href="/login"
                        className="inline-flex items-center justify-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 text-white rounded-lg font-bold text-base sm:text-lg shadow-2xl transition-smooth hover:scale-105 w-full sm:w-auto"
                        style={{ backgroundColor: theme.colors.primary }}
                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = '#991b1b'; }}
                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                    >
                        <Icon name="logout" className="w-5 h-5" />
                        <span>Acceder al Sistema</span>
                    </Link>
                    <a 
                        href="#formulario" 
                        onClick={handleNavClick}
                        className="inline-flex items-center justify-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-white/90 hover:bg-white rounded-lg font-bold text-base sm:text-lg border-2 border-white shadow-xl transition-smooth hover:scale-105 w-full sm:w-auto"
                        style={{ color: theme.colors.primary }}
                    >
                        <span>Solicitar Servicio</span>
                        <Icon name="arrow-right" className="w-5 h-5" />
                    </a>
                    <a 
                        href="#servicios" 
                        onClick={handleNavClick}
                        className="inline-flex items-center justify-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-white/90 hover:bg-white rounded-lg font-bold text-base sm:text-lg border-2 border-white shadow-xl transition-smooth hover:scale-105 w-full sm:w-auto"
                        style={{ color: theme.colors.primary }}
                    >
                        <span>Nuestros Servicios</span>
                        <Icon name="chevron-down" className="w-5 h-5" />
                    </a>
                </div>

                {/* Estadísticas en tarjetas */}
                <div className="mt-12 sm:mt-20 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 max-w-4xl mx-auto px-2">
                    <div className="bg-white/95 backdrop-blur-sm p-4 sm:p-6 rounded-xl shadow-2xl hover-lift">
                        <div className="text-3xl sm:text-4xl font-bold mb-1 sm:mb-2" style={{ color: theme.colors.primary }}>
                            1X10
                        </div>
                        <div className="text-sm sm:text-base text-gray-700 font-semibold">Control de Beneficios</div>
                    </div>
                    <div className="bg-white/95 backdrop-blur-sm p-4 sm:p-6 rounded-xl shadow-2xl hover-lift">
                        <div className="text-3xl sm:text-4xl font-bold mb-1 sm:mb-2" style={{ color: theme.colors.secondary }}>
                            100%
                        </div>
                        <div className="text-sm sm:text-base text-gray-700 font-semibold">Reportes y Estadísticas</div>
                    </div>
                    <div className="bg-white/95 backdrop-blur-sm p-4 sm:p-6 rounded-xl shadow-2xl hover-lift">
                        <div className="text-3xl sm:text-4xl font-bold mb-1 sm:mb-2 text-green-600">
                            24/7
                        </div>
                        <div className="text-sm sm:text-base text-gray-700 font-semibold">Gestión en Tiempo Real</div>
                    </div>
                </div>
            </div>

            {/* Flecha animada hacia abajo */}
            <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                <a 
                    href="#accesos" 
                    onClick={handleNavClick}
                    className="block text-white hover:text-escuque-gold transition-colors"
                >
                    <Icon name="chevron-down" className="w-8 h-8" />
                </a>
            </div>
        </section>
    );
}
