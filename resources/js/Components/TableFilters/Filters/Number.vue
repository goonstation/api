<template>
  <q-input
    v-model="model"
    @keyup.enter="onEnter"
    class="gh-input--denser"
    type="number"
    debounce="1000"
    filled
    square
    dense
    clearable
  />
</template>

<script>
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
    onEnter(e) {
      // Skip debounce on enter
      this.model = e.target.value
    },
  },
}
</script>
