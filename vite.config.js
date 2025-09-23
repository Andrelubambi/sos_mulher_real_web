
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/modern-chat/index.js',
                'resources/js/modern-chat/echo.js',
                'resources/js/modern-chat/ui.js',
                'resources/js/modern-chat/chat.js',
                'resources/js/modern-chat/video.js',
                'resources/vendors/styles/modern-chat.css'
            ],
            refresh: true,
        }),
    ],
});
