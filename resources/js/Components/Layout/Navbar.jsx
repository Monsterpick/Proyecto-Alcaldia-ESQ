import { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { useTheme } from '@/Components/Theme/ThemeProvider';
import Icon from '@/Components/Icons/Icon';

export default function Navbar({ settings }) {
    const theme = useTheme();
    const { auth } = usePage().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [dropdownOpen, setDropdownOpen] = useState(false);

    useEffect(() => {
        // Cerrar menú móvil al hacer clic fuera
        const handleClickOutside = (e) => {
            if (mobileMenuOpen && !e.target.closest('.mobile-menu') && !e.target.closest('.mobile-menu-button')) {
                setMobileMenuOpen(false);
            }
            if (dropdownOpen && !e.target.closest('.dropdown-container')) {
                setDropdownOpen(false);
            }
        };

        document.addEventListener('click', handleClickOutside);
        return () => document.removeEventListener('click', handleClickOutside);
    }, [mobileMenuOpen, dropdownOpen]);

    const handleNavClick = (e) => {
        e.preventDefault();
        const target = document.querySelector(e.currentTarget.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            setMobileMenuOpen(false);
        }
    };

    return (
        <nav 
            className="fixed w-full z-50 top-0 sm:top-2 bg-white shadow-xl"
            style={{ borderBottom: `4px solid ${theme.colors.primary}` }}
        >
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-16 sm:h-20 lg:h-24">
                    <div className="flex items-center gap-2 sm:gap-4 min-w-0">
                        <img 
                            src="/logo-alcaldia-escuque.png" 
                            alt={theme.municipality.name} 
                            className="h-12 sm:h-16 lg:h-20 w-auto object-contain flex-shrink-0"
                        />
                        <div className="hidden lg:block">
                            <h1 className="font-extrabold text-sm xl:text-base leading-tight" style={{ color: theme.colors.primary }}>
                                Sistema de gestión web estadístico
                            </h1>
                            <p className="text-gray-700 font-bold text-base xl:text-lg">
                                {theme.municipality.name}
                            </p>
                        </div>
                    </div>

                    <div className="hidden lg:flex items-center gap-2">
                        <a 
                            href="#inicio" 
                            onClick={handleNavClick}
                            className="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth inline-flex items-center gap-2"
                            style={{ 
                                '--hover-bg': theme.colors.primary 
                            }}
                            onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                        >
                            <Icon name="home" className="w-5 h-5" />
                            Inicio
                        </a>
                        <a 
                            href="#servicios" 
                            onClick={handleNavClick}
                            className="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth inline-flex items-center gap-2"
                            onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                        >
                            <Icon name="briefcase" className="w-5 h-5" />
                            Servicios
                        </a>
                        <a 
                            href="#formulario" 
                            onClick={handleNavClick}
                            className="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth inline-flex items-center gap-2"
                            onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                        >
                            <Icon name="document" className="w-5 h-5" />
                            Solicitud
                        </a>
                        <a 
                            href="#contacto" 
                            onClick={handleNavClick}
                            className="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth inline-flex items-center gap-2"
                            onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                        >
                            <Icon name="envelope" className="w-5 h-5" />
                            Contacto
                        </a>
                    </div>

                    <div className="flex items-center gap-3">
                        {auth?.user ? (
                            <>
                                {!auth.user.roles?.some(r => r.name === 'Beneficiario') && (
                                    <a 
                                        href="/admin/dashboard" 
                                        className="hidden sm:flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth"
                                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                                    >
                                        <Icon name="chart" className="w-5 h-5" />
                                        Dashboard
                                    </a>
                                )}
                                {auth.user.roles?.some(r => r.name === 'Beneficiario') && (
                                    <a 
                                        href="/admin/dashboard" 
                                        className="hidden sm:flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth"
                                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                                    >
                                        <Icon name="chart" className="w-5 h-5" />
                                        Panel
                                    </a>
                                )}
                                <div className="relative dropdown-container">
                                    <button 
                                        onClick={() => setDropdownOpen(!dropdownOpen)}
                                        className="flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-white shadow-lg transition-smooth"
                                        style={{ backgroundColor: theme.colors.primary }}
                                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = '#991b1b'; }}
                                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                                    >
                                        <Icon name="user" className="w-5 h-5" />
                                        {auth.user.name} {auth.user.last_name}
                                        <Icon name="chevron-down" className="w-4 h-4 ml-2" />
                                    </button>
                                    {dropdownOpen && (
                                        <div className="z-10 absolute right-0 mt-1 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 border border-gray-200">
                                            <ul className="py-2 text-sm text-gray-700">
                                                <li>
                                                    <a 
                                                        href="/admin/dashboard" 
                                                        className="block px-4 py-2 hover:bg-gray-100 rounded-t-lg flex items-center gap-2"
                                                        onClick={() => setDropdownOpen(false)}
                                                    >
                                                        <Icon name="gauge" className="w-4 h-4" />
                                                        {auth.user.roles?.some(r => r.name === 'Beneficiario') ? 'Panel' : 'Dashboard'}
                                                    </a>
                                                </li>
                                                <li>
                                                    <Link 
                                                        href="/admin/settings/profile" 
                                                        className="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2"
                                                        onClick={() => setDropdownOpen(false)}
                                                    >
                                                        <Icon name="user" className="w-4 h-4" />
                                                        Perfil
                                                    </Link>
                                                </li>
                                            </ul>
                                            <div className="py-1">
                                                <form method="POST" action="/logout" className="w-full">
                                                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''} />
                                                    <button 
                                                        type="submit" 
                                                        className="w-full block px-4 py-2 text-left hover:bg-gray-100 text-gray-700 cursor-pointer rounded-b-lg flex items-center gap-2"
                                                    >
                                                        <Icon name="logout" className="w-4 h-4" />
                                                        Cerrar Sesión
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </>
                        ) : (
                            <a 
                                href="/login" 
                                className="hidden sm:flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-white shadow-lg transition-smooth"
                                style={{ backgroundColor: theme.colors.primary }}
                                onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = '#991b1b'; }}
                                onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            >
                                <Icon name="logout" className="w-5 h-5" />
                                Ingresar
                            </a>
                        )}
                        <button 
                            type="button" 
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="mobile-menu-button lg:hidden inline-flex items-center justify-center p-3 rounded-lg text-gray-700 hover:text-white transition-smooth"
                            style={{
                                '--hover-bg': theme.colors.primary
                            }}
                            onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                            aria-label="Abrir menú"
                        >
                            <svg className={`${mobileMenuOpen ? 'hidden' : 'block'} h-6 w-6`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg className={`${mobileMenuOpen ? 'block' : 'hidden'} h-6 w-6`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {/* Menú móvil */}
            <div className={`mobile-menu ${mobileMenuOpen ? '' : 'hidden'} lg:hidden border-t-2 border-gray-200 bg-white`}>
                <div className="px-4 pt-4 pb-6 space-y-2">
                    <a 
                        href="#inicio" 
                        onClick={handleNavClick}
                        className="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth"
                        style={{
                            '--hover-bg': theme.colors.primary
                        }}
                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                    >
                        <Icon name="home" className="w-5 h-5" />
                        Inicio
                    </a>
                    <a 
                        href="#servicios" 
                        onClick={handleNavClick}
                        className="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth"
                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                    >
                        <Icon name="briefcase" className="w-5 h-5" />
                        Servicios
                    </a>
                    <a 
                        href="#formulario" 
                        onClick={handleNavClick}
                        className="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth"
                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                    >
                        <Icon name="document" className="w-5 h-5" />
                        Solicitud
                    </a>
                    <a 
                        href="#contacto" 
                        onClick={handleNavClick}
                        className="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white transition-smooth"
                        onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                        onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                    >
                        <Icon name="envelope" className="w-5 h-5" />
                        Contacto
                    </a>
                    <div className="pt-2 border-t-2 border-gray-200">
                        {auth?.user ? (
                            <a 
                                href="/admin/dashboard" 
                                className="block px-4 py-3 rounded-lg font-bold text-white text-center transition-smooth"
                                style={{ backgroundColor: theme.colors.primary }}
                                onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = '#991b1b'; }}
                                onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                                onClick={() => setMobileMenuOpen(false)}
                            >
                                Dashboard
                            </a>
                        ) : (
                            <a 
                                href="/login" 
                                className="flex items-center justify-center gap-2 px-4 py-3 min-h-[44px] rounded-lg font-bold text-white text-center transition-smooth"
                                style={{ backgroundColor: theme.colors.primary }}
                                onMouseEnter={(e) => { e.currentTarget.style.backgroundColor = '#991b1b'; }}
                                onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                                onClick={() => setMobileMenuOpen(false)}
                            >
                                <Icon name="logout" className="w-5 h-5" />
                                Ingresar
                            </a>
                        )}
                    </div>
                </div>
            </div>
        </nav>
    );
}
