<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DeleteTeamForm from './Partials/DeleteTeamForm.vue'
import TeamMemberManager from './Partials/TeamMemberManager.vue'
import UpdateTeamNameForm from './Partials/UpdateTeamNameForm.vue'

defineProps({
  team: Object,
  availableRoles: Array,
  permissions: Object,
})
</script>

<template>
  <AdminLayout title="Team Settings">
    <UpdateTeamNameForm :team="team" :permissions="permissions" />

    <TeamMemberManager
      :team="team"
      :available-roles="availableRoles"
      :user-permissions="permissions"
    />

    <template v-if="permissions.canDeleteTeam && !team.personal_team">
      <DeleteTeamForm class="q-mt-lg" :team="team" />
    </template>
  </AdminLayout>
</template>
