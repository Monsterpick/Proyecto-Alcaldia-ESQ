<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ThemeSettingsSeeder extends Seeder
{
    /**
     * Seed configuraciones de tema personalizables por alcaldía
     */
    public function run(): void
    {
        $themeSettings = [
            [
                'key' => 'primary_color',
                'value' => '#b91c1c',
                'type' => 'string',
                'group' => 'theme',
                'name' => 'Color Principal',
                'description' => 'Color principal del tema (usado en botones, enlaces, acentos)',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'secondary_color',
                'value' => '#d97706',
                'type' => 'string',
                'group' => 'theme',
                'name' => 'Color Secundario',
                'description' => 'Color secundario del tema (usado en elementos complementarios)',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'accent_color',
                'value' => '#059669',
                'type' => 'string',
                'group' => 'theme',
                'name' => 'Color de Acento',
                'description' => 'Color de acento para destacar elementos importantes',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'municipality_name',
                'value' => 'Municipio Escuque',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Nombre del Municipio',
                'description' => 'Nombre oficial del municipio o alcaldía',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'logo_url',
                'value' => null,
                'type' => 'string',
                'group' => 'theme',
                'name' => 'URL del Logo',
                'description' => 'URL o ruta del logo del municipio',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'favicon_url',
                'value' => null,
                'type' => 'string',
                'group' => 'theme',
                'name' => 'URL del Favicon',
                'description' => 'URL o ruta del favicon del sitio',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
        ];

        foreach ($themeSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Configuraciones de tema creadas/actualizadas');
    }
}
