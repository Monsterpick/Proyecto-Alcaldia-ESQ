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

new class extends Component {
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

        $user = Auth::user();

        // Sesión única para Alcalde, Analista, Operador (no Super Admin)
        if ($user->requiereSesionUnica()) {
            $lifetime = (int) config('session.lifetime', 120);
            $sessionActivaReciente = $user->session_last_activity
                ? $user->session_last_activity->gt(now()->subMinutes($lifetime))
                : false;
            $otraSesionActiva = $user->active_session_id
                && $sessionActivaReciente
                && $user->active_session_id !== Session::getId();

            if ($otraSesionActiva) {
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

        if (!$user->hasRole(['Beneficiario'])) {
            $default = $user->hasRole('Analista') ? route('admin.departamentos.index', absolute: false) : route('admin.dashboard', absolute: false);
            $this->redirectIntended(default: $default, navigate: false);
        } else {
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

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="flex min-h-screen">
        <!-- Panel Izquierdo - Información -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-green-600 p-12 flex-col justify-between relative overflow-hidden">
            <!-- Patrón de fondo -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>

            <!-- Contenido -->
            <div class="relative z-10">
                <div class="flex items-center mb-8">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                        <i class="fa-solid fa-building-columns text-5xl text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-3xl font-bold text-white">Alcaldía de Escuque</h1>
                        <p class="text-blue-100">Estado Trujillo, Venezuela</p>
                    </div>
                </div>

                <div class="space-y-6 mt-12">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <div class="flex items-start">
                            <div class="bg-white/20 rounded-lg p-3">
                                <i class="fa-solid fa-hand-holding-heart text-2xl text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-white mb-2">Control de Beneficios 1X10</h3>
                                <p class="text-blue-100">Sistema integral de gestión de beneficios sociales para la comunidad de Escuque</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <div class="flex items-start">
                            <div class="bg-white/20 rounded-lg p-3">
                                <i class="fa-solid fa-boxes-stacked text-2xl text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-white mb-2">Gestión de Inventario</h3>
                                <p class="text-blue-100">Control completo de productos, stock y distribución de ayudas sociales</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <div class="flex items-start">
                            <div class="bg-white/20 rounded-lg p-3">
                                <i class="fa-solid fa-users text-2xl text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-white mb-2">Atención Comunitaria</h3>
                                <p class="text-blue-100">Registro y seguimiento de beneficiarios del programa social</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-white/80 text-sm">
                <p class="flex items-center">
                    <i class="fa-solid fa-location-dot mr-2"></i>
                    Calle Páez, Sector La Loma, Parroquia Escuque
                </p>
                <p class="flex items-center mt-2">
                    <i class="fa-solid fa-phone mr-2"></i>
                    0271-2950133
                </p>
            </div>
        </div>

        <!-- Panel Derecho - Formulario de Login -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo móvil -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center bg-gradient-to-br from-blue-600 to-green-600 rounded-2xl p-4 mb-4">
                        <i class="fa-solid fa-building-columns text-4xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Alcaldía de Escuque</h1>
                    <p class="text-gray-600 dark:text-gray-400">Control de Beneficios 1X10</p>
                </div>

                <!-- Card de Login -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-100 dark:border-gray-700">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Bienvenido</h2>
                        <p class="text-gray-600 dark:text-gray-400">Ingresa tus credenciales para continuar</p>
                    </div>

                    @if (session('error'))
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit="login" class="space-y-6">
                        <div>
                            <x-input 
                                label="Correo Electrónico" 
                                id="email" 
                                type="email" 
                                wire:model="email" 
                                required
                                placeholder="tu@correo.com"
                                icon="envelope"
                            />
                        </div>

                        <div>
                            <x-password 
                                label="Contraseña" 
                                id="password" 
                                wire:model="password" 
                                required
                                placeholder="••••••••"
                            />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <x-checkbox id="remember" wire:model="remember" />
                                <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    Recordarme
                                </label>
                            </div>
                        </div>

                        <x-button 
                            primary 
                            type="submit" 
                            class="w-full" 
                            label="Iniciar Sesión" 
                            icon="arrow-right-end-on-rectangle" 
                            spinner
                            lg
                        />
                    </form>

                    <!-- Divisor -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-center space-x-4">
                            <livewire:components.teme-switcher />
                            <span class="text-sm text-gray-500 dark:text-gray-400">Cambiar tema</span>
                        </div>
                    </div>
                </div>

                <!-- Footer móvil -->
                <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                    <p>© 2025 Alcaldía de Escuque</p>
                    <p class="mt-1">Sistema de Gestión de Beneficios Sociales</p>
                </div>
            </div>
        </div>
    </div>
</div>
