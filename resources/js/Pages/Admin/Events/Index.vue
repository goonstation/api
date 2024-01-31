<template>
  <div class="events">
    <q-card v-if="_connectionError" class="gh-card q-mb-md bg-negative" flat>
      <q-card-section>
        <q-icon :name="ionWarningOutline" size="sm" class="q-mr-sm" />
        <strong>Unable to connect to event queue</strong><br>
        {{ _connectionError }}
      </q-card-section>
    </q-card>

    <q-card class="gh-card q-mb-md" flat>
      <q-card-section>
        <q-icon :name="ionInformationCircleOutline" size="sm" class="q-mr-sm" />
        Event history is limited to the current day.
      </q-card-section>
    </q-card>

    <div class="flex justify-between items-center">
      <div class="q-ml-sm">
        Showing {{ _events.length }} events
      </div>
      <q-btn flat label="Refresh" class="bg-dark q-mr-md" @click="refresh" />
    </div>

    <q-markup-table flat>
      <thead>
        <tr>
          <th class="text-left">Type</th>
          <th class="text-left">Round</th>
          <th class="text-left">Created</th>
          <th class="text-left">Data</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="loading">
          <td colspan="100%" class="text-center">
            <q-spinner size="2em" />
          </td>
        </tr>
        <Event v-for="event in _events" :event="event" />
      </tbody>
    </q-markup-table>
  </div>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Event from './Partials/Event.vue'
import { ionInformationCircleOutline, ionWarningOutline } from '@quasar/extras/ionicons-v6'

export default {
  components: {
    Event,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Events' }, () => page),

  props: {
    events: Array,
    connectionError: null,
  },

  setup() {
    return {
      ionInformationCircleOutline,
      ionWarningOutline,
    }
  },

  data() {
    return {
      _events: [],
      _connectionError: null,
      loading: false,
    }
  },

  created() {
    this._events = this.events
    this._connectionError = this.connectionError
  },

  methods: {
    async refresh() {
      if (this.loading) return
      this._connectionError = null
      this.loading = true
      const response = await axios.get('/admin/events')
      if (response.data.connectionError) {
        this._connectionError = response.data.connectionError
      } else {
        this._events = response.data.events
      }
      this.loading = false
    },
  },
}
</script>
