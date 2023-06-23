<template>
  <div>
    <deaths-table :initial="deaths" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'
import DeathsTable from '@/Components/Tables/DeathsTable.vue'

export default {
  components: {
    DeathsTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Deaths' }, () => h(EventsLayout, () => page))
  },

  props: {
    deaths: Object,
  },
}
</script>
