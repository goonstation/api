<template>
  <div>
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
              {{ $formats.number(totalAveragePlayersOnline) }}
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <q-card class="gh-card q-mb-md" flat>
      <div class="gh-card__header">
        <q-icon :name="ionPeople" size="22px" />
        <span class="flex items-center">
          Average Players Online
          <!-- <q-select
            v-model="dailyPlayersLength"
            class="q-ml-md"
            :options="dailyPlayersOptions"
            emit-value
            map-options
            dense
          /> -->
        </span>
      </div>
      <q-card-section>
        <players-over-time :data="averagePlayersOnline" />
      </q-card-section>
    </q-card>

    <q-card class="gh-card q-mb-md" flat>
      <div class="gh-card__header">
        <q-icon :name="ionPeople" size="22px" />
        <span class="flex items-center">
          Unique Daily Players
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

    <div class="row q-col-gutter-md">
      <div class="col-12 col-md-6">
        <q-card class="gh-card" flat>
          <div class="gh-card__header">
            <q-icon :name="ionPeople" size="22px" />
            <span>Players By Country</span>
          </div>
          <q-card-section>
            <players-by-country :data="playersByCountry" />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-6">
        <q-card class="gh-card" flat style="height: 100%;">
          <div class="gh-card__header">
            <q-icon :name="ionPeople" size="22px" />
            <span>Something</span>
          </div>
          <q-card-section>
            Some chart here that shows something about overall player statistics.
            Give me ideas.
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script>
import { ionPeople } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import PlayersLayout from '@/Layouts/PlayersLayout.vue'
import PlayersOverTime from '@/Components/Charts/PlayersOverTime.vue'
import PlayerParticipationsOverTime from '@/Components/Charts/PlayerParticipationsOverTime.vue'
import PlayersByCountry from '@/Components/Charts/PlayersByCountry.vue'

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
    PlayersOverTime,
    PlayerParticipationsOverTime,
    PlayersByCountry,
  },

  props: {
    averagePlayersOnline: Object,
    participations: Array,
    playersByCountry: Array,
    totalPlayers: Number,
    mostPlayersOnline: Number,
    totalAveragePlayersOnline: Number,
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
      dailyPlayersLength: 365,
      dailyPlayersOptions: [
        { label: 'Last Week', value: 7 },
        { label: 'Last Month', value: 30 },
        { label: 'Last 3 Months', value: 91 },
        { label: 'Last 6 Months', value: 183 },
        { label: 'Last Year', value: 365 },
        { label: 'All Time', value: null },
      ],
    }
  },
}
</script>
