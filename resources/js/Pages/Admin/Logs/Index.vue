<template>
  <log-rounds-table :initial="rounds" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import LogRoundsTable from '@/Components/Tables/Admin/LogRoundsTable.vue'

export default {
  components: {
    LogRoundsTable,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Logs' }, () => page),

  props: {
    rounds: Object,
  },
}
</script>
