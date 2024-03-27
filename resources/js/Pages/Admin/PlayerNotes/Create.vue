<template>
  <notes-form
    :fields="fields"
    :submit-route="route('admin.notes.store')"
    success-message="Note added"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import NotesForm from '@/Components/Forms/NotesForm.vue'

export default {
  components: {
    NotesForm
  },

  layout: (h, page) => h(AdminLayout, { title: 'Add Note' }, () => page),

  data() {
    return {
      fields: {
        game_admin_id: this.$page.props.user.game_admin_id,
        ckey: null,
        server_id: null,
        note: null,
      },
    }
  }
}
</script>
