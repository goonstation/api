import { router } from '@inertiajs/vue3'

export default {
  install: (app) => {
    app.config.globalProperties.$route = route
    app.config.globalProperties.$rtr = router
  },
}
