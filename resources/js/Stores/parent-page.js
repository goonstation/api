import { router } from '@inertiajs/vue3'
import Store from './store'

export default class ParentPage extends Store {
  setup() {
    super.setup({
      params: '',
      url: '',
    })

    router.on('before', (event) => {
      if (event.detail.visit.method !== 'get') return
      this.set('params', window.location.search)
    })

    router.on('navigate', (event) => {
      const crumbs = event.detail.page.props.breadcrumbs
      let newUrl = ''
      if (crumbs && crumbs.length >= 2) {
        newUrl = crumbs[crumbs.length - 2].url + this.get('params')
      }
      this.set('url', newUrl)
    })
  }
}
