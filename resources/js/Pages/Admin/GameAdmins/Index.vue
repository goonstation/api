<template>
  <game-admins-table :initial="gameAdmins" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import GameAdminsTable from '@/Components/Tables/Admin/GameAdminsTable.vue'

export default {
  components: {
    GameAdminsTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Admins' }, () => page),

  props: {
    gameAdmins: Object
  }
}
</script>
