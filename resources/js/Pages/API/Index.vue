<script setup>
import ApiTokenManager from './Partials/ApiTokenManager.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
  tokens: Array,
  availablePermissions: Array,
  defaultPermissions: Array,
})
</script>

<template>
  <AdminLayout title="API Tokens">
    <ApiTokenManager
      :tokens="tokens"
      :available-permissions="availablePermissions"
      :default-permissions="defaultPermissions"
    />
  </AdminLayout>
</template>
