<template>
  <redirects-table :initial="redirects" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import RedirectsTable from '@/Components/Tables/Admin/RedirectsTable.vue'

export default {
  components: {
    RedirectsTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Redirects' }, () => page),

  props: {
    redirects: Object
  }
}
</script>
