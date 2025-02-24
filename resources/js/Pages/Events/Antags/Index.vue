<template>
  <div>
    <antags-table prop-key="antags" />
  </div>
</template>

<script>
import AntagsTable from '@/Components/Tables/AntagsTable.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'

export default {
  components: {
    AntagsTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Antagonists' }, () => h(EventsLayout, () => page))
  },
}
</script>
