<template>
  <redirects-form
    :fields="fields"
    :submit-route="route('admin.redirects.store')"
    success-message="Redirect added"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import RedirectsForm from '@/Components/Forms/RedirectsForm.vue'

export default {
  components: {
    RedirectsForm,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Add Redirect' }, () => page),

  data() {
    return {
      fields: {
        from: '',
        to: '',
      },
    }
  },
}
</script>
