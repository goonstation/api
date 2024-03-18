<template>
  <search-players v-model="search" />
  <players-table :initial="players" :search="search" @loaded="onTableLoaded" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PlayersTable from '@/Components/Tables/Admin/PlayersTable.vue'
import SearchPlayers from './Partials/Search.vue'

export default {
  components: {
    PlayersTable,
    SearchPlayers
  },

  layout: (h, page) => h(AdminLayout, { title: 'Players' }, () => page),

  props: {
    players: Object
  },

  data() {
    return {
      search: null
    }
  },

  methods: {
    onTableLoaded({ filters }) {
      this.search = filters
    }
  }
}
</script>
