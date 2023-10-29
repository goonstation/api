<template>
  <div>
    <antags-table :initial="antags" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'
import AntagsTable from '@/Components/Tables/AntagsTable.vue'

export default {
  components: {
    AntagsTable,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Antagonists' }, () => h(EventsLayout, () => page))
  },

  props: {
    antags: Object,
  },
}
</script>
