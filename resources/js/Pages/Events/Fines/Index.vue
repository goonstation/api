<template>
  <div>
    <fines-table :initial="fines" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'
import FinesTable from '@/Components/Tables/FinesTable.vue'

export default {
  components: {
    FinesTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Fines' }, () => h(EventsLayout, () => page))
  },

  props: {
    fines: Object,
  },
}
</script>
