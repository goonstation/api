<template>
  <game-admin-rank-form />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import GameAdminRankForm from '@/Components/Forms/GameAdminRankForm.vue'

export default {
  components: {
    GameAdminRankForm
  },

  layout: (h, page) => h(AdminLayout, { title: 'Create Admin Rank' }, () => page),
}
</script>
