<template>
  <base-select
    v-bind="$attrs"
    :load-route="$route('admin.users.index')"
    option-value="id"
    option-label="name"
    field-label="name"
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
