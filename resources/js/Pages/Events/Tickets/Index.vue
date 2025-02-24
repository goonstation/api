<template>
  <div>
    <tickets-table prop-key="tickets" />
  </div>
</template>

<script>
import TicketsTable from '@/Components/Tables/TicketsTable.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'

export default {
  components: {
    TicketsTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Tickets' }, () => h(EventsLayout, () => page))
  },
}
</script>
