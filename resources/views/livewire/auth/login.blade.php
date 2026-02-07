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

<div class="flex flex-col gap-12">
    <section class="relative bg-gray-50 dark:bg-gray-900 overflow-hidden">
        <!-- Imagen de fondo -->
        <div class="absolute inset-0" style="background-image: url('{{ asset('fondo4.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat;"></div>
        <!-- Overlay oscuro -->
        <div class="absolute inset-0 bg-slate-950/60"></div>
        <div class="relative flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="flex flex-col items-center mb-6 text-center">
                <div class="bg-white rounded-2xl p-6 shadow-2xl border-2 border-gray-300 transform hover:scale-105 hover:shadow-blue-500/50 transition-all duration-300">
                    <h1 class="text-3xl font-bold text-black mb-2">
                        Sistema Web de Gestion de la Alcaldia del Municipio Escuque
                    </h1>
                    <p class="text-sm text-gray-800 font-semibold">
                        Sistema de gestión de beneficios sociales
                    </p>
                </div>
            </div>
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 border-2 border-gray-300 transform hover:scale-105 hover:shadow-blue-500/50 transition-all duration-300">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Inicia sesión en tu cuenta
                    </h1>
                    <form wire:submit="login" class="space-y-4 md:space-y-6">
                        <div>
                            <x-input label="Tu correo" id="email" type="email" wire:model="email" required
                                placeholder="nombre@dominio.com" />
                            
                        </div>
                        <div>
                            <x-password label="Tu contraseña" id="password" type="password" wire:model="password" required
                                placeholder="••••••••" />
                            
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <x-checkbox id="remember" aria-describedby="remember" type="checkbox" wire:model="remember" />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-700">Recordarme</label>
                                </div>
                            </div>
                            {{-- <a href="#" class="text-sm font-medium text-primary-600 hover:underline">¿Olvidaste tu contraseña?</a> --}}
                        </div>
                        <x-button info type="submit" class="w-full" label="Iniciar sesión" icon="arrow-right-end-on-rectangle" spinner/>
                        {{-- <p class="text-sm font-light text-gray-500">
                            ¿No tienes una cuenta? <a href="#" class="font-medium text-primary-600 hover:underline">Registrate</a>
                        </p> --}}
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>