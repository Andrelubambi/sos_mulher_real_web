import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

// Utilizamos uma função de configuração que recebe o modo (mode) para carregar
// as variáveis de ambiente necessárias.
export default defineConfig(({ mode }) => {
    // 1. Carrega todas as variáveis de ambiente do seu .env.dev
    const env = loadEnv(mode, process.cwd(), '');

    // 2. Define a URL do servidor Vite a ser injetada no Blade.
    // Usamos VITE_SERVER_URL (do seu .env.dev) ou voltamos para o padrão 5173.
    const viteAssetUrl = env.VITE_SERVER_URL || 'http://localhost:5173';

    console.log(`Vite Asset URL configurada para Blade: ${viteAssetUrl}`);
    
    return {
        plugins: [
            laravel({
                input: [ 
                    'resources/css/modern-chat.css',
                    'resources/js/app.js',
                    'resources/js/chat/index.js',
                    'resources/js/chat/echo.js',
                    'resources/js/chat/ui.js',
                    'resources/js/chat/chat.js',
                    'resources/js/chat/video.js',
                    'resources/js/ajax.js',
                    'resources/js/bootstrap.js',
                    'resources/js/medicos.js',
                    'resources/js/consultas.js',
                    'resources/js/dashboard-charts.js',
                    'resources/js/notifications.js',
                    'resources/js/vitimas.js',
                ],
                refresh: true,
                
                // ⬇️ ESTE É O BLOCO CRÍTICO ⬇️
                // Força o Laravel a injetar esta URL completa no HTML do Blade,
                // resolvendo o problema de URL incorreta ([::1]:5176).
                assetUrl: viteAssetUrl,
            }),
        ],
        
        server: {
            // Permite que o servidor Vite seja acessível de fora do container
            host: '0.0.0.0', 
            
            // Define a porta que está mapeada no docker-compose (5173:5173)
            port: 5173,      
            
            watch: {
                usePolling: true
            },

            // O HMR (Hot Module Replacement) usa este host para se conectar,
            // que deve ser acessível pelo seu navegador (localhost).
            hmr: {
                 host: 'localhost',
                 port: 5173
            }
        }
    };
});