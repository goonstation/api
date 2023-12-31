<template>
  <base-table
    v-bind="$attrs"
    :fetch-route="fetchRoute"
    :columns="columns"
    :hide-columns="['expires_at', 'deleted_at']"
    dense
  >
    <template v-slot:body="props">
      <q-tr
        :props="props"
        :style="props.rowIndex % 2 === 0 ? '' : 'background-color: rgba(255, 255, 255, 0.02);'"
      >
        <q-td v-for="col in props.cols" :key="col.name" :props="props">
          <q-btn
            v-if="col.name === 'details'"
            class="q-pa-xs q-pl-sm full-width text-weight-regular"
            style="font-size: 0.9em"
            no-wrap
            flat
            @click="props.expand = !props.expand"
          >
            <span class="q-mr-xs">{{ col.value }}</span>
            <span>
              <q-icon :name="props.expand ? 'keyboard_arrow_up' : 'keyboard_arrow_down'" />
            </span>
          </q-btn>
          <template v-else-if="col.name === 'status'">
            <q-badge v-if="props.row.deleted_at" color="negative"> Removed </q-badge>
            <q-badge
              v-else-if="isBanExpired(props.row.expires_at)"
              color="warning"
              text-color="black"
            >
              Expired
            </q-badge>
          </template>
          <template v-else>{{ col.value }}</template>
        </q-td>
      </q-tr>
      <q-tr v-show="props.expand" :props="props" class="qr-row--expansion">
        <q-td colspan="100%">
          <ban-details-table :expand="props.expand" :row="props.row" />
        </q-td>
      </q-tr>
    </template>
  </base-table>
</template>

<script>
import BaseTable from '../BaseTable.vue'
import BanDetailsTable from './Admin/BanDetailsTable.vue'

export default {
  components: {
    BaseTable,
    BanDetailsTable,
  },

  data() {
    return {
      fetchRoute: '/admin/bans',
      columns: [
        {
          name: 'id',
          label: 'ID',
          field: 'id',
          sortable: true,
          filterable: false,
          format: this.$formats.number,
        },
        { name: 'round_id', label: 'Round', field: 'round_id', sortable: true, filterable: false },
        {
          name: 'server_id',
          label: 'Server',
          field: 'server_id',
          sortable: true,
          format: (val) => (val ? val : 'all'),
        },
        { name: 'admin_ckey', label: 'Admin', field: (row) => row.game_admin.ckey, sortable: true },
        {
          name: 'original_ban_ckey',
          label: 'Player',
          field: (row) => row.original_ban_detail.ckey,
          sortable: true,
        },
        {
          name: 'reason',
          label: 'Reason',
          field: 'reason',
          sortable: true,
          align: 'left',
          style: 'white-space: normal; min-width: 300px;',
        },
        {
          name: 'details',
          label: 'Details',
          field: 'details_count',
          sortable: true,
          filter: {
            type: 'range',
          },
        },
        {
          name: 'created_at',
          label: 'Created',
          field: 'created_at',
          sortable: true,
          format: this.$formats.date,
          filter: { type: 'daterange' },
        },
        {
          name: 'updated_at',
          label: 'Last Updated',
          field: 'updated_at',
          sortable: true,
          format: this.$formats.date,
          filter: { type: 'daterange' },
        },
        {
          name: 'expires_at',
          label: 'Expired',
          field: 'expires_at',
          sortable: true,
          format: this.$formats.date,
          filter: { type: 'daterange' },
        },
        {
          name: 'deleted_at',
          label: 'Deleted',
          field: 'deleted_at',
          sortable: true,
          format: this.$formats.date,
          filter: { type: 'daterange' },
        },
        {
          name: 'status',
          label: '',
          headerClasses: 'q-table--col-auto-width',
          filterable: false,
        },
      ],
    }
  },

  methods: {
    isBanExpired(expiresAt) {
      if (!expiresAt) return false
      return new Date(expiresAt) <= new Date()
    },
  },
}
</script>
