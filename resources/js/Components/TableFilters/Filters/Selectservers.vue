<template>
  <q-select
    v-model="model"
    :options="options"
    class="gh-select--denser"
    map-options
    emit-value
    square
    filled
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
        return this.modelValue
      },
      set(val) {
        this.$emit('update:modelValue', val)
      },
    },

    options() {
      return [
        { value: 'main1', label: this.$helpers.serverIdToFriendlyName('main1') },
        { value: 'main2', label: this.$helpers.serverIdToFriendlyName('main2') },
        { value: 'main3', label: this.$helpers.serverIdToFriendlyName('main3') },
        { value: 'main4', label: this.$helpers.serverIdToFriendlyName('main4') },
        { value: 'main5', label: this.$helpers.serverIdToFriendlyName('main5') },
      ]
    },
  },
}
</script>
