import { reactive } from 'vue'
import Stores from '../Stores'

export default {
  /** @returns {Stores} */
  install: (app) => {
    const storeStore = reactive({})

    for (const storeName in Stores) {
      const store = new Stores[storeName]()
      store.setup(app)
      storeStore[storeName] = store.obj
    }

    app.config.globalProperties.$store = storeStore
    return storeStore
  },
}
