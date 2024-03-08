<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
</script>

<template>
  <AdminLayout title="Dashboard">
    <q-card class="gh-card" flat style="width: 100%; max-width: 500px">
      <div class="gh-card__header q-pa-md bordered">
        <span>Hello</span>
      </div>
      <q-card-section>
        This is the admin dashboard. It is in active development so large parts are missing or
        incomplete. Please ping Wire in Discord if something is obviously broken, or you have a
        feature request.
      </q-card-section>
    </q-card>
  </AdminLayout>
</template>
