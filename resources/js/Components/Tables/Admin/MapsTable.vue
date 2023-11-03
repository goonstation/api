<template>
  <base-table
    v-bind="$attrs"
    :routes="routes"
    :columns="columns"
    :pagination="{ rowsPerPage: 30, sortBy: 'name', descending: false }"
    dense
    flat
  >
    <template #header-right>
      <q-btn
        @click="router.visit(route('admin.maps.upload'))"
        color="primary"
        text-color="dark"
      >
        Upload
      </q-btn>
    </template>
  </base-table>
</template>

<script>
import { router } from '@inertiajs/vue3'
import BaseTable from '../BaseTable.vue'

export default {
  components: { BaseTable },

  setup() {
    return {
      router
    }
  },

  data() {
    return {
      routes: {
        fetch: '/admin/maps',
        // view: '/admin/maps/_id'
      },
      columns: [
        { name: 'map_id', label: 'ID', field: 'map_id', sortable: true },
        { name: 'name', label: 'Name', field: 'name', sortable: true },
        {
          name: 'last_built_by',
          label: 'Last Built By',
          field: 'last_built_by',
          format: (val, row) => {
            if (!val) return
            return row.game_admin.name || row.game_admin.ckey
          },
        },
        {
          name: 'last_built_at',
          label: 'Last Built At',
          field: 'last_built_at',
          sortable: true,
          format: this.$formats.dateWithTime,
          filter: { type: 'DateRange' },
        },
        {
          name: 'created_at',
          label: 'Created',
          field: 'created_at',
          sortable: true,
          format: this.$formats.date,
          filter: { type: 'DateRange' },
        },
        {
          name: 'updated_at',
          label: 'Updated',
          field: 'updated_at',
          sortable: true,
          format: this.$formats.date,
          filter: { type: 'DateRange' },
        },
      ],
    }
  },
}
</script>
