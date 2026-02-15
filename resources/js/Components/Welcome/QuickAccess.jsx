import { Link } from '@inertiajs/react';
import { useTheme } from '@/Components/Theme/ThemeProvider';
import Icon from '@/Components/Icons/Icon';
import SectionReveal from '@/Components/UI/SectionReveal';

export default function QuickAccess({ settings }) {
    const theme = useTheme();

    const handleNavClick = (e) => {
        e.preventDefault();
        const target = document.querySelector(e.currentTarget.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    return (
        <SectionReveal id="accesos" className="py-12 sm:py-16 lg:py-20 bg-gradient-to-b from-gray-50 to-white">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center mb-10 sm:mb-14">
                    <h2 className="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-800 mb-3">
                        Accesos Rápidos
                    </h2>
                    <p className="text-gray-600 max-w-2xl mx-auto">
                        Acceda a la información del sistema, servicios municipales y canales de atención ciudadana
                    </p>
                </div>
                <div className="grid md:grid-cols-3 gap-6 sm:gap-8 reveal-stagger">
                    {/* Sistema 1x10 */}
                    <div className="reveal-stagger-item bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-lg hover-lift transition-all"
                        style={{
                            '--hover-border': `${theme.colors.primary}80`
                        }}
                        onMouseEnter={(e) => e.currentTarget.style.borderColor = `${theme.colors.primary}80`}
                        onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                    >
                        <div className="w-14 h-14 rounded-xl flex items-center justify-center mb-5" style={{ backgroundColor: `${theme.colors.primary}1a` }}>
                            <Icon name="chart" className="w-7 h-7" style={{ color: theme.colors.primary }} />
                        </div>
                        <h3 className="text-xl font-bold text-gray-800 mb-2">Sistema 1x10</h3>
                        <p className="text-gray-600 text-sm sm:text-base mb-4">
                            Control de beneficios, estadísticas y reportes del Municipio Escuque en tiempo real.
                        </p>
                        <Link
                            href="/login"
                            className="inline-flex items-center gap-2 font-semibold hover:underline transition-colors"
                            style={{ color: theme.colors.primary }}
                        >
                            Acceder al sistema
                            <Icon name="arrow-right" className="w-4 h-4" />
                        </Link>
                    </div>

                    {/* Servicios Municipales */}
                    <div className="reveal-stagger-item bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-lg hover-lift transition-all"
                        onMouseEnter={(e) => e.currentTarget.style.borderColor = `${theme.colors.secondary}80`}
                        onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                    >
                        <div className="w-14 h-14 rounded-xl flex items-center justify-center mb-5" style={{ backgroundColor: `${theme.colors.secondary}1a` }}>
                            <Icon name="briefcase" className="w-7 h-7" style={{ color: theme.colors.secondary }} />
                        </div>
                        <h3 className="text-xl font-bold text-gray-800 mb-2">Servicios Municipales</h3>
                        <p className="text-gray-600 text-sm sm:text-base mb-4">
                            Ayudas sociales, educación, salud, empleo y participación ciudadana.
                        </p>
                        <a 
                            href="#servicios" 
                            onClick={handleNavClick}
                            className="inline-flex items-center gap-2 font-semibold hover:underline transition-colors"
                            style={{ color: theme.colors.secondary }}
                        >
                            Ver servicios
                            <Icon name="arrow-right" className="w-4 h-4" />
                        </a>
                    </div>

                    {/* Solicitar Atención */}
                    <div className="reveal-stagger-item bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-lg hover-lift transition-all"
                        onMouseEnter={(e) => e.currentTarget.style.borderColor = '#16a34a80'}
                        onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                    >
                        <div className="w-14 h-14 rounded-xl bg-green-600/10 flex items-center justify-center mb-5">
                            <Icon name="document" className="w-7 h-7 text-green-600" />
                        </div>
                        <h3 className="text-xl font-bold text-gray-800 mb-2">Solicitar Atención</h3>
                        <p className="text-gray-600 text-sm sm:text-base mb-4">
                            Completa el formulario y un funcionario se pondrá en contacto contigo.
                        </p>
                        <a 
                            href="#formulario" 
                            onClick={handleNavClick}
                            className="inline-flex items-center gap-2 text-green-600 font-semibold hover:underline transition-colors"
                        >
                            Ir al formulario
                            <Icon name="arrow-right" className="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
        </SectionReveal>
    );
}
