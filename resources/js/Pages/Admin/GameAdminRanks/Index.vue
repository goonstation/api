<template>
  <game-admin-ranks-table :initial="gameAdminRanks" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import GameAdminRanksTable from '@/Components/Tables/Admin/GameAdminRanksTable.vue'

export default {
  components: {
    GameAdminRanksTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Admin Ranks' }, () => page),

  props: {
    gameAdminRanks: Object
  }
}
</script>
