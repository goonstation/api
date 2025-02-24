<template>
  <div>
    <rounds-table prop-key="rounds" />
  </div>
</template>

<script>
import RoundsTable from '@/Components/Tables/RoundsTable.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
  components: {
    RoundsTable,
  },

  layout: (h, page) => h(AppLayout, { title: 'Rounds' }, () => page),
}
</script>
