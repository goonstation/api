<template>
  <base-select
    v-bind="$attrs"
    load-route="/admin/game-admin-ranks"
    option-value="id"
    option-label="rank"
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
