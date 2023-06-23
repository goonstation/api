<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

defineProps({
  title: String,
})

const leftDrawerOpen = ref(true)
const menuList = [
  {
    icon: 'bug_report',
    label: 'Test',
    href: '/test',
    separator: false,
  },
  {
    icon: 'groups',
    label: 'Players',
    href: '/players',
    separator: false,
  },
  {
    icon: 'warning',
    label: 'Bans',
    href: '/bans',
    separator: false,
  },
]

const switchToTeam = (team) => {
  router.put(
    route('current-team.update'),
    {
      team_id: team.id,
    },
    {
      preserveState: false,
    }
  )
}

const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <Head :title="title" />
  <q-layout view="lhh LpR lff">
    <q-header class="bg-transparent">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
        <q-toolbar-title>
          <slot v-if="$slots.header" name="header" />
          <template v-else>{{ title }}</template>
        </q-toolbar-title>
        <div>
          <q-btn
            v-if="$page.props.jetstream.hasTeamFeatures && $page.props.user.current_team"
            :label="$page.props.user.current_team.name"
            class="q-px-sm q-mr-md"
            icon-right="unfold_more"
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
                        name="check_circle"
                        color="accent"
                      />
                    </q-item-section>
                  </q-item>
                </template>
              </q-list>
            </q-menu>
          </q-btn>

          <q-btn round>
            <q-avatar>
              <img :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
            </q-avatar>
            <q-menu>
              <q-list style="min-width: 150px">
                <q-item-label header>Manage Account</q-item-label>
                <q-item clickable @click="router.visit(route('profile.show'))" v-close-popup>
                  <q-item-section>Profile</q-item-section>
                </q-item>
                <q-item
                  v-if="$page.props.jetstream.hasApiFeatures"
                  clickable
                  @click="router.visit(route('api-tokens.index'))"
                  v-close-popup
                >
                  <q-item-section>API Tokens</q-item-section>
                </q-item>

                <q-separator />

                <q-item clickable @click="logout" v-close-popup>
                  <q-item-section>Log Out</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      show-if-above
      v-model="leftDrawerOpen"
      side="left"
      :width="250"
      :breakpoint="600"
    >
      <q-scroll-area class="fit">
        <q-list>
          <q-item class="q-mb-md">
            <q-avatar class="q-mr-md">
              <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
            </q-avatar>
            <q-item-section class="text-h6"> Goonhub </q-item-section>
          </q-item>
          <template v-for="(menuItem, index) in menuList" :key="index">
            <q-item
              clickable
              @click="router.visit(menuItem.href)"
              :active="$page.url.startsWith(menuItem.href)"
              v-ripple
            >
              <q-item-section avatar>
                <q-icon :name="menuItem.icon" />
              </q-item-section>
              <q-item-section>
                {{ menuItem.label }}
              </q-item-section>
            </q-item>
            <q-separator :key="'sep' + index" v-if="menuItem.separator" />
          </template>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <slot />
    </q-page-container>

    <q-footer class="bg-transparent">
      <q-toolbar>
        <q-toolbar-title>
          <q-avatar>
            <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
          </q-avatar>
          Footer
        </q-toolbar-title>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>
