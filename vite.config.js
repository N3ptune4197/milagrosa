import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/main.jsx',
                'resources/js/app.jsx',
            ],
            refresh: true,
        }),
        react(),
        
    ],
    server: {
        watch: {
          usePolling: true,
        },
      },
    
});
