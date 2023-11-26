<template>
  <div>
    <q-card class="q-mb-md" flat>
      <q-card-section>
        <div v-if="round.server" class="text-caption opacity-60 q-mb-xs">
          {{ round.server.name }}
        </div>
        <div class="text-weight-bold text-lg gh-link-card__title">
          {{ latestStationName }}
        </div>
        <div class="gh-details-list gh-details-list--small q-mt-sm">
          <div>
            <div>{{ started }}</div>
            <div>Started</div>
          </div>
          <div v-if="duration">
            <div>{{ duration }} minutes</div>
            <div>Duration</div>
          </div>
          <div v-if="duration">
            <div>{{ endedFromNow }}</div>
            <div>Ended</div>
          </div>
          <div v-if="map">
            <div>
              <a v-if="mapUri" :href="route('maps.show', mapUri)" target="_blank">
                {{ map }}
                <q-icon :name="ionOpenOutline" />
              </a>
              <template v-else>
                {{ map }}
              </template>
            </div>
            <div>Map</div>
          </div>
          <div v-if="round.game_type">
            <div>{{ round.game_type }}</div>
            <div>Game Type</div>
          </div>
        </div>
        <div class="badges">
          <q-badge
            v-if="round.rp_mode"
            class="q-pa-sm text-weight-bold"
            color="info"
            text-color="dark"
            >Roleplay</q-badge
          >
          <q-badge
            v-if="round.crashed"
            class="q-pa-sm text-weight-bold"
            color="negative"
            text-color="dark"
            >Crashed</q-badge
          >
        </div>
      </q-card-section>
    </q-card>

    <div class="row q-mb-md q-col-gutter-md">
      <div class="col-xs-12 col-md-6">
        <players-card :participations="round.participations" :round-duration="parseInt(duration)" />
      </div>

      <div class="col-xs-12 col-md-6">
        <deaths-card :deaths="round.deaths" :round-duration="parseInt(duration)" />
      </div>
    </div>

    <event-explorer :events="events" />
  </div>
</template>

<style lang="scss" scoped>
.badges {
  display: flex;
  gap: 5px;
  position: absolute;
  top: -2px;
  right: -2px;
}
</style>

<script>
import dayjs, { duration } from 'dayjs'
import { ionOpenOutline } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import EventExplorer from './Partials/EventExplorer/EventExplorer.vue'
import PlayersCard from './Partials/PlayersCard.vue'
import DeathsCard from './Partials/DeathsCard.vue'

export default {
  components: {
    EventExplorer,
    PlayersCard,
    DeathsCard,
  },

  layout: (h, page) =>
    h(
      AppLayout,
      {
        title: `Round #${page.props.round.id}`,
      },
      () => page
    ),

  setup() {
    return {
      dayjs,
      ionOpenOutline,
    }
  },

  props: {
    round: Object,
  },

  computed: {
    latestStationName() {
      if (!this.round.latest_station_name) return 'Space Station 13'
      return this.round.latest_station_name.name
    },

    map() {
      if (this.round.map_record) return this.round.map_record.name
      return this.round.map
    },

    mapUri() {
      if (!this.round.map_record) return
      return this.round.map_record.map_id.toLowerCase()
    },

    started() {
      if (!this.round.created_at) return 'Unknown'
      return dayjs(this.round.created_at).format('YYYY-MM-DD [at] h:mma')
    },

    duration() {
      if (!this.round.ended_at) return
      return dayjs(this.round.ended_at).diff(dayjs(this.round.created_at), 'm')
    },

    endedFromNow() {
      if (!this.round.ended_at) return
      return dayjs(this.round.ended_at).fromNow()
    },

    events() {
      const eventTypes = [
        'ai_laws',
        'deaths',
        'fines',
        'tickets',
        'antags',
        'antag_objectives',
        'antag_item_purchases',
      ]
      const events = {}
      eventTypes.forEach((eventType) => {
        events[eventType] = this.round[eventType]
      })

      events.bee_count = [{ data: this.round.bee_spawns_count }]
      return events
    },
  },
}
</script>
