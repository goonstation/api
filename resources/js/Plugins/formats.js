import dayjs from 'dayjs'

export default {
  install: (app, options) => {
    app.config.globalProperties.$formats = {
      number: (val) => {
        return new Intl.NumberFormat().format(val)
      },
      date: (val, noTime) => {
        if (!val) return
        const date = dayjs.utc(val)
        if (!date.isValid()) return
        let format = 'ddd, D MMM YYYY'
        if (!noTime) {
          format += ', HH:mm'
        }
        return date.local().format(format)
      },
      dateWithTime: (val) => {
        if (!val) return
        const date = dayjs.utc(val)
        if (!date.isValid()) return
        return date.local().format('ddd, D MMM YYYY, HH:mm')
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
