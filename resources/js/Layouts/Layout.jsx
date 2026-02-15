import { usePage } from '@inertiajs/react';
import ThemeProvider from '@/Components/Theme/ThemeProvider';
import Navbar from '@/Components/Layout/Navbar';

export default function Layout({ children, settings = {} }) {
    const { props } = usePage();
    
    // Combinar settings de props con los pasados directamente
    const themeSettings = {
        ...props.settings,
        ...settings,
    };

    return (
        <ThemeProvider settings={themeSettings}>
            <div className="min-h-screen bg-white">
                {/* Barra colores Venezuela */}
                <div className="w-full h-2 bg-gradient-to-r from-[#FFCC00] via-[#00247D] to-[#CF142B]"></div>
                <Navbar settings={themeSettings} />
                <main>{children}</main>
            </div>
        </ThemeProvider>
    );
}
