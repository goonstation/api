<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import GameAuthCallback from '@/Components/GameAuthCallback.vue'

const props = defineProps(['authFromGame', 'authFromGameServer'])
</script>

<template>
  <AdminLayout title="Dashboard">
    <q-card class="gh-card q-mb-md" flat>
      <div class="gh-card__header q-pa-md bordered">
        <span>Hello</span>
      </div>
      <q-card-section>
        This is the admin dashboard. It is in active development so large parts are missing or
        incomplete. Please make a Github issue
        <a href="https://github.com/goonstation/api/issues" target="_blank">here</a> if you have a
        bug report or feature request.
      </q-card-section>
    </q-card>

    <iframe
      src="/pulse"
      class="full-width flex-grow no-border rounded-borders"
      style="min-height: 500px"
    ></iframe>

    <game-auth-callback :server-id="props.authFromGame" :server="props.authFromGameServer" />
  </AdminLayout>
</template>
