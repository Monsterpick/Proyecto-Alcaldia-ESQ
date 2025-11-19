<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Beneficiary;
use App\Models\Report;
use App\Models\Product;
use App\Observers\StatsCacheObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar observers para limpiar cache automáticamente
        Beneficiary::observe(StatsCacheObserver::class);
        Report::observe(StatsCacheObserver::class);
        Product::observe(StatsCacheObserver::class);
        
        // Forzar HTTPS en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Register WireUI view namespaces manually for each component
        $wireUiViewNamespaces = [
            'wireui-text-field' => 'TextField',
            'wireui-select' => 'Select',
            'wireui-button' => 'Button',
            'wireui-mini-button' => 'Button',
            'wireui-alert' => 'Alert',
            'wireui-avatar' => 'Avatar',
            'wireui-badge' => 'Badge',
            'wireui-mini-badge' => 'Badge',
            'wireui-card' => 'Card',
            'wireui-color-picker' => 'ColorPicker',
            'wireui-datetime-picker' => 'DatetimePicker',
            'wireui-dialog' => 'Dialog',
            'wireui-dropdown' => 'Dropdown',
            'wireui-errors' => 'Errors',
            'wireui-icon' => 'Icon',
            'wireui-label' => 'Label',
            'wireui-link' => 'Link',
            'wireui-modal' => 'Modal',
            'wireui-modal-card' => 'Modal',
            'wireui-notifications' => 'Notifications',
            'wireui-popover' => 'Popover',
            'wireui-switcher' => 'Switcher',
            'wireui-time-picker' => 'TimePicker',
            'wireui-wrapper' => 'Wrapper',
        ];

        foreach ($wireUiViewNamespaces as $namespace => $component) {
            $viewPath = base_path("vendor/wireui/wireui/src/Components/{$component}/views");
            if (is_dir($viewPath)) {
                View::addNamespace($namespace, $viewPath);
            }
        }

        // Register WireUI component namespaces manually
        Blade::componentNamespace('WireUi\\Components', 'wireui');
    }
}
