<template>
  <base-table
    v-bind="$attrs"
    :routes="routes"
    :columns="columns"
    :pagination="{ rowsPerPage: 30 }"
    dense
    flat
  />
</template>

<script>
import BaseTable from '../BaseTable.vue'

export default {
  components: { BaseTable },
  data() {
    return {
      routes: { fetch: '/admin/players' },
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
          name: 'connections_count',
          label: 'Connections',
          field: 'connections_count',
          sortable: true,
          format: this.$formats.number,
          filter: {
            type: 'range',
          },
        },
        {
          name: 'participations_count',
          label: 'Participations',
          field: 'participations_count',
          sortable: true,
          format: this.$formats.number,
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
        {
          name: 'created_at',
          label: 'Created',
          field: 'created_at',
          sortable: true,
          format: this.$formats.date,
        },
        {
          name: 'updated_at',
          label: 'Updated',
          field: 'updated_at',
          sortable: true,
          format: this.$formats.date,
        },
      ],
    }
  },
}
</script>
