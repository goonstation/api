<template>
  <q-timeline color="primary">
    <changelog-day
      v-for="(dayEntries, date, idx) in changelog"
      :key="`${date}-${idx}`"
      :date="date"
      :entries="dayEntries"
    />
  </q-timeline>
</template>

<style lang="scss" scoped>
:deep(.q-timeline__content) {
  padding-bottom: 0.5rem;

  .q-timeline__title {
    display: none;
  }
}
</style>

<script>
import ChangelogDay from './ChangelogDay.vue'

export default {
  components: {
    ChangelogDay,
  },

  props: {
    changelog: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },
}
</script>
