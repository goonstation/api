<template>
  <div
    class="row q-col-gutter-md"
    :class="{
      'log-filters-sidebar': sidebar,
    }"
  >
    <div :class="[sidebar ? 'col-12' : 'col']">
      <q-form @submit="search">
        <q-input
          v-model="modelValue.searchInput"
          class="q-mb-xs"
          :class="{ 'text-sm': sidebar }"
          type="textarea"
          placeholder="One term per line&#10;Term: Match must match any&#10;!Term: Match must include&#10;-Term: Match must not include"
          filled
          dense
        />
        <div class="flex gap-xs-xs">
          <q-btn
            v-if="hasSearchFilters"
            @click="clearSearch"
            :class="{ 'full-width': sidebar }"
            color="grey"
            text-color="dark"
            size="sm"
            >Clear Filters</q-btn
          >
          <q-space />
          <q-btn
            :class="{ 'full-width': sidebar }"
            type="submit"
            color="primary"
            text-color="dark"
            size="sm"
            >Apply Filters</q-btn
          >
        </div>
      </q-form>
    </div>
    <div :class="[sidebar ? 'col-12' : 'col']">
      <div class="flex flex-wrap gap-xs-xs">
        <div class="log-type-filter">
          <q-checkbox v-model="logTypesAll" val="all" label="All" dense />
        </div>
        <template v-for="logType in logTypes">
          <div class="log-type-filter" :class="`log-type-${logType.value}`">
            <q-checkbox
              v-model="modelValue.logTypesToShow"
              :val="logType.value"
              :label="logType.label"
              dense
            />
          </div>
        </template>
      </div>
      <hr class="q-mt-md" style="border-color: grey" />
      <q-checkbox
        v-model="modelValue.relativeTimestamps"
        label="Relative Timestamps"
        :dense="sidebar"
      />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.log-filters-sidebar {
  width: 200px;
}

.log-type-filter {
  display: inline-block;
  background: grey;
  border-radius: 3px;
  padding: 3px 5px;
  line-height: 1;
}
</style>

<script>
export default {
  props: {
    modelValue: Object,
    sidebar: Boolean,
    logTypes: Array,
    hasSearchFilters: Boolean,
  },

  computed: {
    logTypesAll: {
      get() {
        return this.logTypes.length === this.modelValue.logTypesToShow.length
      },
      set(val) {
        const newLogTypes = []
        if (val) {
          for (const logType of this.logTypes) {
            newLogTypes.push(logType.value)
          }
        }
        this.modelValue.logTypesToShow = newLogTypes
      },
    },
  },

  methods: {
    search() {
      const terms = this.modelValue.searchInput.split('\n')
      const filters = { and: [], or: [], not: [] }
      for (let term of terms) {
        if (term.length < 3) continue

        term = term.toLowerCase()
        if (term.startsWith('-')) {
          filters.not.push(term.substring(1))
        } else if (term.startsWith('!')) {
          filters.and.push(term.substring(1))
        } else {
          filters.or.push(term)
        }
      }
      this.$emit('search', filters)
    },

    clearSearch() {
      this.modelValue.searchInput = ''
      this.$emit('clear-search')
    },
  },
}
</script>
