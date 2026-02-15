<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
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

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        if (Auth::user()->hasRole(['admin', 'Super Admin', 'Coordinador', 'Operador', 'Administrador'])) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: false);
        } else if (Auth::user()->hasRole(['Beneficiario', 'user'])) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
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

<div class="min-h-screen flex">
    
    <!-- Left Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-gradient-to-br from-gray-50 to-gray-100 relative overflow-hidden">
        
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-yellow-400 via-blue-600 to-red-600"></div>
        <div class="absolute top-20 -left-20 w-72 h-72 bg-red-600/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 -right-20 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>

        <div class="w-full max-w-md relative z-10">
            
            <!-- Logo and Title -->
            <div class="text-center mb-8 animate-fade-in-down">
                <div class="inline-block bg-white rounded-2xl p-6 shadow-2xl mb-6 transform hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('images/logo-alcaldia.jpg') }}" 
                         alt="Alcaldía de Escuque" 
                         class="h-24 w-auto mx-auto">
                </div>
                <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
                    <span class="text-red-700">Alcaldía Bolivariana</span>
                </h1>
                <p class="text-lg font-semibold text-gray-600">
                    Municipio Escuque
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Sistema de Gestión de Beneficios Sociales
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 border-t-4 border-red-700 animate-fade-in-up">
                
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        Bienvenido de nuevo
                    </h2>
                    <p class="text-gray-600">
                        Ingresa tus credenciales para acceder al sistema
                    </p>
                </div>

                <form wire:submit="login" class="space-y-6">
                    
                    <!-- Email Input -->
                    <div>
                        <x-input 
                            label="Correo Electrónico" 
                            id="email" 
                            type="email" 
                            wire:model="email" 
                            required
                            placeholder="nombre@dominio.com"
                            icon="envelope"
                            class="transition-all duration-300 focus:ring-2 focus:ring-red-700 focus:border-transparent" />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <x-password 
                            label="Contraseña" 
                            id="password" 
                            type="password" 
                            wire:model="password" 
                            required
                            placeholder="••••••••"
                            class="transition-all duration-300 focus:ring-2 focus:ring-red-700 focus:border-transparent" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <x-checkbox 
                                id="remember" 
                                wire:model="remember" 
                                class="text-red-700 focus:ring-red-700" />
                            <label for="remember" class="ml-2 text-sm text-gray-700 font-medium">
                                Recordarme
                            </label>
                        </div>
                        {{-- Uncomment if needed
                        <a href="#" class="text-sm font-semibold text-red-700 hover:text-red-800 transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                        --}}
                    </div>

                    <!-- Submit Button -->
                    <div class="space-y-4">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white rounded-xl font-bold text-lg shadow-xl shadow-red-700/30 hover:shadow-2xl hover:shadow-red-700/40 transition-all duration-300 transform hover:scale-105"
                                wire:loading.attr="disabled"
                                wire:target="login">
                            <span wire:loading.remove wire:target="login">Iniciar Sesión</span>
                            <span wire:loading wire:target="login">Ingresando...</span>
                            <svg wire:loading.remove wire:target="login" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                            <svg wire:loading wire:target="login" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        {{-- Uncomment if you need registration
                        <p class="text-center text-sm text-gray-600">
                            ¿No tienes una cuenta? 
                            <a href="#" class="font-semibold text-red-700 hover:text-red-800 transition-colors">
                                Regístrate aquí
                            </a>
                        </p>
                        --}}
                    </div>

                </form>

                <!-- Divider -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-center text-xs text-gray-500">
                        Al ingresar, aceptas nuestros 
                        <a href="#" class="text-red-700 hover:text-red-800 font-semibold">Términos de Servicio</a> 
                        y 
                        <a href="#" class="text-red-700 hover:text-red-800 font-semibold">Política de Privacidad</a>
                    </p>
                </div>

            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-red-700 font-semibold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Volver al inicio</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Right Side - Image/Info -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/alcaldia-building.webp') }}" 
                 alt="Alcaldía de Escuque" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-red-900/90 via-red-800/85 to-amber-700/80"></div>
        </div>

        <!-- Content Overlay -->
        <div class="relative z-10 flex flex-col justify-center px-16 py-12 text-white">
            
            <div class="animate-fade-in-up">
                <div class="mb-8">
                    <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6 border border-white/20">
                        <p class="text-sm font-semibold">Portal Oficial</p>
                    </div>
                    <h2 class="text-5xl font-extrabold mb-4 leading-tight drop-shadow-2xl">
                        Sistema de Gestión
                        <span class="block text-amber-300 mt-2">Alcaldía de Escuque</span>
                    </h2>
                    <p class="text-xl text-white/90 mb-8 leading-relaxed">
                        Plataforma integral para la administración de beneficios sociales y atención ciudadana
                    </p>
                </div>

                <!-- Features -->
                <div class="space-y-6 mb-12">
                    <div class="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300">
                        <div class="w-12 h-12 bg-amber-400 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-red-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Seguridad Garantizada</h3>
                            <p class="text-white/80 text-sm">Protección de datos con encriptación de nivel gubernamental</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300">
                        <div class="w-12 h-12 bg-amber-400 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-red-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Acceso Rápido</h3>
                            <p class="text-white/80 text-sm">Gestión eficiente de servicios y beneficios sociales</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300">
                        <div class="w-12 h-12 bg-amber-400 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-red-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Atención Ciudadana</h3>
                            <p class="text-white/80 text-sm">Seguimiento personalizado de solicitudes y trámites</p>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-amber-300 mb-1">15K+</div>
                        <div class="text-sm text-white/80">Ciudadanos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-amber-300 mb-1">24/7</div>
                        <div class="text-sm text-white/80">Disponible</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-amber-300 mb-1">100%</div>
                        <div class="text-sm text-white/80">Seguro</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom decoration -->
        <div class="absolute bottom-0 left-0 right-0 h-2 bg-gradient-to-r from-yellow-400 via-blue-600 to-red-600"></div>

    </div>

</div>

<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
