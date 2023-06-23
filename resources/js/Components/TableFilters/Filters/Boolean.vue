<template>
  <div class="row justify-center">
    <q-checkbox v-model="model" />
  </div>
</template>

<script>
export default {
  props: ['modelValue'],

  computed: {
    model: {
      get() {
        return !!this.modelValue
      },
      set(val) {
        this.$emit('update:modelValue', val)
      }
    }
  }
}
</script>
