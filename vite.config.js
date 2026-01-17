import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/vendor/flatpickr.css",
                "resources/js/vendor/flatpickr.js",
                "resources/js/vendor/tinymce.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        hmr: {
            protocol: (process.env.CODESPACES && process.env.PUBLIC_URL) ? "wss" : undefined,
            host: (process.env.CODESPACES && process.env.PUBLIC_URL) ? `${process.env.CODESPACE_NAME}-5173.${process.env.GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}` : "localhost",
            clientPort: (process.env.CODESPACES && process.env.PUBLIC_URL) ? 443 : undefined,
        },
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
