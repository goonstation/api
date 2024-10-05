<template>
  <div>
    <q-card class="q-mb-md" flat>
      <q-card-section class="flex gap-xs-md items-start q-pl-lg q-pr-md q-pb-md">
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
        <q-space />
        <div>
          <div class="q-mb-sm">
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
          </div>
          <div class="text-right">
            <q-btn
              outline
              color="primary"
              text-color="primary"
              @click="
                router.visit(
                  route('admin.bans.show-remove-details', {
                    ckey: player.ckey,
                    comp_id: latestConnection.comp_id,
                    ip: latestConnection.ip,
                  })
                )
              "
              label="Unban"
              size="sm"
            />
          </div>
        </div>
      </q-card-section>

      <q-markup-table class="player-details" bordered dense>
        <tbody>
          <tr v-if="latestConnection">
            <td>Last Seen IP</td>
            <td>
              {{ latestConnection.ip }}
              <ips :connections="player.connections" class="q-ml-sm" />
            </td>
          </tr>
          <tr v-if="latestConnection">
            <td>Last Seen Computer ID</td>
            <td>
              {{ latestConnection.comp_id }}
              <comp-ids
                :connections="player.connections"
                :cursed-comp-ids="cursedCompIds"
                class="q-ml-sm"
              />
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
          <tr v-if="player.byond_major && player.byond_minor">
            <td>BYOND Version</td>
            <td>{{ player.byond_major }}.{{ player.byond_minor }}</td>
          </tr>
        </tbody>
      </q-markup-table>
    </q-card>

    <q-card class="gh-card gh-card--small q-mb-md" flat>
      <div class="gh-card__header">
        <q-icon :name="ionEarth" size="22px" />
        <span>Connections ({{ player.connections.length }})</span>
      </div>
      <q-card-section class="q-pa-none">
        <connections :connections="player.connections" />
      </q-card-section>
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
            <template v-if="type === 'Ban History'"> ({{ banHistory.length }}) </template>
            <template v-else-if="type === 'Job Ban History'">
              ({{ player.job_bans.length }})
            </template>
          </q-tab>
        </q-tabs>
      </div>
      <q-card-section class="q-pa-none">
        <q-tab-panels v-model="banTab" animated>
          <q-tab-panel name="Ban History" class="q-pa-none">
            <ban-history :bans="banHistory" :ckey="player.ckey" />
          </q-tab-panel>
          <q-tab-panel name="Job Ban History" class="q-pa-none">
            <job-ban-history :bans="player.job_bans" />
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>

    <q-card class="gh-card gh-card--small q-mb-md" flat>
      <div class="gh-card__header flex">
        <q-icon :name="ionPencil" size="22px" />
        <span>Notes ({{ player.notes.length }})</span>
        <q-space />
        <add-player-note-dialog :player="player" @success="onNoteAdded" size="sm" />
      </div>
      <q-card-section class="q-pa-none">
        <notes :notes="player.notes" />
      </q-card-section>
    </q-card>

    <q-card class="gh-card gh-card--small q-mb-md" flat>
      <div class="gh-card__header flex">
        <q-icon :name="ionMedal" size="22px" />
        <span>Medals ({{ player.medals.length }})</span>
        <q-space />
        <add-player-medal-dialog :player="player" @success="onMedalAdded" size="sm" />
      </div>
      <q-card-section class="q-pa-none">
        <medals v-model="player.medals" :player-id="player.id" />
      </q-card-section>
    </q-card>

    <q-card class="gh-card gh-card--small" flat>
      <div class="gh-card__header">
        <q-icon :name="ionPeople" size="22px" />
        <span>Other Accounts ({{ otherAccounts.length }})</span>
      </div>
      <q-card-section class="q-pa-none">
        <q-banner class="bg-grey-10 q-ma-md">
          <template v-slot:avatar>
            <q-icon :name="ionInformationCircleOutline" color="primary" size="md" class="q-mt-xs" />
          </template>
          These are accounts that have connected with the same IP Address or Computer ID as this
          player. Please note that this doesn't always mean they are played by the same person. This
          information is provided for investigation purposes only.
        </q-banner>
        <other-accounts :accounts="otherAccounts" />
      </q-card-section>
    </q-card>
  </div>
</template>

<style lang="scss" scoped>
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
import {
  ionEarth,
  ionBan,
  ionPencil,
  ionPeople,
  ionInformationCircleOutline,
  ionMedal,
} from '@quasar/extras/ionicons-v6'
import { router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PlayerAvatar from '@/Components/PlayerAvatar.vue'
import AddPlayerNoteDialog from '@/Components/AddPlayerNoteDialog.vue'
import AddPlayerMedalDialog from '@/Components/AddPlayerMedalDialog.vue'
import Ips from './Partials/Ips.vue'
import CompIds from './Partials/CompIds.vue'
import Connections from './Partials/Connections.vue'
import BanHistory from './Partials/BanHistory.vue'
import JobBanHistory from './Partials/JobBanHistory.vue'
import Notes from './Partials/Notes.vue'
import Medals from './Partials/Medals.vue'
import OtherAccounts from './Partials/OtherAccounts.vue'

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
    AddPlayerNoteDialog,
    AddPlayerMedalDialog,
    Ips,
    CompIds,
    Connections,
    BanHistory,
    JobBanHistory,
    Notes,
    Medals,
    OtherAccounts,
  },

  setup() {
    return {
      router,
      dayjs,
      ionEarth,
      ionBan,
      ionPencil,
      ionPeople,
      ionInformationCircleOutline,
      ionMedal,
    }
  },

  props: {
    player: Object,
    latestRound: Object,
    banHistory: Object,
    otherAccounts: Object,
    cursedCompIds: Object,
    uniqueIps: Object,
    uniqueCompIds: Object,
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
        if (ban.active && ban.player_has_active_details) {
          banned = true
          break
        }
      }
      return banned
    },
  },

  methods: {
    onNoteAdded(note) {
      this.player.notes.unshift(note)
    },
    onMedalAdded(medal) {
      this.player.medals.unshift(medal)
    },
  },
}
</script>
