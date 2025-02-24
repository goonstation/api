<template>
  <div>
    <deaths-table prop-key="deaths" />
  </div>
</template>

<script>
import DeathsTable from '@/Components/Tables/DeathsTable.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'

export default {
  components: {
    DeathsTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Deaths' }, () => h(EventsLayout, () => page))
  },
}
</script>
