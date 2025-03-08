<template>
  <div>
    <health-list :health="health" class="q-my-md" />

    <q-card class="gh-card q-mb-md" flat>
      <div class="gh-card__header q-pa-md bordered">
        <span>Hello</span>
      </div>
      <q-card-section>
        This is the admin dashboard. It is in active development so large parts are missing or
        incomplete. Please make a Github issue
        <a href="https://github.com/goonstation/goonhub/issues" target="_blank">here</a> if you have
        a bug report or feature request.
      </q-card-section>
    </q-card>

    <div class="row q-col-gutter-md">
      <div class="col col-md-6 col-lg-4">
        <server-orchestration />
      </div>
    </div>

    <game-auth-callback :server="authFromGame" />
  </div>
</template>

<script>
import GameAuthCallback from '@/Components/GameAuthCallback.vue'
import ServerOrchestration from '@/Components/Orchestration/Manager.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import HealthList from './Partials/HealthList.vue'

export default {
  layout: (h, page) => h(AdminLayout, { title: 'Dashboard' }, () => page),

  components: {
    GameAuthCallback,
    ServerOrchestration,
    HealthList,
  },

  props: {
    authFromGame: [Object, null],
    health: [Object, null],
  },
}
</script>
