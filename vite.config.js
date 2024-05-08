import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/main.sass',
                'resources/js/main.js',
            ],
            publicDirectory: 'public',
            refresh: true,
        }),
    ],
});
