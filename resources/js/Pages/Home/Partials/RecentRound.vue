<template>
  <q-item class="q-px-none q-pt-md">
    <q-item-section>
      <q-item-label caption>{{ round.server.name }}</q-item-label>
      <q-item-label class="station-name">
        <template v-if="round.latest_station_name">
          {{ round.latest_station_name.name }}
        </template>
        <template v-else> Space Station 13 </template>
      </q-item-label>
      <q-item-label caption> Lasted for {{ duration }}, {{ ended }} </q-item-label>
    </q-item-section>

    <q-item-section side>
      <q-btn
        flat
        color="primary"
        text-color="primary"
        :icon-right="ionEye"
        @click="router.visit(route('rounds.show', round.id))"
        label="View"
        size="0.75rem"
        padding="xs sm"
      />
    </q-item-section>
  </q-item>
</template>

<style lang="scss" scoped>
.station-name {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>

<script>
import dayjs from 'dayjs'
import { router } from '@inertiajs/vue3'
import { ionEye } from '@quasar/extras/ionicons-v6'

export default {
  props: {
    round: Object,
  },

  setup() {
    return {
      router,
      ionEye,
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
