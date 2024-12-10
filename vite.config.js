import path from 'path'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import VueMacros from 'unplugin-vue-macros/vite'
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'

export default defineConfig({
  server: {
    hmr: {
      host: 'localhost',
    },
  },
  build: {
    sourcemap: true,
  },
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
    VueMacros({
      plugins: {
        vue: vue(),
      },
      template: { transformAssetUrls },
    }),
    quasar({
      sassVariables: path.resolve(__dirname, './resources/css/quasar-variables.scss'),
    }),
  ],
  resolve: {
    alias: {
      '@img': path.resolve(__dirname, './resources/img'),
      '@': path.resolve(__dirname, './resources/js'),
    },
  },
})
