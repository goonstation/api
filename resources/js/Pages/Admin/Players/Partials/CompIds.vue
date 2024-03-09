<template>
  <a v-bind="$attrs" href="" @click.prevent="dialog = true"> See All </a>
  <q-dialog v-model="dialog">
    <q-card flat bordered>
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Computer Ids</div>
        <q-space />
        <q-btn :icon="ionClose" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <q-markup-table flat bordered>
          <thead>
            <tr>
              <th>Computer ID</th>
              <th>Connections</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="group in compIds">
              <td>{{ group.comp_id }}</td>
              <td>{{ group.connections }}</td>
              <td>
                <q-chip
                  v-if="getCursedCompId(group.comp_id)"
                  color="negative"
                  text-color="dark"
                  class="text-weight-bold"
                  square
                >
                  Cursed
                  <q-tooltip>
                    {{ getCursedCompId(group.comp_id).reason }}
                  </q-tooltip>
                </q-chip>
              </td>
            </tr>
          </tbody>
        </q-markup-table>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script>
import { ionClose } from '@quasar/extras/ionicons-v6'

export default {
  props: {
    connections: Object,
    cursedCompIds: Object,
  },

  setup() {
    return {
      ionClose,
    }
  },

  data() {
    return {
      dialog: false,
    }
  },

  computed: {
    compIds() {
      const groups = []
      for (const connection of this.connections) {
        const groupIdx = groups.findIndex((group) => group.comp_id === connection.comp_id)
        if (groupIdx !== -1) {
          groups[groupIdx].connections++
        } else {
          groups.push({
            comp_id: connection.comp_id,
            connections: 1,
          })
        }
      }
      return groups.sort((a, b) => b.connections - a.connections)
    },
  },

  methods: {
    getCursedCompId(compId) {
      return this.cursedCompIds.find((cursedCompId) => {
        return cursedCompId.comp_id === compId
      })
    },
  },
}
</script>
