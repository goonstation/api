<template>
  <base-table
    v-bind="$attrs"
    :routes="routes"
    :columns="columns"
    :show-columns="['created_at']"
    :pagination="{ rowsPerPage: 30 }"
    flat
    dense
    no-timestamp-toggle
  />
</template>

<script>
import BaseTable from '../BaseTable.vue'

export default {
  components: { BaseTable },
  data() {
    return {
      routes: {
        fetch: '/admin/logs',
        view: '/admin/logs/_id',
      },
      columns: [
        {
          name: 'id',
          label: 'ID',
          field: 'id',
          sortable: true,
          filterable: true,
          headerClasses: 'q-table--col-auto-width',
        },
        {
          name: 'server_id',
          label: 'Server',
          field: 'server_id',
          sortable: true,
          format: (val, row) => {
            if (!val) return 'All'
            return row.server.short_name
          },
          filter: { type: 'SelectServers' },
        },
        {
          name: 'created_at',
          label: 'Started At',
          field: 'created_at',
          sortable: true,
          format: this.$formats.dateWithTime,
        },
        {
          name: 'ended_at',
          label: 'Ended At',
          field: 'ended_at',
          sortable: true,
          format: this.$formats.dateWithTime,
        },
      ],
    }
  },
}
</script>
