<template>
  <div class="q-table__grid-item col-xs-12 col-md-6 col-lg-4">
    <Link :href="`/rounds/${item.row.id}`" class="gh-link-card q-py-sm">
      <div class="text-caption opacity-60 q-mb-xs">
        {{ item.row.server.name }}
      </div>
      <div class="text-weight-bold ellipsis gh-link-card__title">
        {{ latestStationName }}
      </div>
      <div class="gh-details-list gh-details-list--small q-mt-sm">
        <div>
          <div>{{ started }}</div>
          <div>Started</div>
        </div>
        <div v-if="duration">
          <div>{{ duration }} minutes</div>
          <div>
            Duration
            <q-icon :name="ionInformationCircle" />
          </div>
          <q-tooltip :offset="[0, 5]" class="text-sm">
            Ended {{ endedFromNow }}
          </q-tooltip>
        </div>
        <div v-if="item.row.map">
          <div>{{ item.row.map }}</div>
          <div>Map</div>
        </div>
        <div v-if="item.row.game_type">
          <div>{{ item.row.game_type }}</div>
          <div>Game Type</div>
        </div>
      </div>
      <q-badge v-if="item.row.crashed" color="negative" floating>Crashed</q-badge>
    </Link>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import dayjs, { duration } from 'dayjs'
import { ionInformationCircle } from '@quasar/extras/ionicons-v6'

export default {
  components: { Link },

  setup() {
    return {
      dayjs,
      ionInformationCircle
    }
  },

  props: {
    item: Object
  },

  computed: {
    latestStationName() {
      if (!this.item.row.latest_station_name?.length) return 'Space Station 13'
      return this.item.row.latest_station_name[0].name
    },

    started() {
      if (!this.item.row.created_at) return 'Unknown'
      return dayjs(this.item.row.created_at).format('YYYY-MM-DD [at] h:mma')
    },

    duration() {
      if (!this.item.row.ended_at) return
      return dayjs(this.item.row.ended_at).diff(dayjs(this.item.row.created_at), 'm')
    },

    endedFromNow() {
      if (!this.item.row.ended_at) return
      return dayjs(this.item.row.ended_at).fromNow()
    }
  }
}
</script>
