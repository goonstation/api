<template>
  <bans-form
    :fields="fields"
    :submit-route="route('admin.bans.store')"
    success-message="Ban added"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BansForm from '@/Components/Forms/BansForm.vue'

export default {
  components: {
    BansForm
  },

  layout: (h, page) => h(AdminLayout, { title: 'Add Ban' }, () => page),

  data() {
    return {
      fields: {
        ckey: null,
        comp_id: null,
        ip: null,
        server_id: null,
        reason: null,
        duration: null,
      },
    }
  }
}
</script>
