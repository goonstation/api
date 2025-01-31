<template>
  <div class="row justify-center">
    <q-toggle v-model="model" toggle-indeterminate />
  </div>
</template>

<script>
export default {
  props: ['modelValue'],

  computed: {
    model: {
      get() {
        if (this.modelValue === null) return null
        return !!this.modelValue
      },
      set(val) {
        this.$emit('update:modelValue', val === null ? null : val ? 1 : 0)
      },
    },
  },
}
</script>
