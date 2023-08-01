<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { ionMenu, ionChevronDown, ionCheckmarkCircleOutline } from '@quasar/extras/ionicons-v6'
import PageBack from '@/Components/PageBack.vue'

defineProps({
  title: String,
})

const leftDrawerOpen = ref(true)
const menuList = [
  {
    label: 'Test',
    href: '/test',
    separator: false,
  },
  {
    label: 'Admin Ranks',
    href: '/admin/game-admin-ranks',
    separator: false,
  },
  {
    label: 'Admins',
    href: '/admin/game-admins',
    separator: true,
  },
  {
    label: 'Players',
    href: '/admin/players',
    separator: false,
  },
  {
    label: 'Bans',
    href: '/admin/bans',
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
  <q-layout view="lhh LpR fff">
    <q-header class="bg-transparent">
      <q-toolbar class="q-pt-md">
        <q-btn dense flat round :icon="ionMenu" @click="toggleLeftDrawer" />
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
            <q-avatar>
              <img :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
            </q-avatar>
            <q-menu>
              <q-list style="min-width: 150px">
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

                <template v-if="$page.props.user.is_admin">
                  <q-item-label header>Admin Tools</q-item-label>

                  <q-item
                    clickable
                    @click="router.visit(route('admin.users.index'))"
                    v-close-popup
                  >
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

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left" :width="200" :breakpoint="600">
      <q-scroll-area class="fit">
        <q-list>
          <a
            class="block q-mt-lg q-mb-md logo"
            @click.prevent="router.visit('/dashboard')"
            href="/"
          >
            <img src="@img/logo.png" alt="Logo" class="block q-mx-auto" width="100" height="97" />
          </a>
          <template v-for="(menuItem, index) in menuList" :key="index">
            <q-item
              clickable
              @click="router.visit(menuItem.href)"
              :active="$page.url.startsWith(menuItem.href)"
              v-ripple
            >
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
      <q-page class="row column no-wrap q-pa-md page-wrapper">
        <div>
          <slot />
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>
