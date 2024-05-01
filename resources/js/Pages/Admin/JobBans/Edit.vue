<template>
  <job-bans-form
    state="edit"
    :fields="fields"
    :submit-route="route('admin.job-bans.update', { jobBan: jobBan.id })"
    submit-method="put"
    success-message="Job ban updated"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import JobBansForm from '@/Components/Forms/JobBansForm.vue'

export default {
  components: {
    JobBansForm,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Edit Job Ban' }, () => page),

  props: {
    jobBan: Object,
  },

  data() {
    return {
      fields: {
        game_admin_id: this.$page.props.auth.user.game_admin_id,
        ckey: this.jobBan.ckey,
        server_id: this.jobBan.server_id || 'all',
        reason: this.jobBan.reason,
        job: this.jobBan.banned_from_job,
        duration: this.jobBan.duration,
        expires_at: this.jobBan.expires_at,
      },
    }
  },
}
</script>
