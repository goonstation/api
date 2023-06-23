<template>
  <div class="q-pa-md">
    <players-table :initial="players" />
  </div>
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
