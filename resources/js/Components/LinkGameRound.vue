<template>
  <q-btn
    @click.prevent="router.visit(`/rounds/${roundId}`)"
    :href="`/rounds/${roundId}`"
    padding="0.5em 1em"
    type="a"
    color="primary"
    outline
    no-wrap
  >
    <q-icon :name="ionPlanetOutline" class="q-mr-sm" />
    Round #{{ $formats.number(roundId) }}
  </q-btn>
</template>

<style lang="scss" scoped>
.q-btn {
  font-size: 0.8em;
}

.q-icon {
  font-size: 22px;
}
</style>

<script>
import { router } from '@inertiajs/vue3'
import { ionPlanetOutline } from '@quasar/extras/ionicons-v6';

export default {
  setup() {
    return {
      router,
      ionPlanetOutline
    }
  },

  props: {
    roundId: {
      type: [Number, String],
      required: true
    }
  }
}
</script>
