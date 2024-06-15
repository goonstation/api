<template>
  <medals-table :initial="medals" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MedalsTable from '@/Components/Tables/Admin/MedalsTable.vue'

export default {
  components: {
    MedalsTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Medals' }, () => page),

  props: {
    medals: Object
  }
}
</script>
