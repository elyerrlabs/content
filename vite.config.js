import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import inertia from '@inertiajs/vite';
import monacoEditorEsmPlugin from 'vite-plugin-monaco-editor-esm';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    base: '/third-party/content/build/',
    server: {
        watch: {
            ignored: [
                '**/.junie/**',
                '**/.cursor/**',
                '**/.claude/**',
            ],
        },
    },

    plugins: [
        tailwindcss(),
        vue(),
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
            'resources/js/pages.js',
        ]),
        monacoEditorEsmPlugin(),
        inertia({ ssr: false }),
    ],

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});