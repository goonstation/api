<template>
  <div class="q-pa-md">
    <bans-table :initial="bans" />
  </div>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BansTable from '@/Components/Tables/Admin/BansTable.vue'

export default {
  components: {
    BansTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Bans' }, () => page),

  props: {
    bans: Object
  }
}
</script>
