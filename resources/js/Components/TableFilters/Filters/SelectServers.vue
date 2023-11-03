<template>
  <base-select
    v-bind="$attrs"
    load-route="/game-servers"
    option-value="server_id"
    option-label="name"
    field-label="short_name"
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
