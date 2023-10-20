<template>
  <div>Server is {{ serverName }}</div>
</template>

<script>
import axios from 'axios'

export default {
  props: ['filter'],

  data() {
    return {
      serverName: null
    }
  },

  watch: {
    filter: {
      immediate: true,
      handler(val) {
        this.getServer(val)
      }
    }
  },

  methods: {
    async getServer(serverId) {
      const { data } = await axios.get('/game-servers', {
        params: {
          filters: {
            server_id: serverId
          }
        }
      })
      const server = data?.data[0]
      if (server?.name) {
        this.serverName = server.name
      }
    }
  }
}
</script>
