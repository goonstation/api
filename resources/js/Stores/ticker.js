import Store from './store'

export default class Ticker extends Store {
  setup() {
    super.setup({
      tick: 0,
    })

    setInterval(() => {
      this.increment('tick')
    }, 1000)
  }
}
