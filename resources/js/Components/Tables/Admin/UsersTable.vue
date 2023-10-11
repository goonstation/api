<template>
  <base-table
    v-bind="$attrs"
    :routes="routes"
    :columns="columns"
    :pagination="{ rowsPerPage: 30 }"
    dense
    flat
  >
    <template v-slot:cell-content-profile_photo_url="{ props }">
      <img :src="props.props.row.profile_photo_url" alt="" class="photo" />
    </template>
    <template v-slot:cell-content-all_teams="{ props }">
      <q-chip v-for="team in props.props.row.all_teams" color="grey-9" class="text-sm">
        {{ team.name }}
      </q-chip>
    </template>
  </base-table>
</template>

<style lang="scss" scoped>
.photo {
  border-radius: 50%;
  width: 40px;
  height: 40px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  object-fit: cover;
}
</style>

<script>
import BaseTable from '../BaseTable.vue'

export default {
  components: { BaseTable },
  data() {
    return {
      routes: {
        fetch: '/admin/users',
        edit: '/admin/users/_id'
      },
      columns: [
        {
          name: 'id',
          label: 'ID',
          field: 'id',
          sortable: true,
          filterable: false,
          format: this.$formats.number,
        },
        { name: 'profile_photo_url', label: 'Avatar', field: 'profile_photo_url' },
        { name: 'name', label: 'Name', field: 'name', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'all_teams', label: 'Teams', field: 'all_teams', filterable: false },
        {
          name: 'created_at',
          label: 'Created',
          field: 'created_at',
          sortable: true,
          format: this.$formats.date,
        },
        {
          name: 'updated_at',
          label: 'Updated',
          field: 'updated_at',
          sortable: true,
          format: this.$formats.date,
        },
      ],
    }
  },
}
</script>
