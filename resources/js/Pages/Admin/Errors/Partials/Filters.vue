<template>
  <div
    class="row q-col-gutter-md"
    :class="{
      'error-filters-sidebar': sidebar,
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
      <q-checkbox
        v-model="modelValue.relativeTimestamps"
        label="Relative Timestamps"
        :dense="sidebar"
      />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.error-filters-sidebar {
  width: 200px;
}
</style>

<script>
export default {
  props: {
    modelValue: Object,
    sidebar: Boolean,
    hasSearchFilters: Boolean,
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
