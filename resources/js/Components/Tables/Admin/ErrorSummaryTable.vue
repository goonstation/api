<template>
  <div>
    <base-table
      v-bind="$attrs"
      :routes="routes"
      :columns="columns"
      :pagination="{ sortBy: 'count', rowsPerPage: 30 }"
      :search="search"
      @loaded="onTableLoaded"
      @reset="onTableReset"
      @fetch-end="onTableFetch"
      flat
      dense
      wrap-cells
      no-timestamp-toggle
    >
      <template #top-left>
        <div class="flex gap-xs-md">
          <base-select
            ref="serverSelect"
            v-model="search.server"
            :clearable="false"
            style="min-width: 200px;"
            label="Server"
            load-route="/game-servers?with_invisible=1"
            option-value="server_id"
            option-label="name"
            filled
            lazy-rules
            dense
            emit-value
            map-options
            :default-items="[{ name: 'All', server_id: 'all' }]"
          />
          <q-select
            v-model="search.time_range"
            :options="timeRangeOptions"
            style="min-width: 200px;"
            label="Time Range"
            option-value="value"
            option-label="label"
            filled
            dense
            emit-value
            map-options
          />
        </div>
      </template>

      <template #header-bottom>
        <div class="full-width">
          <errors-by-round :data="rounds" />
        </div>
      </template>

      <template #cell-content-name="{ props }">
        <a href="" @click.prevent="openRoundErrors(props.row)">{{ props.row.name }}</a>
      </template>

    </base-table>

    <q-dialog v-model="roundErrorsDialog">
      <q-card flat bordered>
        <q-card-section>
          {{ viewingError.name }}
          <br><br>
          {{ viewingError.file }}:{{ viewingError.line }}
          <q-markup-table flat bordered class="q-mt-md">
            <thead>
              <tr>
                <th>Round ID</th>
                <th>Occurrences</th>
              </tr>
            </thead>
            <tbody class="text-center">
              <tr v-for="(errors, roundId) in viewingError.round_error_counts">
                <td>
                  <Link :href="route('admin.errors.show', roundId)">
                    {{ roundId }}
                  </Link>
                </td>
                <td>{{ $formats.number(errors) }}</td>
              </tr>
            </tbody>
          </q-markup-table>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Close" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import BaseTable from '../BaseTable.vue'
import BaseSelect from '@/Components/Selects/BaseSelect.vue'
import ErrorsByRound from '@/Components/Charts/ErrorsByRound.vue'

export default {
  components: { BaseTable, BaseSelect, ErrorsByRound },
  data() {
    return {
      routes: {
        fetch: '/admin/errors/summary',
      },
      columns: [
        {
          name: 'count',
          label: 'Count',
          field: 'count',
          sortable: true,
          filterable: true,
          filter: { type: 'Range' },
          format: this.$formats.number,
          headerClasses: 'q-table--col-auto-width',
        },
        {
          name: 'round_count',
          label: 'Round Count',
          field: 'round_count',
          sortable: true,
          filterable: true,
          filter: { type: 'Range' },
          format: this.$formats.number,
          headerClasses: 'q-table--col-auto-width',
        },
        {
          name: 'name',
          label: 'Name',
          field: 'name',
          sortable: true,
          align: 'left',
          style: 'word-break: break-all; min-width: 300px;',
        },
        {
          name: 'file',
          label: 'File',
          field: 'file',
          sortable: true,
          align: 'left',
        },
        {
          name: 'line',
          label: 'Line',
          field: 'line',
          sortable: true,
        },
      ],
      search: {
        server: 'all',
        time_range: '1week',
      },
      timeRangeOptions: [
        { label: 'Last Week', value: '1week' },
        { label: 'Last 3 Days', value: '3days' },
        { label: 'Last Day', value: '1day' },
      ],
      rounds: [],
      roundErrorsDialog: false,
      viewingError: {},
    }
  },

  created() {
    this.rounds = this.$attrs.initial.data.length ? this.$attrs.initial.data[0].rounds : []
  },

  methods: {
    onTableLoaded({ filters }) {
      if (filters.server) this.search.server = filters.server
      if (filters.time_range) this.search.time_range = filters.time_range
    },

    onTableReset({ filters }) {
      this.search.server = filters.server
      this.search.time_range = filters.time_range
    },

    onTableFetch(data) {
      this.rounds = data.data.length ? data.data[0].rounds : []
    },

    openRoundErrors(error) {
      this.viewingError = error
      this.roundErrorsDialog = true
    },
  }
}
</script>
