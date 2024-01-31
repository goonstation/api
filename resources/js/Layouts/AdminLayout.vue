<template>
  <Head :title="title" />
  <q-layout view="lhh LpR fff">
    <q-header class="bg-transparent">
      <q-toolbar class="q-pt-md">
        <q-btn dense flat round :icon="ionMenu" @click="siteNavOpen = !siteNavOpen" />
        <q-toolbar-title>
          <page-back class="q-mr-sm" />
          <slot v-if="$slots.header" name="header" />
          <template v-else>{{ title }}</template>
        </q-toolbar-title>
        <div>
          <q-btn
            v-if="$page.props.jetstream.hasTeamFeatures && $page.props.user.current_team"
            :label="$page.props.user.current_team.name"
            class="q-px-sm q-mr-md"
            :icon-right="ionChevronDown"
            flat
            no-caps
          >
            <q-menu>
              <q-list style="min-width: 150px">
                <q-item-label header>Manage Team</q-item-label>
                <q-item
                  clickable
                  @click="router.visit(route('teams.show', $page.props.user.current_team))"
                  v-close-popup
                >
                  <q-item-section>Team Settings</q-item-section>
                </q-item>
                <q-item
                  v-if="$page.props.jetstream.canCreateTeams"
                  clickable
                  @click="router.visit(route('teams.create'))"
                  v-close-popup
                >
                  <q-item-section>Create New Team</q-item-section>
                </q-item>

                <q-separator />

                <q-item-label header>Switch Teams</q-item-label>
                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                  <q-item clickable @click="switchToTeam(team)">
                    <q-item-section>{{ team.name }}</q-item-section>
                    <q-item-section avatar style="padding-left: 0; min-width: 35px">
                      <q-icon
                        v-if="team.id == $page.props.user.current_team_id"
                        :name="ionCheckmarkCircleOutline"
                        color="accent"
                      />
                    </q-item-section>
                  </q-item>
                </template>
              </q-list>
            </q-menu>
          </q-btn>

          <q-btn round flat>
            <user-avatar :user="$page.props.user" />
            <q-menu>
              <q-list style="min-width: 150px">
                <q-item clickable @click="router.visit(route('profile.show'))" v-close-popup>
                  <q-item-section>Profile</q-item-section>
                </q-item>
                <q-item
                  v-if="$page.props.jetstream.hasApiFeatures && user.is_admin"
                  clickable
                  @click="router.visit(route('api-tokens.index'))"
                  v-close-popup
                >
                  <q-item-section>API Tokens</q-item-section>
                </q-item>

                <q-separator />

                <template v-if="user.is_admin">
                  <q-item-label header>Admin Tools</q-item-label>

                  <q-item clickable @click="router.visit(route('admin.users.index'))" v-close-popup>
                    <q-item-section>Users</q-item-section>
                  </q-item>

                  <q-separator />
                </template>

                <q-item clickable @click="logout" v-close-popup>
                  <q-item-section>Log Out</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <site-nav :home="route('dashboard')" :items="siteNavItems" :is-open="siteNavOpen" />

    <q-page-container>
      <q-page class="row column no-wrap q-pa-md page-wrapper">
        <div>
          <slot />
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { Head, router } from '@inertiajs/vue3'
import { ionMenu, ionChevronDown, ionCheckmarkCircleOutline } from '@quasar/extras/ionicons-v6'
import SiteNav from '@/Components/SiteNav/SiteNav.vue'
import PageBack from '@/Components/PageBack.vue'
import UserAvatar from '@/Components/UserAvatar.vue'

export default {
  components: {
    Head,
    SiteNav,
    PageBack,
    UserAvatar,
  },

  props: {
    title: String,
  },

  setup() {
    return {
      router,
      ionMenu,
      ionChevronDown,
      ionCheckmarkCircleOutline,
    }
  },

  data() {
    return {
      siteNavOpen: true,
      siteNavItems: [],
    }
  },

  computed: {
    user() {
      return this.$page.props.user
    },
  },

  created() {
    this.siteNavItems = this.buildSiteNavItems()
  },

  methods: {
    switchToTeam(team) {
      router.put(route('current-team.update'), { team_id: team.id }, { preserveState: false })
    },

    logout() {
      router.post(route('logout'))
    },

    buildSiteNavItems() {
      const items = [
        {
          label: 'Dashboard',
          href: route('dashboard'),
          separator: true,
        },
      ]

      if (!!this.user.game_admin_id) {
        items.push(
          {
            label: 'Admins',
            match: [route('admin.game-admins.index'), route('admin.game-admin-ranks.index')],
            children: [
              {
                label: 'Admin List',
                href: route('admin.game-admins.index'),
              },
              {
                label: 'Ranks',
                href: route('admin.game-admin-ranks.index'),
              },
            ],
          },
          {
            label: 'Players',
            match: [
              route('admin.players.index'),
              route('admin.bans.index'),
              route('admin.notes.index'),
            ],
            children: [
              {
                label: 'Player List',
                href: route('admin.players.index'),
              },
              {
                label: 'Bans',
                href: route('admin.bans.index'),
              },
              {
                label: 'Notes',
                href: route('admin.notes.index'),
              },
            ],
          },
          {
            label: 'Maps',
            href: route('admin.maps.index'),
          },
          {
            label: 'Events',
            href: route('admin.events.index'),
          }
        )
      }

      return items
    },
  },
}
</script>
