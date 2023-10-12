<template>
  <q-item class="q-px-none q-pt-md">
    <q-item-section>
      <q-item-label caption>{{ round.server.name }}</q-item-label>
      <q-item-label>Round #{{ $formats.number(round.id) }}</q-item-label>
      <q-item-label caption> Lasted for {{ duration }}, {{ ended }} </q-item-label>
    </q-item-section>

    <q-item-section side>
      <q-btn
        flat
        color="primary"
        text-color="primary"
        :icon-right="ionEye"
        label="View"
        size="0.75rem"
        padding="xs sm"
      />
    </q-item-section>
  </q-item>
  <!-- <q-card class="gh-recent-round" :class="[round.map && `gh-recent-round--map-${round.map}`]" flat>
    <q-card-section>
      <div class="gh-recent-round__server q-mb-xs">
        {{ round.server.name }}
      </div>
      <div class="gh-recent-round__id q-mb-sm">Round #{{ $formats.number(round.id) }}</div>
      <div class="row items-center">
        <q-chip square class="q-ma-none" color="grey-9" text-color="white" size="sm">
          {{ round.game_type }}
        </q-chip>
        <div class="gh-recent-round__duration q-ml-auto">
          <em>Lasted for {{ duration }}, {{ ended }}</em>
        </div>
      </div>
    </q-card-section>
  </q-card> -->
</template>

<script>
import dayjs from 'dayjs'
import { ionEye } from '@quasar/extras/ionicons-v6'

export default {
  props: {
    round: Object,
  },

  setup() {
    return {
      ionEye
    }
  },

  computed: {
    ended() {
      const endedAt = dayjs(this.round.ended_at)
      return endedAt.from(dayjs())
    },

    duration() {
      const startedAt = dayjs(this.round.created_at)
      const endedAt = dayjs(this.round.ended_at)
      return startedAt.from(endedAt, true)
    },
  },
}
</script>

<style lang="scss" scoped>
.gh-recent-round {
  // .q-card__section {
  //   border-left: 5px solid var(--q-primary);
  // }

  &__server {
    font-weight: 500;
  }

  &__duration {
    opacity: 0.8;
    font-size: 0.9em;
  }
}
</style>
