import { useEffect, useState } from 'react';
import { useTheme } from '@/Components/Theme/ThemeProvider';
import useInViewAnimation from '@/hooks/useInViewAnimation';

export default function StatsBar({ stats = {} }) {
    const theme = useTheme();
    const { ref, visible } = useInViewAnimation();

    const [animated, setAnimated] = useState({
        solicitudes: 0,
        beneficiarios: 0,
        reportes: 0,
    });

    const handleNavClick = (e) => {
        e.preventDefault();
        const target = document.querySelector(e.currentTarget.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    // Animación numérica suave cuando la barra entra en vista
    useEffect(() => {
        if (!visible) return;

        const duration = 800; // ms
        const start = performance.now();

        const from = { solicitudes: 0, beneficiarios: 0, reportes: 0 };
        const to = {
            solicitudes: stats.solicitudes || 0,
            beneficiarios: stats.beneficiarios || 0,
            reportes: stats.reportes || 0,
        };

        const animate = (now) => {
            const t = Math.min((now - start) / duration, 1);
            const easeOut = 1 - Math.pow(1 - t, 3);

            setAnimated({
                solicitudes: Math.round(from.solicitudes + (to.solicitudes - from.solicitudes) * easeOut),
                beneficiarios: Math.round(from.beneficiarios + (to.beneficiarios - from.beneficiarios) * easeOut),
                reportes: Math.round(from.reportes + (to.reportes - from.reportes) * easeOut),
            });

            if (t < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }, [visible, stats.solicitudes, stats.beneficiarios, stats.reportes]);

    return (
        <section
            ref={ref}
            className={`relative z-10 py-6 sm:py-8 bg-white border-b border-gray-200 shadow-lg transition-[opacity,transform] duration-700 ease-out ${
                visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'
            }`}
        >
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6 text-center">
                    <a 
                        href="#formulario" 
                        onClick={handleNavClick}
                        className="group p-4 rounded-xl hover:bg-gray-50 transition-smooth"
                    >
                        <div
                            className="text-2xl sm:text-3xl font-bold group-hover:scale-105 transition-transform"
                            style={{ color: theme.colors.primary }}
                        >
                            {animated.solicitudes}
                        </div>
                        <div className="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Solicitudes</div>
                    </a>
                    <div className="p-4 rounded-xl">
                        <div className="text-2xl sm:text-3xl font-bold" style={{ color: theme.colors.secondary }}>
                            {animated.beneficiarios}
                        </div>
                        <div className="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Beneficiarios</div>
                    </div>
                    <div className="p-4 rounded-xl col-span-2 sm:col-span-1">
                        <div className="text-2xl sm:text-3xl font-bold text-green-600">
                            {animated.reportes}
                        </div>
                        <div className="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Reportes</div>
                    </div>
                    <div className="hidden lg:block p-4 rounded-xl">
                        <div className="text-2xl sm:text-3xl font-bold text-blue-600">1X10</div>
                        <div className="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Control</div>
                    </div>
                    <div className="hidden lg:block p-4 rounded-xl">
                        <div className="text-2xl sm:text-3xl font-bold text-gray-700">24/7</div>
                        <div className="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Gestión</div>
                    </div>
                </div>
            </div>
        </section>
    );
}

