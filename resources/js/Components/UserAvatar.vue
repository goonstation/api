<template>
  <q-avatar v-if="user.profile_photo_url">
    <img :src="user.profile_photo_url" :alt="user.name" />
  </q-avatar>
  <q-avatar
    v-else
    class="text-uppercase"
    style="background-color: #111"
    font-size="1rem"
    text-color="primary"
  >
    {{ initials }}
  </q-avatar>
</template>

<script>
export default {
  props: {
    user: Object,
  },

  computed: {
    initials() {
      const names = this.user.name.split(' ')
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
