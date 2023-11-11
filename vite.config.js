import path from 'path'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import VueMacros from 'unplugin-vue-macros/vite'
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'
import { sassMigratorQuasar } from 'rollup-plugin-sass-migrator'

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
      sassVariables: 'resources/css/quasar-variables.scss',
    }),
    // Quasar recommends pinning sass at a low version, but that sucks so let's use modern sass
    // and just convert their dumb vendor files into valid syntax
    sassMigratorQuasar({
      indexPath: 'node_modules/quasar/src/css/index.sass',
    }),
    sassMigratorQuasar({
      indexPath: 'node_modules/quasar/src/css/flex-addon.sass',
    }),
  ],
  resolve: {
    alias: {
      '@img': path.resolve(__dirname, './resources/img'),
      '@': path.resolve(__dirname, './resources/js'),
    },
  },
})
