<?php

use function Livewire\Volt\{state, mount, computed};
use App\Models\Setting;

// Estados para los valores en tiempo real (vista previa)
state([
    'currency_code' => '',
    'currency_symbol' => '',
    'currency_position' => '',
    'decimal_separator' => '',
    'thousand_separator' => '',
    'available_currencies' => [],
]);

// Estados para los valores guardados
state([
    'saved_currency_code' => '',
    'saved_currency_symbol' => '',
    'saved_currency_position' => '',
    'saved_decimal_separator' => '',
    'saved_thousand_separator' => '',
]);

mount(function () {
    // Cargar valores guardados
    $this->saved_currency_code = Setting::get('currency_code', 'USD');
    $this->saved_currency_symbol = Setting::get('currency_symbol', '$');
    $this->saved_currency_position = Setting::get('currency_position', 'before');
    $this->saved_decimal_separator = Setting::get('decimal_separator', '.');
    $this->saved_thousand_separator = Setting::get('thousand_separator', ',');
    $this->available_currencies = Setting::get('available_currencies', []);

    // Inicializar valores en tiempo real con los valores guardados
    $this->currency_code = $this->saved_currency_code;
    $this->currency_symbol = $this->saved_currency_symbol;
    $this->currency_position = $this->saved_currency_position;
    $this->decimal_separator = $this->saved_decimal_separator;
    $this->thousand_separator = $this->saved_thousand_separator;
});

$saveSettings = function() {
    try {
        // Guardar los valores en la base de datos
        Setting::set('currency_code', $this->currency_code);
        Setting::set('currency_symbol', $this->currency_symbol);
        Setting::set('currency_position', $this->currency_position);
        Setting::set('decimal_separator', $this->decimal_separator);
        Setting::set('thousand_separator', $this->thousand_separator);

        // Actualizar los valores guardados
        $this->saved_currency_code = $this->currency_code;
        $this->saved_currency_symbol = $this->currency_symbol;
        $this->saved_currency_position = $this->currency_position;
        $this->saved_decimal_separator = $this->decimal_separator;
        $this->saved_thousand_separator = $this->thousand_separator;

        $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Configuraciones guardadas correctamente',
                'text' => 'Las configuraciones se han guardado correctamente',
            ]);

    } catch (\Exception $e) {
        $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudieron guardar las configuraciones',
            ]);
    }
};

$hasChanges = computed(function () {
    return $this->currency_code !== $this->saved_currency_code ||
           $this->currency_symbol !== $this->saved_currency_symbol ||
           $this->currency_position !== $this->saved_currency_position ||
           $this->decimal_separator !== $this->saved_decimal_separator ||
           $this->thousand_separator !== $this->saved_thousand_separator;
});

$getCurrencyOptions = computed(function () {
    return collect($this->available_currencies)->map(function ($currency) {
        return [
            'label' => "{$currency['name']} ({$currency['symbol']})",
            'value' => $currency['code']
        ];
    })->toArray();
});

?>

<div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Wireui.hook('notifications:load', () => {
                Livewire.on('settings-updated', (event) => {
                    $wireui.notify({
                        title: event.title,
                        description: event.message,
                        icon: event.status
                    });
                });
            });
        });
    </script>

    <x-card title="Configuración de Moneda">
        <form wire:submit="saveSettings">
            <div class="space-y-6">
                <!-- Moneda Principal -->
                <div>
                    <x-select
                        label="Moneda Principal"
                        placeholder="Selecciona la moneda principal"
                        :options="$this->getCurrencyOptions"
                        option-label="label"
                        option-value="value"
                        wire:model.live="currency_code"
                        description="Esta será la moneda predeterminada del sistema"
                    />
                </div>

                <!-- Símbolo de Moneda -->
                <div>
                    <x-input
                        label="Símbolo de Moneda"
                        wire:model.live="currency_symbol"
                        description="Símbolo que se mostrará junto a los montos"
                    />
                </div>

                <!-- Posición del Símbolo -->
                <div>
                    <x-select
                        label="Posición del Símbolo"
                        option-label="label"
                        option-value="value"
                        :options="[
                            ['label' => 'Antes del monto ($100)', 'value' => 'before'],
                            ['label' => 'Después del monto (100$)', 'value' => 'after'],
                        ]"
                        wire:model.live="currency_position"
                        description="Posición del símbolo"
                    />
                </div>

                <!-- Separador Decimal -->
                <div>
                    <x-select
                        label="Separador Decimal"
                        option-label="label"
                        option-value="value"
                        :options="[
                            ['label' => 'Punto (.)', 'value' => '.'],
                            ['label' => 'Coma (,)', 'value' => ','],
                        ]"
                        wire:model.live="decimal_separator"
                        description="Separador decimal"
                    />
                </div>

                <!-- Separador de Miles -->
                <div>
                    <x-select
                        label="Separador de Miles"
                        option-label="label"
                        option-value="value"
                        :options="[
                            ['label' => 'Coma (1,000)', 'value' => ','],
                            ['label' => 'Punto (1.000)', 'value' => '.'],
                        ]"
                        wire:model.live="thousand_separator"
                        description="Separador de miles"
                    />
                </div>

                <!-- Vista Previa -->
                <div class="mt-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Vista Previa</h3>
                    <div class="mt-2">
                        <p class="text-xl text-gray-500 dark:text-gray-400">
                            Ejemplo de formato: 
                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $currency_position === 'before' 
                                    ? $currency_symbol . number_format(1234.56, 2, $decimal_separator, $thousand_separator)
                                    : number_format(1234.56, 2, $decimal_separator, $thousand_separator) . $currency_symbol 
                                }}
                            </span>
                        </p>
                        @if($this->hasChanges)
                            <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                                <x-icon name="exclamation-triangle" class="w-4 h-4 inline-block mr-1" />
                                Hay cambios sin guardar
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Botones -->
                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                <div class="flex justify-end space-x-2 pt-4">
                    <x-button 
                        type="submit"
                        info 
                        :disabled="!$this->hasChanges"
                        label="Guardar Configuración"
                        icon="check"
                    />
                </div>
            </div>
        </form>
    </x-card>
</div>
