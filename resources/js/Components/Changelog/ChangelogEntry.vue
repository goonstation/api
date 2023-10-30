<template>
  <div class="q-my-sm flex items-center gap-xs-sm">
    <q-chip square color="grey-9" class="q-ma-none">
      <q-avatar :icon="ionPerson" color="primary" text-color="black" />
      {{ entry.user }}
    </q-chip>
    <span v-if="entry.emojiLabels?.emojis" class="emojis">
      {{ entry.emojiLabels.emojis }}
    </span>
    <q-space />
    <div v-if="entry.pr">
      <a :href="`https://github.com/goonstation/goonstation/pull/${entry.pr}`" target="_blank">
        PR #{{ entry.pr }}
      </a>
    </div>
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

<style lang="scss" scoped>
.emojis {
  font-size: 1.5em;
  line-height: 1;
}
</style>

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
