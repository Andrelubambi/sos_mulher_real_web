import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/modern-chat.css',
                'resources/js/app.js',
                'resources/js/chat/index.js',
                'resources/js/chat/echo.js',
                'resources/js/chat/ui.js',
                'resources/js/chat/chat.js',
                'resources/js/chat/video.js',
            ],
            refresh: true,
        }),
    ],
});
