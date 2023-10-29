import './bootstrap'

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/vue3'
import { Quasar, Notify } from 'quasar'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import VueDOMPurifyHTML from 'vue-dompurify-html'

import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import relativeTime from 'dayjs/plugin/relativeTime'
import duration from 'dayjs/plugin/duration'
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore'
dayjs.extend(utc)
dayjs.extend(relativeTime)
dayjs.extend(duration)
dayjs.extend(isSameOrBefore)

import VueApexCharts from 'vue3-apexcharts'

// Import icon libraries
import { ionChevronDown } from '@quasar/extras/ionicons-v6'
import iconSet from 'quasar/icon-set/svg-ionicons-v6'
iconSet.arrow.dropdown = ionChevronDown

// Import Quasar css
import 'quasar/src/css/index.sass'
import "quasar/src/css/flex-addon.sass"

import '../css/app.scss'

// Plugins!
import route from './Plugins/route'
import helpers from './Plugins/helpers'
import formats from './Plugins/formats'
import globals from './Plugins/globals'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(Quasar, {
        plugins: {
          Notify
        },
        config: {
          dark: true,
          notify: {
            position: 'top-right'
          }
        },
        iconSet,
        cssAddon: true
      })
      .use(route)
      .use(helpers)
      .use(formats)
      .use(globals)
      .use(VueApexCharts)
      .use(VueDOMPurifyHTML)
      .mount(el)
  },
  progress: {
    color: '#ffd125',
  },
})
