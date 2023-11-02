<template>
  <q-card flat>
    <q-card-section class="flex gap-xs-md items-start q-pl-lg q-pr-xl q-pb-md">
      <player-avatar :player="player" class="q-mt-xs" />
      <div class="q-pb-xs">
        <div class="text-weight-bold text-h6">
          <template v-if="player.key">{{ player.key }}</template>
          <template v-else>{{ $formats.capitalize(player.ckey) }}</template>
        </div>
        <div v-if="player.latest_connection" class="text-caption text-grey-5">
          Last seen {{ dayjs(player.latest_connection.created_at).fromNow() }}
        </div>
        <div class="text-caption text-grey-5">
          Started playing {{ dayjs(player.first_connection.created_at).fromNow() }}
        </div>
      </div>
    </q-card-section>

    <q-markup-table class="player-details" bordered>
      <tbody>
        <tr v-if="latestRound">
          <td>Last Round Played</td>
          <td>
            <Link :href="`/rounds/${latestRound.id}`">
              <template v-if="latestRound.latest_station_name">
                {{ latestRound.latest_station_name.name }}
              </template>
              <template v-else> #{{ latestRound.id }} </template>
            </Link>
          </td>
        </tr>
        <tr v-if="favoriteJob">
          <td>Favorite Job</td>
          <td>{{ favoriteJob }}</td>
        </tr>
        <tr>
          <td>Classic Rounds Played</td>
          <td>
            {{ $formats.number(player.participations_count - player.participations_rp_count) }}
          </td>
        </tr>
        <tr>
          <td>Roleplay Rounds Played</td>
          <td>{{ $formats.number(player.participations_rp_count) }}</td>
        </tr>
        <tr>
          <td>Hours Played</td>
          <td>{{ $formats.number(totalPlaytime) }}</td>
        </tr>
        <tr>
          <td>Total Deaths</td>
          <td>{{ $formats.number(player.deaths_count) }}</td>
        </tr>
      </tbody>
    </q-markup-table>
  </q-card>
</template>

<style lang="scss" scoped>
.q-card {
  max-width: 500px;
}

.player-details {
  tr {
    td:first-child {
      width: 0;
      font-weight: 600;
    }
    td:last-child {
      white-space: unset;
    }
  }
}
</style>

<script>
import dayjs from 'dayjs'
import { ionStarHalfOutline } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import PlayerAvatar from '@/Components/PlayerAvatar.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Player' }, () => page),

  components: {
    PlayerAvatar,
  },

  setup() {
    return {
      dayjs,
      ionStarHalfOutline,
    }
  },

  props: {
    player: Object,
    latestRound: Object,
    favoriteJob: String,
  },

  computed: {
    totalPlaytime() {
      if (!this.player.playtime.length) return 0
      const seconds = this.player.playtime
        .map((item) => item.seconds_played)
        .reduce((prev, next) => prev + next)
      return Math.round((seconds / 3600 + Number.EPSILON) * 100) / 100
    },
  },
}
</script>
