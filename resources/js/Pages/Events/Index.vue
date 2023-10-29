<template>
  <div>
    <div class="row q-col-gutter-md q-mb-md">
      <!-- <div class="col-xs-12 col-md-auto" :style="verticalCategories && 'max-width: 150px'">
        <div flat class="flex items-center gap-xs-sm">
          <q-icon :name="verticalCategories ? ionReturnUpBack : ionArrowUp" size="3rem" />
          <div class="text-body2">Explore events in detail here.</div>
        </div>
      </div> -->

      <div class="col">
        <div class="row q-col-gutter-sm event-count-wrap">
          <div v-for="countCard in countCards" class="col">
            <event-count :label="countCard.label" :count="countCard.counts" />
          </div>
        </div>
      </div>
    </div>

    <q-card class="gh-card q-mb-md" flat>
      <div class="gh-card__header">
        <q-icon :name="ionLayers" size="22px" />
        <span class="flex items-center">
          <q-select
            v-model="eventStatsType"
            :options="eventStatsTypeOptions"
            emit-value
            map-options
            dense
          />
          <q-select
            v-model="eventStatsDuration"
            class="q-ml-md"
            :options="eventStatsDurationOptions"
            emit-value
            map-options
            dense
          />
        </span>
      </div>
      <q-card-section style="position: relative; min-height: 400px">
        <events-over-time
          v-if="eventStats"
          :data="filteredEventStats"
          :type="eventStatsTypeLabel"
        />
        <div v-if="eventStatsLoading" class="chart-no-data">Loading</div>
      </q-card-section>
    </q-card>
  </div>
</template>

<style lang="scss" scoped>
.event-count-wrap {
  flex-direction: column;

  .col {
    width: 100%;
  }

  @media (min-width: $breakpoint-sm-min) {
    flex-direction: row;
    .col {
      min-width: 210px;
    }
  }
}
</style>

<script>
import axios from 'axios'
import { ionReturnUpBack, ionArrowUp, ionLayers } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'
import EventsOverTime from '@/Components/Charts/EventsOverTime.vue'
import EventCount from './Partials/EventCount.vue'

export default {
  components: {
    EventCount,
    EventsOverTime,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Events' }, () => h(EventsLayout, () => page))
  },

  setup() {
    return {
      ionReturnUpBack,
      ionArrowUp,
      ionLayers,
    }
  },

  data() {
    return {
      eventStats: null,
      eventStatsLoading: false,
      eventStatsType: 'deaths',
      eventStatsTypeOptions: [
        { label: 'Antagonists', value: 'antags' },
        { label: 'Bees', value: 'bee_spawns' },
        { label: 'Deaths', value: 'deaths' },
        { label: 'Fines', value: 'fines' },
        { label: 'Tickets', value: 'tickets' },
      ],
      eventStatsDuration: 365,
      eventStatsDurationOptions: [
        { label: 'Last Week', value: 7 },
        { label: 'Last Month', value: 30 },
        { label: 'Last 3 Months', value: 91 },
        { label: 'Last 6 Months', value: 183 },
        { label: 'Last Year', value: 365 },
      ],
    }
  },

  props: {
    counts: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },

  computed: {
    countCards() {
      return [
        { label: 'Antagonists', counts: this.counts.antags },
        { label: 'Deaths', counts: this.counts.deaths },
        { label: 'Fines', counts: this.counts.fines },
        { label: 'Tickets', counts: this.counts.tickets },
        { label: 'Bees', counts: this.counts.bees },
      ]
    },

    verticalCategories() {
      return !this.$q.screen.lt.md
    },

    filteredEventStats() {
      return this.eventStats.slice(Math.max(this.eventStats.length - this.eventStatsDuration, 0))
    },

    eventStatsTypeLabel() {
      return this.eventStatsTypeOptions.find((item) => item.value === this.eventStatsType).label
    },
  },

  methods: {
    async getEventStats() {
      this.eventStatsLoading = true
      const response = await axios.get('/events/stats', {
        params: {
          type: this.eventStatsType,
        },
      })
      this.eventStats = response.data
      this.eventStatsLoading = false
    },
  },

  watch: {
    eventStatsType: {
      immediate: true,
      handler() {
        this.getEventStats()
      },
    },
  },
}
</script>
