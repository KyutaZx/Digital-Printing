import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/main.jsx',
                'resources/js/landing-hero.jsx',
                'resources/js/landing-services.jsx',
                'resources/js/landing-cara-order.jsx',
                'resources/js/app-footer.jsx',
                'resources/js/auth-page.jsx',
                'resources/js/landing-contact.jsx',
                'resources/js/catalog-grid.jsx',
                'resources/js/product-detail-app.jsx',
                'resources/js/about-faq.jsx',
            ],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
