<template>
  <q-card class="gh-card gh-card--small" flat>
    <div class="gh-card__header">
      <q-icon :name="ionPeople" size="22px" />
      <span>Players</span>
    </div>
    <q-card-section>
      <div class="gh-details-list q-mb-sm q-px-md">
        <div>
          <div>{{ $formats.number(playerCount) }}</div>
          <div>Players</div>
        </div>
        <div>
          <div>{{ averagePlayers }}</div>
          <div>Average joins per minute</div>
        </div>
      </div>

      <round-participations-over-time :data="participations" />
    </q-card-section>
  </q-card>
</template>

<script>
import dayjs from 'dayjs'
import { ionPeople } from '@quasar/extras/ionicons-v6'
import RoundParticipationsOverTime from '@/Components/Charts/RoundParticipationsOverTime.vue'

export default {
  setup() {
    return {
      dayjs,
      ionPeople,
    }
  },

  components: {
    RoundParticipationsOverTime
  },

  props: {
    participations: {
      type: Array,
      required: true
    },
    roundDuration: {
      type: Number,
      required: true
    }
  },

  computed: {
    playerCount() {
      return this.participations.length
    },

    averagePlayers() {
      return (this.playerCount / this.roundDuration).toFixed(2)
    },
  }
}
</script>
