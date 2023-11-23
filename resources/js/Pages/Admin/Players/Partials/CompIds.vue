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
            </tr>
          </thead>
          <tbody>
            <tr v-for="(connections, compId) in compIds">
              <td>{{ compId }}</td>
              <td>{{ connections }}</td>
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
      const compIds = {}
      for (const connection of this.connections) {
        if (compIds[connection.comp_id]) {
          compIds[connection.comp_id]++
        } else {
          compIds[connection.comp_id] = 1
        }
      }
      return compIds
    }
  }
}
</script>
