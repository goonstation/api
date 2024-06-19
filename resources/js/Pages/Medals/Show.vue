<template>
  <div>
    <medal :medal="medal" hide-earned />

    <q-card flat>
      <q-card-section>
        <div class="text-subtitle2 flex items-center">
          <q-icon :name="ionPeople" size="sm" class="q-mr-md" />
          Players ({{ medal.earned_count }})
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="q-pa-none">
        <medal-players-table v-if="medal.earned_count" :medal-uuid="medal.uuid" fetch-on-load />
        <div v-else class="q-pa-md">
          <q-banner class="bg-grey-10 text-center">
            No one has earned this medal yet.
          </q-banner>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { ionPeople } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import Medal from './Partials/Medal.vue'
import MedalPlayersTable from '@/Components/Tables/MedalPlayersTable.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Medal' }, () => page),

  components: {
    Medal,
    MedalPlayersTable,
  },

  setup() {
    return {
      ionPeople,
    }
  },

  props: {
    medal: Object,
  },
}
</script>
