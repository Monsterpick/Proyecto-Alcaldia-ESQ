<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'settings' => $this->getSettings(),
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
        ];
    }

    /**
     * Obtener configuraciones del sistema para compartir con todas las páginas
     */
    protected function getSettings(): array
    {
        try {
            $name = Setting::get('name', config('app.name'));
            // Subtítulo del Hero: mismo texto que el login (parte después de "Sistema de gestión web estadístico")
            $heroSubtitle = trim(Str::after($name, 'Sistema de gestión web estadístico'));
            if ($heroSubtitle === $name) {
                $heroSubtitle = Setting::get('municipality_name', 'Alcaldía de Escuque');
            }
            return [
                'name' => $name,
                'municipality_name' => Setting::get('municipality_name', $heroSubtitle),
                'hero_subtitle' => $heroSubtitle ?: 'Alcaldía de Escuque',
                'description' => Setting::get('description', ''),
                'primary_color' => Setting::get('primary_color', '#b91c1c'),
                'secondary_color' => Setting::get('secondary_color', '#d97706'),
                'accent_color' => Setting::get('accent_color', '#059669'),
                'logo_url' => Setting::get('logo_url'),
                'favicon_url' => Setting::get('favicon_url'),
                'phone' => Setting::get('phone'),
                'email' => Setting::get('email'),
                'address' => Setting::get('address'),
                'whatsapp' => Setting::get('whatsapp'),
                'horario_atencion' => Setting::get('horario_atencion', 'Lunes a Viernes: 8:00 AM - 4:00 PM'),
            ];
        } catch (\Exception $e) {
            // Si hay error (tabla no existe, etc), retornar valores por defecto
            return [
                'name' => config('app.name'),
                'municipality_name' => config('app.name'),
                'hero_subtitle' => 'Alcaldía de Escuque',
                'description' => '',
                'primary_color' => '#b91c1c',
                'secondary_color' => '#d97706',
                'accent_color' => '#059669',
            ];
        }
    }
}
