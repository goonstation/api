<template>
  <div>
    <q-card class="q-mb-md" flat>
      <q-card-section class="flex gap-xs-md items-start q-pl-lg q-pr-xl q-pb-md">
        <player-avatar :player="player" class="q-mt-xs" />
        <div class="q-pb-xs">
          <div class="text-weight-bold text-h6">
            <template v-if="player.key">{{ player.key }}</template>
            <template v-else>{{ $formats.capitalize(player.ckey) }}</template>
          </div>
          <div v-if="latestConnection" class="text-caption text-grey-5">
            Last seen {{ dayjs(latestConnection.created_at).fromNow() }}
          </div>
          <div v-if="firstConnection" class="text-caption text-grey-5">
            Started playing {{ dayjs(firstConnection.created_at).fromNow() }}
          </div>
        </div>
      </q-card-section>

      <q-markup-table class="player-details" bordered>
        <tbody>
          <tr>
            <td colspan="2">
              <q-chip
                v-if="isBanned"
                color="negative"
                text-color="dark"
                class="text-weight-bold"
                square
                >Banned</q-chip
              >
              <q-chip v-else color="positive" text-color="dark" class="text-weight-bold" square
                >Not Banned</q-chip
              >
              <q-chip
                v-if="player.vpn_whitelist"
                color="info"
                text-color="dark"
                class="text-weight-bold"
                square
              >
                VPN Whitelisted
              </q-chip>
            </td>
          </tr>
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
        </tbody>
      </q-markup-table>
    </q-card>

    <q-card class="gh-card gh-card--small q-mb-md" flat>
      <div class="gh-card__header">
        <q-icon :name="ionBan" size="22px" />
        <q-tabs v-model="banTab" active-color="primary" indicator-color="transparent">
          <q-tab
            v-for="type in banTabTypes"
            class="items-center"
            :name="type"
            content-class="q-pa-sm text-sm text-weight-medium"
          >
            {{ type }}
          </q-tab>
        </q-tabs>
      </div>
      <q-card-section class="q-pa-none">
        <q-tab-panels v-model="banTab" animated>
          <q-tab-panel name="Ban History" class="q-pa-none">
            <ban-history :bans="banHistory" />
          </q-tab-panel>
          <q-tab-panel name="Job Ban History" class="q-pa-none">
            <job-ban-history :bans="player.job_bans" />
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>

    <q-card class="gh-card gh-card--small" flat>
      <div class="gh-card__header">
        <q-icon :name="ionPencil" size="22px" />
        <span>Notes</span>
      </div>
      <q-card-section class="q-pa-none">
        <notes :notes="player.notes" />
      </q-card-section>
    </q-card>

    <pre>
      stats (same as public player view page)
      labels
        vpn whitelisted
        banned
      connection history (graph with drill down into specific rounds)
      associated accounts (players with the same IP/compID details)
      ban/jobban history
      notes
    </pre>
  </div>
</template>

<style lang="scss" scoped>
// .q-card {
//   max-width: 500px;
// }

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
import { ionBan, ionPencil } from '@quasar/extras/ionicons-v6'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PlayerAvatar from '@/Components/PlayerAvatar.vue'
import BanHistory from './Partials/BanHistory.vue'
import JobBanHistory from './Partials/JobBanHistory.vue'
import Notes from './Partials/Notes.vue'

export default {
  layout: (h, page) =>
    h(
      AdminLayout,
      {
        title: `Player ${page.props.player.key || page.props.player.ckey}`,
      },
      () => page
    ),

  components: {
    Link,
    PlayerAvatar,
    BanHistory,
    JobBanHistory,
    Notes,
  },

  setup() {
    return {
      dayjs,
      ionBan,
      ionPencil,
    }
  },

  props: {
    player: Object,
    latestRound: Object,
    banHistory: Object,
  },

  data() {
    return {
      banTab: 'Ban History',
      banTabTypes: ['Ban History', 'Job Ban History'],
    }
  },

  computed: {
    firstConnection() {
      return this.player.connections[0]
    },

    latestConnection() {
      return this.player.connections[this.player.connections.length - 1]
    },

    totalPlaytime() {
      if (!this.player.playtime.length) return 0
      const seconds = this.player.playtime
        .map((item) => item.seconds_played)
        .reduce((prev, next) => prev + next)
      return Math.round((seconds / 3600 + Number.EPSILON) * 100) / 100
    },

    isBanned() {
      let banned = false
      for (const ban of this.banHistory) {
        if (!this.isBanExpiredOrRemoved(ban)) {
          banned = true
          break
        }
      }
      return banned
    },
  },

  methods: {
    isBanExpired(expiresAt) {
      if (!expiresAt) return false
      return new Date(expiresAt) <= new Date()
    },

    isBanExpiredOrRemoved(ban) {
      return ban.deleted_at || this.isBanExpired(ban.expires_at)
    },
  },
}
</script>
