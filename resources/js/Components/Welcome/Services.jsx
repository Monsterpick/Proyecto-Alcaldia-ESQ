import { useTheme } from '@/Components/Theme/ThemeProvider';
import SectionReveal from '@/Components/UI/SectionReveal';

export default function Services({ settings }) {
    const theme = useTheme();

    const services = [
        {
            title: 'Ayudas Sociales',
            description: 'Programas de asistencia directa para familias en situación de vulnerabilidad',
            iconColor: theme.colors.primary,
            borderHover: theme.colors.primary,
            icon: (
                <svg className="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                </svg>
            ),
        },
        {
            title: 'Educación',
            description: 'Becas y programas de apoyo educativo para estudiantes',
            iconColor: '#2563eb',
            borderHover: '#2563eb',
            icon: (
                <svg className="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z"/>
                </svg>
            ),
        },
        {
            title: 'Salud',
            description: 'Acceso a servicios médicos y programas de prevención',
            iconColor: '#16a34a',
            borderHover: '#16a34a',
            icon: (
                <svg className="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                </svg>
            ),
        },
        {
            title: 'Empleo',
            description: 'Bolsa de trabajo y capacitación laboral',
            iconColor: theme.colors.secondary,
            borderHover: theme.colors.secondary,
            icon: (
                <svg className="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path fillRule="evenodd" clipRule="evenodd" d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                    <path d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z"/>
                </svg>
            ),
        },
        {
            title: 'Vivienda',
            description: 'Apoyo para mejoras habitacionales y vivienda social',
            iconColor: '#ea580c',
            borderHover: '#ea580c',
            icon: (
                <svg className="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"/>
                    <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"/>
                </svg>
            ),
        },
        {
            title: 'Participación Ciudadana',
            description: 'Espacios para tu voz en las decisiones municipales',
            iconColor: '#9333ea',
            borderHover: '#9333ea',
            icon: (
                <svg className="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8.25 4.5a3.75 3.75 0 117.5 0v8.25a3.75 3.75 0 11-7.5 0V4.5z"/>
                    <path d="M6 10.5a.75.75 0 01.75.75v1.5a5.25 5.25 0 1010.5 0v-1.5a.75.75 0 011.5 0v1.5a6.751 6.751 0 01-6 6.709v2.291h3a.75.75 0 010 1.5h-7.5a.75.75 0 010-1.5h3v-2.291a6.751 6.751 0 01-6-6.709v-1.5A.75.75 0 016 10.5z"/>
                </svg>
            ),
        },
    ];

    return (
        <SectionReveal id="servicios" className="py-12 sm:py-16 lg:py-24 bg-white">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center mb-8 sm:mb-16">
                    <h2 className="text-2xl sm:text-4xl lg:text-5xl font-extrabold mb-4 sm:mb-6 text-gray-800">
                        <span style={{ color: theme.colors.primary }}>Servicios</span> Municipales
                    </h2>
                    <p className="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto px-2">
                        Módulos del sistema para gestión, control y estadísticas de los programas municipales
                    </p>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 reveal-stagger">
                    {services.map((service, index) => (
                        <div 
                            key={index}
                            className="reveal-stagger-item bg-white rounded-2xl p-6 sm:p-8 border-2 border-gray-200 shadow-lg hover-lift transition-all"
                            onMouseEnter={(e) => e.currentTarget.style.borderColor = service.borderHover}
                            onMouseLeave={(e) => e.currentTarget.style.borderColor = '#e5e7eb'}
                        >
                            <div 
                                className="w-14 h-14 sm:w-16 sm:h-16 min-w-[3.5rem] min-h-[3.5rem] rounded-xl flex items-center justify-center mb-4 sm:mb-6 flex-shrink-0 text-white"
                                style={{ backgroundColor: service.iconColor }}
                            >
                                {service.icon}
                            </div>
                            <h3 className="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">
                                {service.title}
                            </h3>
                            <p className="text-gray-600 text-sm sm:text-base">
                                {service.description}
                            </p>
                        </div>
                    ))}
                </div>
            </div>
        </SectionReveal>
    );
}
