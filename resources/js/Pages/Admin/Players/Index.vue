<template>
  <players-table :initial="players" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PlayersTable from '@/Components/Tables/Admin/PlayersTable.vue'

export default {
  components: {
    PlayersTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Players' }, () => page),

  props: {
    players: Object
  }
}
</script>
