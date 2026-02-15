import { useState } from 'react';
import { router } from '@inertiajs/react';
import { useTheme } from '@/Components/Theme/ThemeProvider';
import Icon from '@/Components/Icons/Icon';

export default function LoginModal({ isOpen, onClose }) {
    const theme = useTheme();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(false);
    const [errors, setErrors] = useState({});
    const [processing, setProcessing] = useState(false);

    if (!isOpen) return null;

    const handleSubmit = async (e) => {
        e.preventDefault();
        setProcessing(true);
        setErrors({});

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    email,
                    password,
                    remember,
                }),
            });

            const data = await response.json();

            if (response.ok) {
                // Login exitoso - navegar completamente al dashboard
                const dashboardRoute = data.is_beneficiario 
                    ? '/dashboard' 
                    : '/admin/dashboard';
                
                router.visit(dashboardRoute, {
                    method: 'get',
                    preserveState: false,
                    preserveScroll: false,
                });
            } else {
                // Errores de validación
                if (data.errors) {
                    setErrors(data.errors);
                } else if (data.message) {
                    setErrors({ email: data.message });
                }
                setProcessing(false);
            }
        } catch (error) {
            console.error('Error en login:', error);
            setErrors({ email: 'Error de conexión. Por favor, intenta nuevamente.' });
            setProcessing(false);
        }
    };

    return (
        <div 
            className="fixed inset-0 z-[100] flex items-center justify-center p-4"
            onClick={onClose}
        >
            {/* Backdrop */}
            <div className="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            {/* Modal */}
            <div 
                className="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
                onClick={(e) => e.stopPropagation()}
            >
                {/* Header */}
                <div 
                    className="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
                    style={{ backgroundColor: `${theme.colors.primary}10` }}
                >
                    <h2 className="text-2xl font-bold" style={{ color: theme.colors.primary }}>
                        Iniciar Sesión
                    </h2>
                    <button
                        onClick={onClose}
                        className="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        aria-label="Cerrar"
                    >
                        <svg className="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="p-6 space-y-5">
                    {/* Email */}
                    <div>
                        <label htmlFor="email" className="block text-sm font-bold text-gray-700 mb-2">
                            Correo Electrónico <span style={{ color: theme.colors.primary }}>*</span>
                        </label>
                        <input
                            id="email"
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="tu@correo.com"
                            required
                            className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                errors.email ? 'border-red-500 ring-1 ring-red-500' : ''
                            }`}
                            style={{ '--tw-ring-color': theme.colors.primary }}
                            disabled={processing}
                        />
                        {errors.email && (
                            <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                        )}
                    </div>

                    {/* Password */}
                    <div>
                        <label htmlFor="password" className="block text-sm font-bold text-gray-700 mb-2">
                            Contraseña <span style={{ color: theme.colors.primary }}>*</span>
                        </label>
                        <input
                            id="password"
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="••••••••"
                            required
                            className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                errors.password ? 'border-red-500 ring-1 ring-red-500' : ''
                            }`}
                            style={{ '--tw-ring-color': theme.colors.primary }}
                            disabled={processing}
                        />
                        {errors.password && (
                            <p className="mt-1 text-sm text-red-600">{errors.password}</p>
                        )}
                    </div>

                    {/* Remember */}
                    <div className="flex items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            checked={remember}
                            onChange={(e) => setRemember(e.target.checked)}
                            className="w-5 h-5 border-gray-300 rounded focus:ring-2"
                            style={{ 
                                accentColor: theme.colors.primary,
                                '--tw-ring-color': theme.colors.primary 
                            }}
                            disabled={processing}
                        />
                        <label htmlFor="remember" className="ml-2 text-sm text-gray-600">
                            Recordarme
                        </label>
                    </div>

                    {/* Submit Button */}
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full px-6 py-3 rounded-lg font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-2"
                        style={{ backgroundColor: theme.colors.primary }}
                        onMouseEnter={(e) => { if (!processing) e.currentTarget.style.backgroundColor = '#991b1b'; }}
                        onMouseLeave={(e) => { if (!processing) e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                    >
                        {processing ? (
                            <>
                                <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Iniciando sesión...</span>
                            </>
                        ) : (
                            <>
                                <Icon name="logout" className="w-5 h-5" />
                                <span>Iniciar Sesión</span>
                            </>
                        )}
                    </button>
                </form>
            </div>
        </div>
    );
}
