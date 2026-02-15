import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',   // Livewire / Blade
                'resources/js/app.jsx',  // Inertia + React
            ],
            refresh: true,
        }),
        react({
            jsxRuntime: 'automatic',
            jsxImportSource: 'react',
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5175,
        cors: true,
        hmr: {
            host: '127.0.0.1',
        },
    },
});