<template>
  <base-table
    v-bind="$attrs"
    :fetch-route="fetchRoute"
    :columns="columns"
    loading-label=""
    hide-no-data
    no-timestamp-toggle
    no-filters
    grid
  >
    <template v-slot:item="props">
      <div class="q-table__grid-item col-xs-12 col-sm-4 col-md-3">
        <Link :href="`/players/${props.row.id}`" class="gh-link-card">
          <div class="flex items-center no-wrap">
            <q-avatar
              class="q-mr-sm text-uppercase text-bold"
              :style="`background-color: ${getAvatarBg(props.row.ckey)};`"
              font-size="0.35em"
              text-color="dark"
              size="md"
            >
              {{ props.row.ckey.substr(0, 2) }}
            </q-avatar>
            <div>
              <div class="text-weight-bold ellipsis">
                <template v-if="props.row.key">{{ props.row.key }}</template>
                <template v-else>{{ $formats.capitalize(props.row.ckey) }}</template>
              </div>
              <div class="text-caption">
                Last seen
                <template v-if="props.row.latest_connection">
                  {{ dayjs(props.row.latest_connection.created_at).fromNow() }}
                </template>
                <template v-else>never</template>
              </div>
            </div>
          </div>
        </Link>
      </div>
    </template>
  </base-table>
</template>

<style lang="scss" scoped>
.gh-link-card {
  .text-caption {
    opacity: 0.6;
  }
}
</style>

<script>
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { getHsla } from 'pastel-color'
import BaseTable from './BaseTable.vue'

export default {
  components: { Link, BaseTable },

  setup() {
    return {
      dayjs,
    }
  },

  data() {
    return {
      fetchRoute: '/players/search',
      columns: [
        { name: 'ckey', label: 'Ckey', field: 'ckey', sortable: true },
        { name: 'key', label: 'Key', field: 'key', sortable: true },
        // {
        //   name: 'connections',
        //   label: 'Connections',
        //   field: 'connections_count',
        //   sortable: true,
        //   filter: {
        //     type: 'range',
        //   },
        // },
        // {
        //   name: 'participations',
        //   label: 'Participations',
        //   field: 'participations_count',
        //   sortable: true,
        //   filter: {
        //     type: 'range',
        //   },
        // },
      ],
    }
  },

  methods: {
    getAvatarBg(val) {
      return getHsla(val)
    },
  },
}
</script>
