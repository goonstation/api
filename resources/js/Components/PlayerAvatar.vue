<template>
  <q-avatar
    class="text-uppercase text-bold"
    :style="`background-color: ${avatarBg};`"
    :size="size"
    font-size="0.35em"
    text-color="dark"
  >
    {{ initials }}
  </q-avatar>
</template>

<script>
import { getHsla } from 'pastel-color'

export default {
  props: {
    player: {
      type: Object,
      required: true,
      default: () => ({}),
    },
    size: {
      type: String,
      required: false,
      default: 'auto'
    }
  },

  computed: {
    displayableKey() {
      return this.player.key || this.player.ckey
    },

    avatarBg() {
      return getHsla(this.player.ckey)
    },

    initials() {
      const names = this.displayableKey.split(' ')
      let initials = names[0].substring(0, 1).toUpperCase()
      if (names.length > 1) {
        initials += names[names.length - 1].substring(0, 1).toUpperCase()
      } else {
        initials += names[0].substring(1, 2).toUpperCase()
      }
      return initials
    },
  },
}
</script>
