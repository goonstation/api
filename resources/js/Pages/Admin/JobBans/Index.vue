<template>
  <job-bans-table :initial="jobBans" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import JobBansTable from '@/Components/Tables/Admin/JobBansTable.vue'

export default {
  components: {
    JobBansTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Job Bans' }, () => page),

  props: {
    jobBans: Object
  }
}
</script>
