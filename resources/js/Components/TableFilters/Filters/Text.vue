<template>
  <q-input
    :model-value="model"
    @update:modelValue="onFilterInput"
    @keyup.native.enter="onEnter"
    class="gh-input--denser"
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
        return this.modelValue
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
    }
  },
}
</script>
