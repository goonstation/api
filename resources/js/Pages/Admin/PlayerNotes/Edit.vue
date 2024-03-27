<template>
  <notes-form
    state="edit"
    :fields="fields"
    :submit-route="route('admin.notes.update', { note: note.id })"
    submit-method="put"
    success-message="Note updated"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import NotesForm from '@/Components/Forms/NotesForm.vue'

export default {
  components: {
    NotesForm
  },

  layout: (h, page) => h(AdminLayout, { title: 'Edit Note' }, () => page),

  props: {
    note: Object,
  },

  data() {
    return {
      fields: {
        game_admin_id: this.$page.props.user.game_admin_id,
        ckey: this.note?.player?.ckey || this.note.ckey,
        server_id: this.note.server_id || 'all',
        note: this.note.note,
      },
    }
  }
}
</script>
