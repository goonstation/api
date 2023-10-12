export default {
  install: (app, options) => {
    app.config.globalProperties.$helpers = {
      serverIdToFriendlyName: (val, short = false) => {
        if (val === 'dev') {
          return short ? 'Goon Dev' : 'Goonstation Development'
        } else if (val === 'main1') {
          return short ? 'Goon 1' : 'Goonstation 1 Classic: Heisenbee'
        } else if (val === 'main2') {
          return short ? 'Goon 2' : 'Goonstation 2 Classic: Bombini'
        } else if (val === 'main3') {
          return short ? 'Goon 3 RP' : 'Goonstation 3 Roleplay: Morty'
        } else if (val === 'main4') {
          return short ? 'Goon 4 RP' : 'Goonstation 4 Roleplay: Sylvester'
        } else if (val === 'main5') {
          return short ? 'Goon 5 Event' : 'Goonstation 5 Event: Rocko'
        } else {
          return short ? 'Goon' : 'Goonstation'
        }
      },

      isNumeric: (val) => {
        if (typeof val != 'string') return false
        return (
          !isNaN(val) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
          !isNaN(parseFloat(val)) // ...and ensure strings of whitespace fail
        )
      },
    }
  },
}
