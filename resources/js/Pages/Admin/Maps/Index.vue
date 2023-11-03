<template>
  <maps-table :initial="maps" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MapsTable from '@/Components/Tables/Admin/MapsTable.vue'

export default {
  components: {
    MapsTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Maps' }, () => page),

  props: {
    maps: Object
  }
}
</script>
