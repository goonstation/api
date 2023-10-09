<template>
  <bans-form />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BansForm from '@/Components/Forms/BansForm.vue'

export default {
  components: {
    BansForm
  },

  layout: (h, page) => h(AdminLayout, { title: 'Bans' }, () => page),
}
</script>
