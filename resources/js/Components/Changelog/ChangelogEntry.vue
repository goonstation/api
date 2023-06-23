<template>
  <div class="q-my-sm">
    <q-chip square color="grey-9" class="q-ma-none">
      <q-avatar :icon="ionPerson" color="primary" text-color="black" />
      {{ entry.user }}
    </q-chip>
  </div>
  <div v-for="change in entry.major">
    <q-chip square color="grey-9" class="q-mr-sm" size="sm"> Major </q-chip>
    {{ change }}
  </div>
  <div v-for="change in entry.minor">
    <q-chip square color="grey-9" class="q-mr-sm" size="sm"> Minor </q-chip>
    {{ change }}
  </div>
</template>

<script>
import { ionPerson } from '@quasar/extras/ionicons-v6'

export default {
  props: {
    entry: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },

  setup() {
    return {
      ionPerson,
    }
  },
}
</script>
