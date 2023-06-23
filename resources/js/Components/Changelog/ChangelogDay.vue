<template>
  <q-timeline-entry>
    <template v-slot:subtitle>
      <div class="flex items-center q-pr-md">
        <div>{{ $formats.date(date, true) }}</div>
        <q-space />
        <div class="flex items-center">
          {{ changeCount }} change<template v-if="changeCount !== 1">s</template>
          <q-btn
            :icon="ionChevronDown"
            @click="expanded = !expanded"
            class="expand-entries q-ml-xs"
            :class="expanded && 'rotate-180'"
            size="sm"
            dense
          />
        </div>
      </div>
    </template>

    <q-expansion-item v-model="expanded" header-style="display: none;" hide-expand-icon>
      <changelog-entry v-for="entry in entries" :entry="entry" />
    </q-expansion-item>
  </q-timeline-entry>
</template>

<style lang="scss" scoped>
.expand-entries {
  transition: transform 200ms;
}
</style>

<script>
import { ionChevronDown } from '@quasar/extras/ionicons-v6'
import ChangelogEntry from './ChangelogEntry.vue'

export default {
  setup() {
    return {
      ionChevronDown,
    }
  },

  components: {
    ChangelogEntry,
  },

  props: {
    date: String,
    entries: Array,
  },

  data() {
    return {
      changeCount: 0,
      expanded: true,
    }
  },

  created() {
    let count = 0
    this.entries.forEach((entry) => {
      if (entry.major) count += entry.major.length
      if (entry.minor) count += entry.minor.length
    })
    this.changeCount = count
  },
}
</script>
