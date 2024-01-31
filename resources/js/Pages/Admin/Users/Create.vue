<template>
  <users-form
    :fields="fields"
    :submit-route="route('admin.users.store')"
    success-message="User added"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UsersForm from '@/Components/Forms/UsersForm.vue'

export default {
  components: {
    UsersForm,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Add User' }, () => page),

  data() {
    return {
      fields: {
        name: null,
        email: null,
        password: null,
        confirm_password: null,
        discord_id: null,
        game_admin_id: null,
        is_admin: false
      },
    }
  }
}
</script>
