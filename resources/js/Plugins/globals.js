import { Link } from '@inertiajs/vue3'

export default {
  install: (app) => {
    // eslint-disable-next-line vue/no-reserved-component-names
    app.component('Link', Link)
  },
}
