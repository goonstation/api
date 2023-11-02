<template>
  <pre>
    stats (same as public player view page)
    VPN whitelist status
    connection history (graph with drill down into specific rounds)
    associated accounts (players with the same IP/compID details)
    ban/jobban history
    notes
  </pre>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default {
  layout: (h, page) => h(AdminLayout, { title: 'Player' }, () => page),

  props: {
    player: Object
  }
}
</script>
