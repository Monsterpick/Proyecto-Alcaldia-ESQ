<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('livewire.layout.client.client')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    // Datos institucionales (simple, sin descripciones gigantes ni caché raro)
    public string $brandName = '';
    public string $brandDescription = '';
    public string $brandLongDescription = '';
    public string $brandLogo = '';
    /** Título partido: blanco + amarillo (Sistema web estadistico para la | Alcaldia de escuque) */
    public string $brandTitleWhite = '';
    public string $brandTitleYellow = '';

    public function mount(): void
    {
        $this->brandName = \App\Models\Setting::get('name', 'Sistema de Gestión');
        $this->brandDescription = \App\Models\Setting::get('description', 'Alcaldía de Escuque');

        // Partir nombre para título: "Sistema web estadistico para la" (blanco) + "Alcaldia de escuque" (amarillo)
        $name = trim($this->brandName);
        $nameLower = mb_strtolower($name);
        if (str_contains($nameLower, ' para la ')) {
            $idx = mb_strpos($nameLower, ' para la ');
            $this->brandTitleWhite = mb_substr($name, 0, $idx + strlen(' para la '));
            $this->brandTitleYellow = trim(mb_substr($name, $idx + strlen(' para la ')));
        } elseif (str_contains($nameLower, ' de la ')) {
            $idx = mb_strpos($nameLower, ' de la ');
            $this->brandTitleWhite = mb_substr($name, 0, $idx + strlen(' de la '));
            $this->brandTitleYellow = trim(mb_substr($name, $idx + strlen(' de la ')));
        } else {
            $words = preg_split('/\s+/u', $name);
            $this->brandTitleWhite = $name;
            $this->brandTitleYellow = count($words) > 1 ? array_pop($words) : '';
            if ($this->brandTitleYellow !== '') {
                $this->brandTitleWhite = implode(' ', $words);
            }
        }

        // Texto fijo, corto y limpio (como en tu diseño)
        $this->brandLongDescription = 'Plataforma integral de control, estadísticas, reportes y gestión de beneficios del Municipio Escuque, Estado Trujillo.';

        $logo = \App\Models\Setting::get('logo', '');
        $this->brandLogo = $logo ? asset('storage/' . $logo) : asset('logo-alcaldia-escuque.png');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Sesión única para Alcalde, Analista, Operador (no Super Admin)
        if ($user->requiereSesionUnica() && $user->active_session_id && $user->active_session_id !== Session::getId()) {
            $sessionTable = config('session.table', 'sessions');
            $minutosSinActividadParaConsiderarMuerta = 1;
            $cutoff = time() - ($minutosSinActividadParaConsiderarMuerta * 60);

            // Si la sesión anterior no existe o no ha tenido actividad en 2 min (cerró navegador), permitir login
            $otraSesionRealmenteActiva = DB::table($sessionTable)
                ->where('id', $user->active_session_id)
                ->where('last_activity', '>', $cutoff)
                ->exists();

            if (!$otraSesionRealmenteActiva) {
                $user->update(['active_session_id' => null, 'session_last_activity' => null]);
            } else {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Ya existe una sesión activa con este usuario. Solo se permite una sesión a la vez. Cierre sesión en el otro dispositivo o espere a que expire.',
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $user->update([
            'active_session_id' => Session::getId(),
            'session_last_activity' => now(),
        ]);

        $user = Auth::user();

        if ($user->hasRole('Director')) {
            $director = $user->director;
            if (!$director) {
                Auth::logout();
                throw ValidationException::withMessages(['email' => 'Inicio de sesión inválido: no tienes un perfil de director asignado.']);
            }
            if (!$director->departamento_id) {
                Auth::logout();
                throw ValidationException::withMessages(['email' => 'Inicio de sesión fallido: no perteneces a un departamento.']);
            }
            if (!$director->activo) {
                Auth::logout();
                throw ValidationException::withMessages(['email' => 'Inicio de sesión inválido porque estás inactivo, no puedes acceder al sistema.']);
            }
            if (!$user->password_changed_at) {
                Session::flash('show_password_change_alert', true);
            }
            $this->redirectIntended(default: route('admin.solicitudes.index', absolute: false), navigate: false);
        } elseif ($user->hasRole(['admin', 'Super Admin', 'Coordinador', 'Operador', 'Administrador', 'Alcalde', 'Analista', 'Doctor', 'Recepcionista'])) {
            $default = $user->hasRole('Analista') ? route('admin.departamentos.index', absolute: false) : route('admin.dashboard', absolute: false);
            $this->redirectIntended(default: $default, navigate: false);
        } elseif ($user->hasRole(['Beneficiario', 'user'])) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
        } else {
            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();
            Session::flash('warning', 'Tu usuario no tiene permisos asignados.');
            $this->redirect(route('home', absolute: false), navigate: false);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

@push('styles')
<style>
    /* Animaciones de entrada del login (garantizadas en esta vista) */
    @keyframes loginFadeInUp {
        from { opacity: 0; transform: translate3d(0, 40px, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }
    @keyframes loginFadeInDown {
        from { opacity: 0; transform: translate3d(0, -30px, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }
    @keyframes loginFadeInLeft {
        from { opacity: 0; transform: translate3d(-40px, 0, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }
    @keyframes loginFadeInRight {
        from { opacity: 0; transform: translate3d(40px, 0, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }
    @keyframes loginScaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes loginFloat {
        0%, 100% { transform: translate3d(0, 0, 0); }
        50% { transform: translate3d(0, -12px, 0); }
    }
    .login-override .anim-fade-up {
        animation: loginFadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .login-override .anim-fade-down {
        animation: loginFadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .login-override .anim-fade-left {
        animation: loginFadeInLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .login-override .anim-fade-right {
        animation: loginFadeInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .login-override .anim-scale {
        animation: loginScaleIn 0.6s cubic-bezier(0.34, 1.2, 0.64, 1) forwards;
    }
    .login-override .anim-float {
        animation: loginFloat 5s ease-in-out infinite;
    }
    .login-override .anim-delay-100 { animation-delay: 0.08s; opacity: 0; animation-fill-mode: forwards; }
    .login-override .anim-delay-200 { animation-delay: 0.15s; opacity: 0; animation-fill-mode: forwards; }
    .login-override .anim-delay-300 { animation-delay: 0.22s; opacity: 0; animation-fill-mode: forwards; }
    .login-override .anim-delay-400 { animation-delay: 0.3s; opacity: 0; animation-fill-mode: forwards; }
    .login-override .anim-delay-500 { animation-delay: 0.38s; opacity: 0; animation-fill-mode: forwards; }
    .login-override .anim-delay-600 { animation-delay: 0.46s; opacity: 0; animation-fill-mode: forwards; }
    .login-override .anim-delay-700 { animation-delay: 0.54s; opacity: 0; animation-fill-mode: forwards; }
    @media (prefers-reduced-motion: reduce) {
        .login-override .anim-fade-up, .login-override .anim-fade-down,
        .login-override .anim-fade-left, .login-override .anim-fade-right,
        .login-override .anim-scale, .login-override .anim-float {
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
        }
        .login-override .anim-delay-100, .login-override .anim-delay-200,
        .login-override .anim-delay-300, .login-override .anim-delay-400,
        .login-override .anim-delay-500, .login-override .anim-delay-600,
        .login-override .anim-delay-700 { animation-delay: 0s !important; opacity: 1 !important; }
    }
</style>
@endpush

<div class="login-override fixed inset-0 z-40 flex flex-col overflow-y-auto">

    {{-- Barra Venezuela superior --}}
    <div class="fixed top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#FFCC00] via-[#00247D] to-[#CF142B] z-50 anim-fade-down"></div>

    {{-- Background con gradiente animado --}}
    <div class="fixed inset-0 bg-gradient-to-br from-red-900 via-red-800 to-amber-900 login-bg-gradient" style="background-size: 400% 400%;"></div>

    {{-- Elementos decorativos flotantes --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 -left-32 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl anim-float"></div>
        <div class="absolute top-60 -right-32 w-[500px] h-[500px] bg-red-600/10 rounded-full blur-3xl anim-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl anim-float" style="animation-delay: 4s;"></div>
    </div>

    {{-- Grid pattern overlay --}}
    <div class="fixed inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>

    {{-- Contenido principal --}}
    <div class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-7xl">
            
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">

                {{-- ===== IZQUIERDA - INFORMACIÓN ===== --}}
                <div class="text-center lg:text-left space-y-8">

                    {{-- Logo con animación flotante --}}
                    <div class="mb-8 anim-fade-down flex justify-center lg:justify-start">
                        <div class="anim-float inline-block">
                            <img src="{{ $brandLogo }}" 
                                 alt="{{ $brandName }}" 
                                 class="h-28 sm:h-36 lg:h-44 xl:h-52 w-auto hover-lift"
                                 style="filter: drop-shadow(0 0 15px rgba(255,255,255,0.9)) drop-shadow(0 0 30px rgba(255,255,255,0.6)) drop-shadow(0 0 50px rgba(255,255,255,0.3));">
                        </div>
                    </div>

                    {{-- Título: "Sistema web estadistico para la" (blanco) + "Alcaldia de escuque" (amarillo) --}}
                    <div class="anim-fade-left anim-delay-100">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white leading-tight mb-3">
                            {{ $brandTitleWhite }}
                            @if($brandTitleYellow)
                                <span class="block bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-300 bg-clip-text text-transparent mt-2">
                                    {{ $brandTitleYellow }}
                                </span>
                            @endif
                        </h1>
                    </div>

                    {{-- Descripción corta: pequeña debajo del nombre --}}
                    <p class="text-white/90 text-sm sm:text-base max-w-2xl leading-relaxed anim-fade-left anim-delay-200">
                        {{ $brandDescription }}
                    </p>

                    {{-- Features - Solo desktop --}}
                    <div class="hidden lg:flex flex-col gap-5 anim-fade-left anim-delay-300">
                        
                        <div class="feature-card glass-card rounded-2xl p-5 group cursor-pointer">
                            <div class="flex items-center gap-5">
                                <div class="feature-icon w-14 h-14 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-xl shadow-amber-500/40">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-bold text-lg mb-1">Seguridad Garantizada</h3>
                                    <p class="text-white/70 text-sm">Protección de datos con encriptación de nivel gubernamental</p>
                                </div>
                            </div>
                        </div>

                        <div class="feature-card glass-card rounded-2xl p-5 group cursor-pointer">
                            <div class="flex items-center gap-5">
                                <div class="feature-icon w-14 h-14 rounded-xl bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center shadow-xl shadow-red-600/40">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-bold text-lg mb-1">Acceso Rápido y Eficiente</h3>
                                    <p class="text-white/70 text-sm">Gestión optimizada de servicios y beneficios sociales</p>
                                </div>
                            </div>
                        </div>

                        <div class="feature-card glass-card rounded-2xl p-5 group cursor-pointer">
                            <div class="flex items-center gap-5">
                                <div class="feature-icon w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center shadow-xl shadow-green-600/40">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-bold text-lg mb-1">Atención Ciudadana</h3>
                                    <p class="text-white/70 text-sm">Seguimiento en tiempo real de solicitudes y trámites</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Estadísticas --}}
                    <div class="grid grid-cols-3 gap-4 lg:gap-6 anim-fade-up anim-delay-400">
                        <div class="stat-card glass-card rounded-2xl p-4 lg:p-6 text-center cursor-pointer">
                            <div class="text-3xl lg:text-4xl font-extrabold bg-gradient-to-r from-amber-300 to-amber-500 bg-clip-text text-transparent mb-2">
                                1X10
                            </div>
                            <div class="text-white/80 text-sm lg:text-base font-semibold">Beneficios</div>
                        </div>
                        <div class="stat-card glass-card rounded-2xl p-4 lg:p-6 text-center cursor-pointer">
                            <div class="text-3xl lg:text-4xl font-extrabold bg-gradient-to-r from-amber-300 to-amber-500 bg-clip-text text-transparent mb-2">
                                24/7
                            </div>
                            <div class="text-white/80 text-sm lg:text-base font-semibold">Disponible</div>
                        </div>
                        <div class="stat-card glass-card rounded-2xl p-4 lg:p-6 text-center cursor-pointer">
                            <div class="text-3xl lg:text-4xl font-extrabold bg-gradient-to-r from-amber-300 to-amber-500 bg-clip-text text-transparent mb-2">
                                100%
                            </div>
                            <div class="text-white/80 text-sm lg:text-base font-semibold">Seguro</div>
                        </div>
                    </div>

                </div>

                {{-- ===== DERECHA - FORMULARIO LOGIN ===== --}}
                <div class="w-full max-w-md mx-auto lg:mx-0 anim-fade-right anim-delay-200">
                    <div class="login-card bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden hover-lift border border-white/20">

                        {{-- Barra decorativa con gradiente animado --}}
                        <div class="h-2 bg-gradient-to-r from-[#b91c1c] via-[#d97706] to-[#b91c1c] login-shimmer-bar" style="background-size: 200% 100%;"></div>

                        <div class="p-8 sm:p-10">

                            {{-- Header del formulario --}}
                            <div class="text-center mb-8 anim-scale anim-delay-300">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-red-50 to-amber-50 mb-5 shadow-lg hover-lift">
                                    <svg class="w-10 h-10 login-accent-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-extrabold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-2">
                                    Bienvenido de nuevo
                                </h2>
                                <p class="text-gray-600 text-base">
                                    Ingresa tus credenciales para acceder al sistema
                                </p>
                            </div>

                            @php
                                $sessionReason = session('session_reason');
                                $errorMsg = session('error');
                            @endphp
                            @if ($sessionReason === 'other_device' || $errorMsg)
                                <div class="mb-6 rounded-lg border p-4 text-sm {{ $sessionReason === 'other_device' ? 'border-amber-200 bg-amber-50 text-amber-800' : 'border-red-200 bg-red-50 text-red-800' }}">
                                    <i class="fa-solid fa-{{ $sessionReason === 'other_device' ? 'mobile-screen' : 'triangle-exclamation' }} mr-2"></i>
                                    {{ $sessionReason === 'other_device' ? 'Su sesión fue cerrada porque inició sesión desde otro dispositivo.' : ($errorMsg ?: 'Cierre de sesión inesperado.') }}
                                </div>
                            @endif

                            {{-- Formulario --}}
                            <form wire:submit="login" class="space-y-6">

                                {{-- Email --}}
                                <div class="anim-fade-up anim-delay-400">
                                    <x-input
                                        label="Correo Electrónico"
                                        id="email"
                                        type="email"
                                        wire:model="email"
                                        required
                                        placeholder="nombre@dominio.com"
                                        icon="envelope" />
                                </div>

                                {{-- Password --}}
                                <div class="anim-fade-up anim-delay-500">
                                    <x-password
                                        label="Contraseña"
                                        id="password"
                                        type="password"
                                        wire:model="password"
                                        required
                                        placeholder="••••••••" />
                                </div>

                                {{-- Recordarme --}}
                                <div class="flex items-center justify-between anim-fade-up anim-delay-600">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <x-checkbox id="remember" wire:model="remember" />
                                        <span class="text-sm text-gray-700 font-medium group-hover:text-gray-900 transition-colors">
                                            Recordarme
                                        </span>
                                    </label>
                                </div>

                                {{-- Botón Login con efectos avanzados --}}
                                <div class="anim-fade-up anim-delay-700">
                                    <button type="submit"
                                            class="w-full btn-gradient ripple-effect flex items-center justify-center gap-3 px-6 py-4 text-white rounded-xl font-bold text-lg shadow-2xl shadow-red-700/40 hover:shadow-red-700/60 active:scale-[0.97] transition-all duration-300 cursor-pointer bg-gradient-to-r from-red-700 via-red-600 to-red-700 hover:from-red-600 hover:via-red-500 hover:to-red-600"
                                            wire:loading.attr="disabled"
                                            wire:target="login">
                                        
                                        <span wire:loading.remove wire:target="login" class="flex items-center gap-3">
                                            <span>Iniciar Sesión</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </span>

                                        <span wire:loading wire:target="login" class="flex items-center gap-3">
                                            <svg class="spinner h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Ingresando...</span>
                                        </span>
                                    </button>
                                </div>

                            </form>

                            {{-- Términos y condiciones --}}
                            <div class="mt-8 pt-6 border-t border-gray-200 anim-fade-up anim-delay-800">
                                <p class="text-center text-xs text-gray-500 leading-relaxed">
                                    Al ingresar, aceptas los 
                                    <a href="#" class="text-[#b91c1c] hover:text-[#991b1b] font-semibold transition-colors">términos de uso</a> 
                                    del sistema municipal
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- Volver al inicio --}}
                    <div class="text-center mt-8 anim-fade-up anim-delay-800">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center gap-2.5 text-sm text-white/90 hover:text-amber-400 transition-all duration-300 font-semibold group">
                            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Volver al inicio</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Footer --}}
    <div class="relative z-10 text-center py-6 anim-fade-up anim-delay-800">
        <p class="text-white/50 text-sm">
            &copy; {{ date('Y') }} {{ $brandName }} - {{ $brandDescription }}
        </p>
    </div>

    {{-- Barra Venezuela inferior --}}
    <div class="fixed bottom-0 left-0 right-0 h-2 bg-gradient-to-r from-[#CF142B] via-[#00247D] to-[#FFCC00] z-50 anim-fade-up"></div>

</div>
