<template>
  <q-btn-group>
    <q-btn class="text-sm" color="grey-9" padding="xs sm" dense no-caps unelevated>
      {{ column.label }} {{ comparator }} {{ prettyFilter }}
      <q-menu :offset="[0, 10]">
        <div class="q-pa-sm flex items-center">
          <span class="q-mr-sm text-caption">{{ column.label }}</span>
          <table-filter
            :model-value="filter"
            @update:modelValue="$emit('update', $event)"
            @clear="$emit('clear')"
            :filter-type="column.filter?.type || 'text'"
          />
        </div>
      </q-menu>
    </q-btn>
    <q-btn class="text-sm" color="grey-9" padding="xs xs" @click="$emit('clear')" dense unelevated>
      <q-icon :name="ionClose" size="xs" />
    </q-btn>
  </q-btn-group>
</template>

<script>
import { ionClose } from '@quasar/extras/ionicons-v6'
import TableFilter from '@/Components/TableFilters/BaseFilter.vue'

export default {
  setup() {
    return {
      ionClose,
    }
  },

  components: {
    TableFilter
  },

  props: {
    column: Object,
    filter: null,
  },

  computed: {
    comparator() {
      const type = this.column.filter?.type ?? ''
      if (type === 'daterange') {
        if (this.filter.includes('-')) return 'is between'
        else return 'is on'
      } else if (type === 'boolean') {
        return 'is'
      } else if (type === 'range') {
        return ''
      } else {
        return 'contains'
      }
    },

    prettyFilter() {
      if (this.column.filter?.type === 'daterange') {
        return this.filter.replace('-', ' and ')
      }
      return this.filter
    },
  },
}
</script>
