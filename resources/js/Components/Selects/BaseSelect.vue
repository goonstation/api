<template>
  <q-select
    ref="select"
    v-model="visibleModel"
    v-bind="$attrs"
    :options="options"
    :clearable="!!model"
    :loading="loading"
    :option-value="optionValue"
    :option-label="optionLabel"
    map-options
    emit-value
    @virtual-scroll="onScroll"
    @filter="filterFn"
  >
    <template #selected-item="{ opt }">
      {{ opt[fieldLabel || optionLabel] }}
    </template>
  </q-select>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    modelValue: null,
    loadRoute: String,
    optionValue: String,
    optionLabel: String,
    fieldLabel: String,
    defaultItems: Array,
  },

  computed: {
    model: {
      get() {
        if (!this.modelValue) return
        if (this.$helpers.isNumeric(this.modelValue)) return parseInt(this.modelValue)
        return this.modelValue
      },
      set(val) {
        this.$emit('update:modelValue', val)
      },
    },

    visibleModel: {
      get() {
        if (this.firstLoad) return
        return this.model
      },
      set(val) {
        this.model = val
      },
    },
  },

  data() {
    return {
      options: [],
      pagination: {
        currentPage: 0,
        lastPage: 1,
        perPage: 50,
      },
      filters: {},
      loading: false,
      firstLoad: true,
      loadedDefaultItem: false,
    }
  },

  created() {
    // Handle an existing item being selected
    if (this.model) {
      this.filters[this.optionValue] = this.model
      this.load().then(() => {
        // Reset state so future calls can correctly query the rest of the resources
        this.pagination.currentPage = 0
        this.pagination.lastPage = 1
        this.pagination.perPage = 50
        delete this.filters[this.optionValue]
        this.loadedDefaultItem = true
      })
    }

    if (this.defaultItems?.length) {
      this.options = this.options.concat(this.defaultItems)
    }
  },

  methods: {
    async load() {
      if (this.pagination.currentPage >= this.pagination.lastPage) return

      this.loading = true
      const response = await axios.get(this.loadRoute, {
        params: {
          page: this.pagination.currentPage + 1,
          per_page: this.pagination.perPage,
          filters: this.filters,
        },
      })

      const newOptions = response.data.data

      // Ensure we don't have duplicate items if we already loaded a default item
      for (const option of this.options) {
        const existingItemIdx = newOptions.findIndex(
          (newOption) => newOption[this.optionValue] === option[this.optionValue]
        )
        if (existingItemIdx >= 0) {
          newOptions.splice(existingItemIdx, 1)
        }
      }

      this.options = this.options.concat(newOptions)
      this.$refs.select.refresh()

      this.pagination.currentPage = response.data.current_page
      this.pagination.lastPage = response.data.last_page
      this.pagination.perPage = response.data.per_page

      this.loading = false
      this.firstLoad = false
      this.loadedDefaultItem = false
    },

    onScroll({ to, ref }) {
      if (this.loading || this.firstLoad) return
      const lastIndex = this.options.length - 1
      if (to === lastIndex) {
        this.load()
      }
    },

    filterFn(val, update, abort) {
      if (!this.firstLoad) {
        // already loaded
        update()
        return
      }

      update(() => {
        this.load()
      })
    },
  },
}
</script>
