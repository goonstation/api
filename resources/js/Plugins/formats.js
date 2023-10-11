import dayjs from 'dayjs'

export default {
  install: (app, options) => {
    app.config.globalProperties.$formats = {
      number: (val) => {
        return new Intl.NumberFormat().format(val)
      },
      date: (val, noTime) => {
        if (!val) return
        const date = new Date(val)
        const opts = {
          weekday: 'short',
          year: 'numeric',
          month: 'short',
          day: 'numeric',
        }
        if (!noTime) {
          opts.hour = '2-digit'
          opts.minute = '2-digit'
        }
        const format = date.toLocaleDateString('en-GB', opts)
        return format
      },
      fromNow: (val) => {
        if (!val) return 'never'
        return dayjs(val).fromNow()
      },
      capitalize: (val) => {
        if (!val) return val
        return val.charAt(0).toUpperCase() + val.slice(1)
      },
      currency: (val) => {
        return new Intl.NumberFormat('en-US', {
          style: 'currency',
          currency: 'USD',
        }).format(val)
      },
      server: (val, short = false) => {
        return app.config.globalProperties.$helpers.serverIdToFriendlyName(val, short)
      },
    }
  },
}
