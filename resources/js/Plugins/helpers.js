export default {
  install: (app, options) => {
    app.config.globalProperties.$helpers = {
      serverIdToFriendlyName: (val) => {
        if (val === 'dev') {
          return 'Goonstation Development'
        } else if (val === 'main1') {
          return 'Goonstation 1 Classic: Heisenbee'
        } else if (val === 'main2') {
          return 'Goonstation 2 Classic: Bombini'
        } else if (val === 'main3') {
          return 'Goonstation 3 Roleplay: Morty'
        } else if (val === 'main4') {
          return 'Goonstation 4 Roleplay: Sylvester'
        } else if (val === 'main5') {
          return 'Goonstation 5 Event: Rocko'
        } else {
          return 'Goonstation'
        }
      },
    }
  },
}
