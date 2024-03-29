import dayjs from 'dayjs'

export default {
  install: (app, options) => {
    app.config.globalProperties.$formats = {
      number: (val) => {
        return new Intl.NumberFormat().format(val)
      },
      date: (val, noTime) => {
        if (!val) return
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
        return new Date(val).toLocaleDateString('en-GB', opts)
      },
      dateWithTime: (val) => {
        if (!val) return
        const opts = {
          weekday: 'short',
          year: 'numeric',
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
        }
        return new Date(val).toLocaleDateString('en-GB', opts)
      },
      fromNow: (val) => {
        const date = dayjs.utc(val)
        if (!date.isValid()) return 'never'
        return date.fromNow()
      },
      capitalize: (val) => {
        if (!val) return val
        return val.charAt(0).toUpperCase() + val.slice(1)
      },
      currency: (val) => {
        return val + ' ⪽'
      },
      server: (val, short = false) => {
        return app.config.globalProperties.$helpers.serverIdToFriendlyName(val, short)
      },
    }
  },
}
