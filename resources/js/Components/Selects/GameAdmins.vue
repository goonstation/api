<template>
  <base-select
    v-bind="$attrs"
    load-route="/admin/game-admins"
    option-value="id"
    option-label="ckey"
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
