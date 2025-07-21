import { defineConfig } from 'vite';

import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

// Get host from the environment or use default
const appHost = process.env.APP_HOST || 'metafilter.test';
const subdomains = [
    'www', 
    'ask', 
    'metatalk', 
    'fanfare', 
    'projects', 
    'music', 
    'jobs', 
    'irl'
];

// Create allowed hosts list with both bare domain and subdomains
const allowedHosts = [appHost, ...subdomains.map(sub => `${sub}.${appHost}`)];

// Serve on localhost by default
const serverHost = process.env.VITE_HOST || 'localhost';
const serverPort = parseInt(process.env.VITE_PORT || '5173') || 5173;

// noinspection JSUnusedGlobalSymbols
export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler'
            }
        }
    },
    server: {
        host: serverHost,
        allowedHosts,
        hmr: { host: appHost },
        cors: true,
        strictPort: true,
        port: serverPort,
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/email.scss',
                'resources/sass/errors.scss',
                'resources/sass/themes/ask.scss',
                'resources/sass/themes/bestof.scss',
                'resources/sass/themes/fanfare.scss',
                'resources/sass/themes/irl.scss',
                'resources/sass/themes/jobs.scss',
                'resources/sass/themes/metafilter.scss',
                'resources/sass/themes/metatalk.scss',
                'resources/sass/themes/music.scss',
                'resources/sass/themes/projects.scss',
                'resources/css/filament/admin/theme.css',
                'resources/js/app.js',
                'resources/js/wysiwyg.js',
            ],
            refresh: [
                'app/Livewire/**',
                'resources/views/**',
            ],
            publicDirectory: 'public_html',
            build: {
                outDir: 'public_html/build',
            },
            // Use specific host configuration
            host: appHost,
        })
    ],
    resolve: {
        alias: {
            $fonts: resolve('./public_html/fonts')
        }
    }
});
