<template>
  <div>
    <fines-table prop-key="fines" />
  </div>
</template>

<script>
import FinesTable from '@/Components/Tables/FinesTable.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'

export default {
  components: {
    FinesTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Fines' }, () => h(EventsLayout, () => page))
  },
}
</script>
