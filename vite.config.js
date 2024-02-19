import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.sass',
                'resources/js/app.js',
            ],
            publicDirectory: 'public',
            refresh: true,
        }),
    ],
});
