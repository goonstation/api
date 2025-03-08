<template>
  <audit-table prop-key="audits" />
</template>

<script>
import AuditTable from '@/Components/Tables/Admin/AuditTable.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default {
  layout: (h, page) => h(AdminLayout, { title: 'Audit' }, () => page),

  components: {
    AuditTable,
  },

  props: {
    audits: Object,
  },
}
</script>
