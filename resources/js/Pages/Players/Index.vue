<template>
  <div>
    <!--
      most players online at once
        PlayersOnline::sortBy('amount', 'desc')->first()

      average players per day

      total amount of players seen
        Player::count()

      pie graph of connection countries

      line graph of players online over time
        line for each server
        line for total (maybe??)
    -->
    <div class="row q-mb-md q-col-gutter-md">
      <div class="col-12 col-md-4">
        <q-card flat>
          <q-card-section>
            <div class="text-weight-medium">Total Players (All Time)</div>
            <div class="text-xl text-weight-bolder text-primary">
              {{ $formats.number(totalPlayers) }}
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4">
        <q-card flat>
          <q-card-section>
            <div class="text-weight-medium">Most Players Online At Once</div>
            <div class="text-xl text-weight-bolder text-primary">
              {{ $formats.number(mostPlayersOnline) }}
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4">
        <q-card flat>
          <q-card-section>
            <div class="text-weight-medium">Average Players Online</div>
            <div class="text-xl text-weight-bolder text-primary">
              {{ $formats.number(averagePlayersOnline) }}
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <q-card class="gh-card" flat>
      <div class="gh-card__header">
        <q-icon :name="ionPeople" size="22px" />
        <span class="flex items-center">
          Daily Players
          <q-select
            v-model="dailyPlayersLength"
            class="q-ml-md"
            :options="dailyPlayersOptions"
            emit-value
            map-options
            dense
          />
        </span>
      </div>
      <q-card-section>
        <player-participations-over-time :data="filteredParticipations" />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { ionPeople } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import PlayersLayout from '@/Layouts/PlayersLayout.vue'
import PlayerParticipationsOverTime from '@/Components/Charts/PlayerParticipationsOverTime.vue'

export default {
  layout: (h, page) => {
    return h(AppLayout, { title: 'Players' }, () => h(PlayersLayout, () => page))
  },

  setup() {
    return {
      ionPeople,
    }
  },

  components: {
    PlayerParticipationsOverTime,
  },

  props: {
    participations: Array,
    totalPlayers: Number,
    mostPlayersOnline: Number,
    averagePlayersOnline: Number,
  },

  computed: {
    filteredParticipations() {
      if (!this.dailyPlayersLength) return this.participations
      return this.participations.slice(
        Math.max(this.participations.length - this.dailyPlayersLength, 0)
      )
    },
  },

  data() {
    return {
      dailyPlayersLength: null,
      dailyPlayersOptions: [
        { label: 'Last Week', value: 7 },
        { label: 'Last Month', value: 30 },
        { label: 'Last 3 Months', value: 91 },
        { label: 'Last 6 Months', value: 183 },
        { label: 'Last Year', value: null },
      ],
    }
  },
}
</script>
