<template>
  <a v-bind="$attrs" href="" @click.prevent="dialog = true"> See All </a>
  <q-dialog v-model="dialog">
    <q-card flat bordered>
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">IPs</div>
        <q-space />
        <q-btn :icon="ionClose" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <q-markup-table flat bordered>
          <thead>
            <tr>
              <th>IP</th>
              <th>Connections</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="group in ips">
              <td>{{ group.ip }}</td>
              <td>{{ group.connections }}</td>
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
    ips() {
      const groups = []
      for (const connection of this.connections) {
        const groupIdx = groups.findIndex((group) => group.ip === connection.ip)
        if (groupIdx !== -1) {
          groups[groupIdx].connections++
        } else {
          groups.push({
            ip: connection.ip,
            connections: 1
          })
        }
      }
      return groups.sort((a, b) => b.connections - a.connections)
    }
  }
}
</script>
