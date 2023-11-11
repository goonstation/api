<template>
  <div class="welcome q-mt-sm q-mb-md">
    <img src="@img/bee.gif" alt="" class="gh-sprite" width="64" height="64" />
    <p class="bg-dark q-py-md q-px-lg q-mb-none">
      <span class="arrow" aria-hidden="true"></span>
      Welcome to Goonhub, an information website designed to collect and display statistics from the
      Goonstation branch of the popular game Space Station 13 developed on BYOND, and then show them
      in a meaningful and useful way. Designed as a one-stop shop for all your out-of-game nerding
      out. Have fun!
    </p>
  </div>

  <div class="row q-col-gutter-md q-mb-md">
    <div class="col-12 col-md-6 flex column no-wrap">
      <player-trend :data="_playersOnline" class="q-mb-md" />
      <div class="server-statuses gap-xs-sm">
        <server-status
          v-for="server in servers"
          :ref="`serverStatus${server.server_id}`"
          :server="server"
          @refreshed="onServerStatusRefreshed"
        />
      </div>
    </div>

    <div class="col-12 col-md-6">
      <useful-links />
      <q-card class="gh-card" flat>
        <div class="gh-card__header">
          <q-icon :name="ionNotifications" size="22px" />
          <span>Recent Rounds</span>
        </div>
        <q-card-section class="q-py-sm">
          <q-list separator>
            <recent-round
              v-for="round in lastRounds"
              class="q-mb-sm"
              :key="round.id"
              :round="round"
            />
          </q-list>
        </q-card-section>
      </q-card>
    </div>
  </div>

  <q-card class="gh-card changelog" flat>
    <div class="gh-card__header">
      <q-icon :name="ionDocument" size="22px" />
      <span>Changelog</span>
    </div>
    <q-card-section class="q-py-sm">
      <q-scroll-area class="q-pr-md" style="height: 500px" visible>
        <changelog :changelog="changelog" />

        <div class="text-center q-mb-md">
          <q-btn
            unelevated
            color="grey-9"
            size="md"
            type="link"
            href="/changelog"
            @click.prevent="router.visit('/changelog')"
            label="View the full changelog"
          />
        </div>
      </q-scroll-area>
    </q-card-section>
  </q-card>
</template>

<style lang="scss" scoped>
.welcome {
  display: flex;
  align-items: flex-end;

  img {
    display: none;
    width: 50px;
    height: 50px;
    margin-right: 35px;
  }

  p {
    position: relative;
    font-size: 1.1em;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;

    .arrow {
      position: absolute;
      display: none;
      bottom: 0;
      left: 0;
      transform: translateX(-100%);
      width: 30px;
      height: 30px;
      overflow: hidden;

      &:before {
        content: '';
        display: block;
        width: 200%;
        height: 200%;
        background: var(--q-dark);
        transform: translate(0, 15px) rotate(45deg);
      }
    }
  }

  @media (min-width: $breakpoint-sm-min) {
    img {
      display: block;
    }

    p {
      .arrow {
        display: block;
      }
    }
  }

  @media (min-width: $breakpoint-md-min) {
    img {
      width: 64px;
      height: 64px;
    }
  }
}

.server-statuses {
  display: flex;
  flex-direction: column;
  height: 100%;

  .server-status {
    flex-grow: 1;
  }
}
</style>

<script>
import dayjs from 'dayjs'
import { router } from '@inertiajs/vue3'
import { ionNotifications, ionDocument } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import Changelog from '@/Components/Changelog/Changelog.vue'
import PlayerTrend from './Partials/PlayerTrend.vue'
import ServerStatus from './Partials/ServerStatus.vue'
import UsefulLinks from './Partials/UsefulLinks.vue'
import RecentRound from './Partials/RecentRound.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Home' }, () => page),

  setup() {
    return {
      router,
      ionNotifications,
      ionDocument,
    }
  },

  components: {
    PlayerTrend,
    ServerStatus,
    UsefulLinks,
    RecentRound,
    Changelog,
  },

  data() {
    return {
      _playersOnline: null,
    }
  },

  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    servers: Array,
    playersOnline: Object,
    lastRounds: Object,
    changelog: Object,
  },

  created() {
    this._playersOnline = this.playersOnline
  },

  methods: {
    onServerStatusRefreshed({ serverId, status, error }) {
      let totalPlayers = error ? 0 : parseInt(status.players)
      // Check all the other servers for their reported player counts
      for (const server of this.servers) {
        if (serverId === server.server_id) continue
        const serverStatusRef = this.$refs[`serverStatus${server.server_id}`][0]
        if (serverStatusRef.status?.players) {
          totalPlayers += parseInt(serverStatusRef.status.players)
        }
      }

      // Insert or update the "online right now" entry for the players online chart
      if (this._playersOnline.length) {
        this._playersOnline[this._playersOnline.length - 1].online = totalPlayers
      } else {
        this._playersOnline.push({
          date: dayjs().format('YYYY-MM-DD'),
          online: totalPlayers
        })
      }
    }
  }
}
</script>
