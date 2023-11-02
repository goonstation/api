<template>
  <base-table
    v-bind="$attrs"
    :routes="routes"
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
            <player-avatar :player="props.row" class="q-mr-sm" size="md" />
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
import BaseTable from './BaseTable.vue'
import PlayerAvatar from '@/Components/PlayerAvatar.vue'

export default {
  components: {
    Link,
    BaseTable,
    PlayerAvatar,
  },

  setup() {
    return {
      dayjs,
    }
  },

  data() {
    return {
      routes: { fetch: '/players/search' },
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
}
</script>
