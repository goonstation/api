<template>
  <q-card class="gh-card gh-card--small overflow-hidden q-mb-md" flat>
    <q-card-section class="q-pa-none">
      <q-tabs indicator-color="transparent" align="left">
        <q-tab @click.prevent="router.visit('/admin/bans')">Active Bans</q-tab>
        <q-tab class="text-primary">Inactive Bans</q-tab>
      </q-tabs>
    </q-card-section>
  </q-card>
  <bans-removed-table :initial="bans" />
</template>

<script>
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BansRemovedTable from '@/Components/Tables/Admin/BansRemovedTable.vue'

export default {
  components: {
    BansRemovedTable
  },

  layout: (h, page) => h(AdminLayout, { title: 'Bans' }, () => page),

  setup() {
    return {
      router,
    }
  },

  props: {
    bans: Object
  }
}
</script>
