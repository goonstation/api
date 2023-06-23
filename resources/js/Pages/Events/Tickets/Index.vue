<template>
  <div>
    <tickets-table :initial="tickets" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'
import TicketsTable from '@/Components/Tables/TicketsTable.vue'

export default {
  components: {
    TicketsTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Tickets' }, () => h(EventsLayout, () => page))
  },

  props: {
    tickets: Object,
  },
}
</script>
