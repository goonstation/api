<template>
  <q-table :rows="notes" :columns="notesColumns" flat dense>
    <template v-slot:body-cell-id="props">
      <q-td :props="props">
        <Link :href="route('admin.notes.show', props.row.id)">
          {{ props.row.id }}
        </Link>
      </q-td>
    </template>
    <template v-slot:body-cell-admin_ckey="props">
      <q-td :props="props">
        <Link v-if="props.row.game_admin" :href="route('admin.game-admins.show', props.row.game_admin.id)">
          {{ props.row.game_admin.name || props.row.game_admin.ckey }}
        </Link>
      </q-td>
    </template>
  </q-table>
</template>

<script>
export default {
  props: {
    notes: Object,
  },

  data() {
    return {
      notesColumns: [
        { name: 'id', field: 'id', label: 'ID', sortable: true },
        {
          name: 'note',
          field: 'note',
          label: 'Note',
          align: 'left',
          style: 'white-space: normal; min-width: 300px;',
        },
        {
          name: 'server_id',
          label: 'Server',
          field: 'server_id',
          sortable: true,
          format: (val, row) => {
            if (!val) return 'All'
            return row.game_server.short_name
          },
        },
        {
          name: 'admin_ckey',
          label: 'Admin',
          sortable: true,
        },
        {
          name: 'created_at',
          label: 'Created',
          field: 'created_at',
          sortable: true,
          format: this.$formats.date,
        },
      ],
    }
  },
}
</script>
