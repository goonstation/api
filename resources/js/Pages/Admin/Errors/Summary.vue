<template>
  <error-summary-table fetch-on-load />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ErrorSummaryTable from '@/Components/Tables/ErrorSummaryTable.vue'

export default {
  components: {
    ErrorSummaryTable,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Errors' }, () => page),
}
</script>
