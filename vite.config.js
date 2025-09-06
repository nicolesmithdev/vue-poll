import { resolve } from 'path';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    define: {
        'process.env': {},
    },
    plugins: [vue()],
    build: {
        outDir: '',
        minify: true,
        lib: {
            entry: resolve(__dirname, 'src/index.js'),
            name: 'App',
            fileName: 'app',
        },
        rollupOptions: {
            output: {
                entryFileNames: 'js/app.js',
                chunkFileNames: 'js/app.js',
                assetFileNames: ({ name }) => {
                    if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')) {
                        return 'images/[name][extname]';
                    }
                    if (/\.css$/.test(name ?? '')) {
                        return 'css/[name][extname]';
                    }
                    // default
                    return '[name]-[hash][extname]';
                },
            },
        },
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, 'src'),
        },
    },
});
