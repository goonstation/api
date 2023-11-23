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
            <tr v-for="(connections, ip) in ips">
              <td>{{ ip }}</td>
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
    ips() {
      const ips = {}
      for (const connection of this.connections) {
        if (ips[connection.ip]) {
          ips[connection.ip]++
        } else {
          ips[connection.ip] = 1
        }
      }
      return ips
    }
  }
}
</script>
