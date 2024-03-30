<template>
  <div>
    <round-summary class="q-mb-md" :round="round" />

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
import AppLayout from '@/Layouts/AppLayout.vue'
import RoundSummary from '@/Components/RoundSummary.vue'
import EventExplorer from './Partials/EventExplorer/EventExplorer.vue'
import PlayersCard from './Partials/PlayersCard.vue'
import DeathsCard from './Partials/DeathsCard.vue'

export default {
  components: {
    RoundSummary,
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

  props: {
    round: Object,
  },

  computed: {
    duration() {
      if (!this.round.ended_at) return
      return dayjs(this.round.ended_at).diff(dayjs(this.round.created_at), 'm')
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
