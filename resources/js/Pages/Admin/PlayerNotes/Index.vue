<template>
  <player-notes-table :initial="playerNotes" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PlayerNotesTable from '@/Components/Tables/Admin/PlayerNotesTable.vue'

export default {
  components: {
    PlayerNotesTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Notes' }, () => page),

  props: {
    playerNotes: Object
  }
}
</script>
