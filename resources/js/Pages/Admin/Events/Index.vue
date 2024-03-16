<template>
  <events-table :initial="events" :event-types="eventTypes" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EventsTable from '@/Components/Tables/Admin/EventsTable.vue'

export default {
  components: {
    EventsTable,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Events' }, () => page),

  props: {
    eventTypes: Array,
    events: Object,
  },
}
</script>
