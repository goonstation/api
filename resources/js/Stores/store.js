import { reactive } from 'vue'

export default class Store {
  obj = {}

  setup(obj) {
    this.obj = reactive(obj)
  }

  set(key, val) {
    this.obj[key] = val
  }

  get(key) {
    return this.obj[key]
  }
}
