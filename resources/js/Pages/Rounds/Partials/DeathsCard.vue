<template>
  <q-card class="gh-card gh-card--small" flat>
    <div class="gh-card__header">
      <q-icon :name="ionSkull" size="22px" />
      <span>Deaths</span>
    </div>
    <q-card-section>
      <div class="gh-details-list q-mb-sm q-px-md">
        <div>
          <div>{{ $formats.number(deathCount) }}</div>
          <div>Deaths</div>
        </div>
        <div>
          <div>{{ averageDeaths }}</div>
          <div>Average deaths per minute</div>
        </div>
      </div>

      <deaths-over-time :data="deaths" />
    </q-card-section>
  </q-card>
</template>

<script>
import dayjs from 'dayjs'
import { ionSkull } from '@quasar/extras/ionicons-v6'
import DeathsOverTime from '@/Components/Charts/DeathsOverTime.vue'

export default {
  setup() {
    return {
      dayjs,
      ionSkull,
    }
  },

  components: {
    DeathsOverTime
  },

  props: {
    deaths: {
      type: Array,
      required: true
    },
    roundDuration: {
      type: Number,
      required: true
    }
  },

  computed: {
    deathCount() {
      return this.deaths.length
    },

    averageDeaths() {
      if (!this.deathCount) return 0
      return (this.deathCount / this.roundDuration).toFixed(2)
    },
  }
}
</script>
