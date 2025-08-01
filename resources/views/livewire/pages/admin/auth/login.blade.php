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

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        if (!Auth::user()->hasRole(['Paciente'])) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: false);
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

<div class="flex flex-col gap-12">
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-70 h-12 mr-2" src="{{ Storage::url('images/4_logo_horizontal.png') }}" alt="logo">
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
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
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Recordarme</label>
                                </div>
                            </div>
                            {{-- <a href="#" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">¿Olvidaste tu contraseña?</a> --}}
                        </div>
                        <x-button info type="submit" class="w-full" label="Iniciar sesión" icon="arrow-right-end-on-rectangle" spinner/>
                        {{-- <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            ¿No tienes una cuenta? <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Registrate</a>
                        </p> --}}
                    </form>
                </div>
                <div class="flex justify-center items-center mb-4">
                    <livewire:components.teme-switcher />
                </div>
            </div>
        </div>
    </section>
</div>
