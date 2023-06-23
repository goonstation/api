<template>
  <div>
    <rounds-table :initial="rounds" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import RoundsTable from '@/Components/Tables/RoundsTable.vue'

export default {
  components: {
    RoundsTable
  },

  layout: (h, page) => h(AppLayout, { title: 'Rounds' }, () => page),

  props: {
    rounds: Object
  }
}
</script>
