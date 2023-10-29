<template>
  <bans-form
    state="edit"
    :fields="fields"
    :submit-route="route('admin.bans.update', { ban: ban.id })"
    submit-method="put"
    success-message="Ban updated"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BansForm from '@/Components/Forms/BansForm.vue'

export default {
  components: {
    BansForm,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Edit Ban' }, () => page),

  props: {
    ban: Object,
  },

  data() {
    return {
      fields: {}
    }
  },

  created() {
    this.fields = {
      ckey: this.ban.original_ban_detail.ckey,
      comp_id: this.ban.original_ban_detail.comp_id,
      ip: this.ban.original_ban_detail.ip,
      server_id: this.ban.server_id,
      reason: this.ban.reason,
      duration: this.ban.duration,
      expires_at: this.ban.expires_at
    }
  }
}
</script>
