<template>
  <div>
    <div class="row q-mb-md q-col-gutter-md">
      <div class="col-12 col-md-auto">
        <q-card flat>
          <q-card-section class="flex gap-xs-md items-start q-pl-lg q-pr-xl q-pb-md">
            <q-avatar class="q-mt-xs" :style="`background-color: ${avatarBg};`" font-size="0.35em">
              {{ initials }}
            </q-avatar>
            <div class="q-pb-xs">
              <div class="text-weight-bold text-h6">
                <template v-if="player.key">{{ player.key }}</template>
                <template v-else>{{ $formats.capitalize(player.ckey) }}</template>
              </div>
              <div class="text-caption text-grey-5">
                Last seen {{ dayjs(player.latest_connection.created_at).fromNow() }}
              </div>
              <div class="text-caption text-grey-5">
                Started playing {{ dayjs(player.first_connection.created_at).fromNow() }}
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col flex items-center justify-evenly gap-xs-lg">
        <div class="gh-big-stat">
          <div class="gh-big-stat__number">
            {{ $formats.number(player.participations_count) }}
          </div>
          <div class="gh-big-stat__label">Rounds Played</div>
        </div>
        <div class="gh-big-stat">
          <div class="gh-big-stat__number">
            {{ $formats.number(totalPlaytime) }}
          </div>
          <div class="gh-big-stat__label">Hours Played</div>
        </div>
        <div class="gh-big-stat">
          <div class="gh-big-stat__number">
            {{ $formats.number(player.deaths_count) }}
          </div>
          <div class="gh-big-stat__label">Deaths</div>
        </div>
      </div>
    </div>

    <!-- <q-card class="gh-card" flat>
      <div class="gh-card__header">
        <q-icon :name="ionStarHalfOutline" size="22px" />
        <span>Most Picked Antagonist Roles</span>
      </div>
      <q-card-section class="q-py-sm">
        <q-list separator>
          <q-item v-for="antag in antagPicks">
            <q-item-section>{{ antag.traitor_type }}</q-item-section>
            <q-item-section side>{{ antag.picked }}</q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card> -->

    <!-- <div class="row q-col-gutter-md q-mb-md">
      <div class="col-12 col-md-6">
        <q-card flat class="gh-last-round">
          <q-card-section>
            foo
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-6">
        <q-markup-table flat separator="none">
          <thead>
            <tr>
              <th colspan="2" class="text-left">
                <div class="text-body1">Most Played Antagonists</div>
              </th>
            </tr>
            <tr>
              <th class="text-left">Antagonist Type</th>
              <th class="text-left">Rounds Played</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="antag in antagPicks">
              <td class="text-left">
                {{ $formats.capitalize(antag.traitor_type) }}
              </td>
              <td class="text-left">
                {{ $formats.number(antag.picked) }}
              </td>
            </tr>
          </tbody>
        </q-markup-table>
      </div>
    </div>

    <q-card flat>
      <q-card-section>
        <div style="position: relative;">
          <player-connections-over-time :data="connections" />
        </div>
      </q-card-section>
    </q-card> -->

    <br /><br /><br />
    <pre>
      if latest_connected has round_id
        link to last connected round

      favorite job / a generated title based on rounds played as job

      graph of connections over time
    </pre>
  </div>
</template>

<style lang="scss" scoped>
.gh-big-stat {
  &__number {
    margin-bottom: 5px;
    font-size: 3.5em;
    font-weight: bold;
    line-height: 1;
    letter-spacing: 5px;
  }

  &__label {
    opacity: 0.7;
    font-size: 0.9em;
  }
}

.gh-last-round {
  position: relative;
  overflow: hidden;

  &:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 4px;
    background: var(--q-primary);
    transition: all 200ms;
  }
}
</style>

<script>
import { getHsla } from 'pastel-color'
import dayjs from 'dayjs'
import { ionStarHalfOutline } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
// import PlayerConnectionsOverTime from '@/Components/Charts/PlayerConnectionsOverTime.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Player' }, () => page),

  components: {
    // PlayerConnectionsOverTime,
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
    connections: Object,
  },

  computed: {
    displayableKey() {
      return this.player.key || this.player.ckey
    },

    avatarBg() {
      return getHsla(this.player.ckey)
    },

    initials() {
      const names = this.displayableKey.split(' ')
      let initials = names[0].substring(0, 1).toUpperCase()
      if (names.length > 1) {
        initials += names[names.length - 1].substring(0, 1).toUpperCase()
      }
      return initials
    },

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
