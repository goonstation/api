<template>
  <div>
    <error-summary-table :initial="errors" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'
import ErrorSummaryTable from '@/Components/Tables/ErrorSummaryTable.vue'

export default {
  components: {
    ErrorSummaryTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Errors' }, () => h(EventsLayout, () => page))
  },

  props: {
    errors: Object,
  },
}
</script>
