<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('livewire.layout.admin.admin')] class extends Component {

    public string $color_primary = '#5C0A1E';
    public string $color_secondary = '#7A1232';
    public string $color_buttons = '#5C0A1E';

    public function mount(): void
    {
        // Asegurar que los settings existen
        $defaults = [
            'color_primary' => ['value' => '#5C0A1E', 'name' => 'Color Primario', 'description' => 'Color principal de la aplicación (navbar, sidebar)'],
            'color_secondary' => ['value' => '#7A1232', 'name' => 'Color Secundario', 'description' => 'Color secundario para gradientes y detalles'],
            'color_buttons' => ['value' => '#5C0A1E', 'name' => 'Color de Botones', 'description' => 'Color principal de los botones de acción'],
        ];

        foreach ($defaults as $key => $data) {
            Setting::firstOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'type' => 'string',
                    'group' => 'colors',
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_public' => true,
                    'is_tenant_editable' => true,
                ]
            );
        }

        // Cargar valores actuales
        $this->color_primary = Setting::get('color_primary', '#5C0A1E');
        $this->color_secondary = Setting::get('color_secondary', '#7A1232');
        $this->color_buttons = Setting::get('color_buttons', '#5C0A1E');
    }

    public function save(): void
    {
        $this->validate([
            'color_primary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_secondary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_buttons' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Setting::set('color_primary', $this->color_primary);
        Setting::set('color_secondary', $this->color_secondary);
        Setting::set('color_buttons', $this->color_buttons);

        // Limpiar cache para que se apliquen los nuevos colores
        cache()->forget('app_colors');

        // Aplicar colores en tiempo real sin recargar
        $this->dispatch('colors-updated', [
            'primary' => $this->color_primary,
            'secondary' => $this->color_secondary,
            'buttons' => $this->color_buttons,
        ]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Colores guardados',
            'text' => 'Los colores institucionales se han actualizado correctamente.',
            'showConfirmButton' => true,
            'timer' => 3000,
        ]);
    }

    public function resetColors(): void
    {
        $this->color_primary = '#5C0A1E';
        $this->color_secondary = '#7A1232';
        $this->color_buttons = '#5C0A1E';

        Setting::set('color_primary', $this->color_primary);
        Setting::set('color_secondary', $this->color_secondary);
        Setting::set('color_buttons', $this->color_buttons);

        cache()->forget('app_colors');

        // Aplicar colores en tiempo real sin recargar
        $this->dispatch('colors-updated', [
            'primary' => $this->color_primary,
            'secondary' => $this->color_secondary,
            'buttons' => $this->color_buttons,
        ]);

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Colores restaurados',
            'text' => 'Se han restaurado los colores predeterminados.',
            'showConfirmButton' => true,
            'timer' => 3000,
        ]);
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.settings.general') }}" wire:navigate class="text-sm font-medium" style="color: var(--color-text-tertiary);">
                        Configuración
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-xs mx-2" style="color: var(--color-text-muted);"></i>
                        <span class="text-sm font-medium" style="color: var(--color-text-primary);">Colores</span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="max-w-5xl mx-auto"
         x-data="{
            primary: @entangle('color_primary'),
            secondary: @entangle('color_secondary'),
            buttons: @entangle('color_buttons')
         }">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background-color: var(--color-bg-secondary);">
                <i class="fa-solid fa-palette text-2xl" style="color: var(--color-blue-600);"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold" style="color: var(--color-text-primary);">
                Configuración de Colores
            </h1>
            <p class="mt-2 text-sm" style="color: var(--color-text-tertiary);">
                Personaliza los colores institucionales de tu aplicación
            </p>
        </div>

        <!-- Cards de colores -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Color Primario -->
            <div class="rounded-2xl p-6 border-2 transition-all duration-300"
                 style="background-color: var(--color-bg-primary); box-shadow: var(--shadow-md);"
                 :style="{ borderColor: primary }">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md"
                         :style="{ backgroundColor: primary }">
                        <i class="fa-solid fa-paintbrush text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm" style="color: var(--color-text-primary);">Color Primario</h3>
                        <p class="text-xs" style="color: var(--color-text-tertiary);">Headers y elementos principales</p>
                    </div>
                </div>

                <x-color-picker
                    label="Selecciona el color"
                    placeholder="Elige un color primario"
                    wire:model.live="color_primary"
                />

                <!-- Preview -->
                <div class="w-full h-20 rounded-xl mt-4 transition-all duration-300 shadow-inner"
                     :style="{ backgroundColor: primary }">
                </div>

                <p class="text-center text-xs font-medium mt-2" style="color: var(--color-text-tertiary);">
                    CÓDIGO HEX
                </p>
                <p class="text-center text-sm font-bold font-mono" style="color: var(--color-text-primary);"
                   x-text="primary">
                </p>
            </div>

            <!-- Color Secundario -->
            <div class="rounded-2xl p-6 border-2 transition-all duration-300"
                 style="background-color: var(--color-bg-primary); box-shadow: var(--shadow-md);"
                 :style="{ borderColor: secondary }">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md"
                         :style="{ backgroundColor: secondary }">
                        <i class="fa-solid fa-swatchbook text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm" style="color: var(--color-text-primary);">Color Secundario</h3>
                        <p class="text-xs" style="color: var(--color-text-tertiary);">Gradientes y detalles</p>
                    </div>
                </div>

                <x-color-picker
                    label="Selecciona el color"
                    placeholder="Elige un color secundario"
                    wire:model.live="color_secondary"
                />

                <!-- Preview -->
                <div class="w-full h-20 rounded-xl mt-4 transition-all duration-300 shadow-inner"
                     :style="{ backgroundColor: secondary }">
                </div>

                <p class="text-center text-xs font-medium mt-2" style="color: var(--color-text-tertiary);">
                    CÓDIGO HEX
                </p>
                <p class="text-center text-sm font-bold font-mono" style="color: var(--color-text-primary);"
                   x-text="secondary">
                </p>
            </div>

            <!-- Color Botones -->
            <div class="rounded-2xl p-6 border-2 transition-all duration-300"
                 style="background-color: var(--color-bg-primary); box-shadow: var(--shadow-md);"
                 :style="{ borderColor: buttons }">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md"
                         :style="{ backgroundColor: buttons }">
                        <i class="fa-solid fa-hand-pointer text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm" style="color: var(--color-text-primary);">Color Botones</h3>
                        <p class="text-xs" style="color: var(--color-text-tertiary);">Todos los botones de acción</p>
                    </div>
                </div>

                <x-color-picker
                    label="Selecciona el color"
                    placeholder="Elige un color para botones"
                    wire:model.live="color_buttons"
                />

                <!-- Preview -->
                <div class="w-full h-20 rounded-xl mt-4 transition-all duration-300 shadow-inner"
                     :style="{ backgroundColor: buttons }">
                </div>

                <p class="text-center text-xs font-medium mt-2" style="color: var(--color-text-tertiary);">
                    CÓDIGO HEX
                </p>
                <p class="text-center text-sm font-bold font-mono" style="color: var(--color-text-primary);"
                   x-text="buttons">
                </p>
            </div>
        </div>

        <!-- Preview en vivo del gradiente -->
        <div class="rounded-2xl p-6 mb-6" style="background-color: var(--color-bg-primary); box-shadow: var(--shadow-md);">
            <h3 class="font-bold text-sm mb-3" style="color: var(--color-text-primary);">
                <i class="fa-solid fa-eye mr-2"></i>Vista previa del gradiente (Navbar)
            </h3>
            <div class="w-full h-14 rounded-xl transition-all duration-300"
                 :style="{ background: 'linear-gradient(to right, ' + primary + ', ' + secondary + ', ' + primary + ')' }">
            </div>
        </div>

        <!-- Tip -->
        <div class="rounded-xl p-4 mb-8 flex items-center gap-3" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-primary);">
            <span class="text-xl">💡</span>
            <p class="text-sm" style="color: var(--color-text-secondary);">
                <strong>Tip:</strong> Los cambios se aplicarán en toda la aplicación al guardar y recargar la página.
            </p>
        </div>

        <!-- Botones -->
        <div class="flex justify-center gap-4">
            <button wire:click="resetColors"
                    wire:confirm="¿Restaurar los colores predeterminados?"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium rounded-xl border transition-colors"
                    style="border-color: var(--color-border-secondary); color: var(--color-text-secondary); background-color: var(--color-bg-primary);"
                    onmouseover="this.style.backgroundColor='var(--color-bg-hover)'"
                    onmouseout="this.style.backgroundColor='var(--color-bg-primary)'">
                <i class="fa-solid fa-rotate-left"></i>
                Restaurar
            </button>

            <button wire:click="save"
                    class="inline-flex items-center gap-2 px-8 py-2.5 text-sm font-semibold text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl"
                    :style="{ backgroundColor: buttons }"
                    style="opacity: 0.95;"
                    onmouseover="this.style.opacity='1'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.opacity='0.95'; this.style.transform='translateY(0)'">
                <i class="fa-solid fa-check"></i>
                Guardar Colores
            </button>
        </div>
    </div>
</div>
