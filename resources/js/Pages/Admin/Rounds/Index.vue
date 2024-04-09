<template>
  <rounds-table :initial="rounds" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import RoundsTable from '@/Components/Tables/Admin/RoundsTable.vue'

export default {
  components: {
    RoundsTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Rounds' }, () => page),

  props: {
    rounds: Object
  }
}
</script>
