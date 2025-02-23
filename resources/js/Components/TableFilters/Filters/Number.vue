<template>
  <q-input
    :model-value="model"
    @update:modelValue="onFilterInput"
    @keyup.native.enter="onEnter"
    class="gh-input--denser"
    type="number"
    filled
    square
    dense
    clearable
  />
</template>

<script>
import { debounce } from 'lodash'

export default {
  props: ['modelValue'],

  computed: {
    model: {
      get() {
        return Number(this.modelValue) || null
      },
      set(val) {
        this.$emit('update:modelValue', val)
      },
    },
  },

  methods: {
    onFilterInput: debounce(function (val) {
      this.model = val
    }, 1000),

    onEnter(e) {
      // Skip debounce on enter
      this.model = e.target.value
    },
  },
}
</script>
