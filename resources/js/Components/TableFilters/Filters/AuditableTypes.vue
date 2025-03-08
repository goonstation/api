<template>
  <base-select
    v-bind="$attrs"
    :load-route="$route('admin.audit.types')"
    option-value="type"
    option-label="label"
  />
</template>

<script>
import BaseSelect from '@/Components/Selects/BaseSelect.vue'

export default {
  components: {
    BaseSelect,
  },
}
</script>
