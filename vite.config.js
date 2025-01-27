import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin';
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        AutoImport({
            resolvers: [ElementPlusResolver({ importStyle: 'sass' })],
            imports: ['vue', 'vue-router', '@vueuse/core', 'pinia'],
            dts: 'resources/js/auto-imports.d.ts',
        }),
        Components({
            extensions: ['tsx', 'jsx', 'vue'],
            dirs: ['./resources/js/components'],
            resolvers: [ElementPlusResolver({ importStyle: 'sass' })],
            dts: 'resources/js/components.d.ts',
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': '/resources/js',
        },
    },
});
