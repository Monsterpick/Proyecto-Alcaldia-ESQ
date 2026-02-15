import { createContext, useContext } from 'react';

const ThemeContext = createContext({});

export function useTheme() {
    return useContext(ThemeContext);
}

export default function ThemeProvider({ children, settings = {} }) {
    // Extraer colores del tema desde settings
    const theme = {
        colors: {
            primary: settings.primary_color || '#b91c1c', // escuque-red por defecto
            secondary: settings.secondary_color || '#d97706', // escuque-gold por defecto
            accent: settings.accent_color || '#059669', // green-600 por defecto
        },
        municipality: {
            name: settings.municipality_name || settings.name || 'Municipio',
            logo: settings.logo_url || null,
            favicon: settings.favicon_url || null,
        },
        contact: {
            phone: settings.phone || null,
            email: settings.email || null,
            address: settings.address || null,
            whatsapp: settings.whatsapp || null,
        },
        ...settings,
    };

    return (
        <ThemeContext.Provider value={theme}>
            {children}
        </ThemeContext.Provider>
    );
}
