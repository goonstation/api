<template>
  <users-table :initial="users" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UsersTable from '@/Components/Tables/Admin/UsersTable.vue'

export default {
  components: {
    UsersTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Users' }, () => page),

  props: {
    users: Object
  }
}
</script>
