<template>
  <medals-form
    :fields="fields"
    :submit-route="route('admin.medals.store')"
    success-message="Medal added"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MedalsForm from '@/Components/Forms/MedalsForm.vue'

export default {
  components: {
    MedalsForm
  },

  layout: (h, page) => h(AdminLayout, { title: 'Add Medal' }, () => page),

  data() {
    return {
      fields: {
        image: null,
        title: null,
        description: null,
        hidden: false,
      },
    }
  }
}
</script>
