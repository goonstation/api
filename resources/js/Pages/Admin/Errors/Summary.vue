<template>
  <error-summary-table :initial="errors" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ErrorSummaryTable from '@/Components/Tables/Admin/ErrorSummaryTable.vue'

export default {
  components: {
    ErrorSummaryTable,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Errors' }, () => page),

  props: {
    errors: Object,
  },
}
</script>
