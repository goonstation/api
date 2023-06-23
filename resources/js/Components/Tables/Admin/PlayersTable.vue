<template>
  <base-table v-bind="$attrs" :fetch-route="fetchRoute" :columns="columns" dense />
</template>

<script>
import BaseTable from '../BaseTable.vue'

export default {
  components: { BaseTable },
  data() {
    return {
      fetchRoute: '/admin/players',
      columns: [
        {
          name: 'id',
          label: 'ID',
          field: 'id',
          sortable: true,
          filterable: false,
          format: this.$formats.number,
        },
        { name: 'ckey', label: 'Ckey', field: 'ckey', sortable: true },
        { name: 'key', label: 'Key', field: 'key', sortable: true },
        {
          name: 'connections',
          label: 'Connections',
          field: 'connections_count',
          sortable: true,
          filter: {
            type: 'range',
          },
        },
        {
          name: 'participations',
          label: 'Participations',
          field: 'participations_count',
          sortable: true,
          filter: {
            type: 'range',
          },
        },
        {
          name: 'byond_version',
          label: 'Byond Version',
          field: (row) => {
            if (!row.byond_major || !row.byond_minor) return ''
            return `${row.byond_major}.${row.byond_minor}`
          },
        },
        { name: 'created_at', label: 'Created', field: 'created_at', sortable: true },
        { name: 'updated_at', label: 'Updated', field: 'updated_at', sortable: true },
      ],
    }
  },
}
</script>
