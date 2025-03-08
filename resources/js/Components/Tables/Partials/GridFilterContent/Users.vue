<template>
  <div>User is {{ userName }}</div>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    filter: null,
    filterOption: null,
  },

  data() {
    return {
      userName: null,
    }
  },

  watch: {
    filter: {
      immediate: true,
      handler(val) {
        if (this.filterOption) {
          this.userName = this.filterOption.name
        } else {
          this.getUser(val)
        }
      },
    },
  },

  methods: {
    async getServer(userId) {
      const { data } = await axios.get(this.$route('admin.users.index'), {
        params: {
          filters: {
            id: userId,
          },
        },
      })
      this.userName = data?.data?.[0]?.name
    },
  },
}
</script>
