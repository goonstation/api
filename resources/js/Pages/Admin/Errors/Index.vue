<template>
  <error-rounds-table :initial="rounds" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ErrorRoundsTable from '@/Components/Tables/Admin/ErrorRoundsTable.vue'

export default {
  components: {
    ErrorRoundsTable,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Errors' }, () => page),

  props: {
    rounds: Object,
  },
}
</script>
