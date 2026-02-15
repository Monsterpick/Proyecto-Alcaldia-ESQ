import { useTheme } from '@/Components/Theme/ThemeProvider';
import SectionReveal from '@/Components/UI/SectionReveal';

export default function Footer({ settings }) {
    const theme = useTheme();
    const currentYear = new Date().getFullYear();

    return (
        <SectionReveal
            as="footer"
            className="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-12 sm:py-16"
            style={{ borderTop: `4px solid ${theme.colors.primary}` }}
        >
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center">
                    <p className="text-gray-400">
                        &copy; {currentYear} Sistema de gestión web estadístico para la Alcaldía de Escuque. Todos los derechos reservados.
                    </p>
                    <p className="text-gray-500 text-sm mt-2">
                        Desarrollado por <span className="font-semibold" style={{ color: theme.colors.secondary }}>AG 1.0</span>
                    </p>
                </div>
            </div>
        </SectionReveal>
    );
}
