<template>
  <q-card class="gh-card gh-card--small" flat>
    <div class="gh-card__header">
      <q-icon :name="ionLayers" size="22px" />
      <span>Event Explorer</span>
    </div>
    <q-card-section class="row no-wrap q-px-none q-pb-none" style="height: 500px">
      <q-tabs v-model="tab" vertical active-color="primary" indicator-color="primary">
        <q-tab
          v-for="heading in headings"
          content-class="q-px-md col-auto"
          style="justify-content: initial; text-align: left"
          :name="heading.name"
          :label="heading.label"
        />
      </q-tabs>
      <q-separator vertical style="margin-left: -1px;" />
      <div class="col scroll q-mr-sm q-mb-sm">
        <q-tab-panels
          v-model="tab"
          vertical
          animated
          transition-prev="jump-up"
          transition-next="jump-up"
        >
          <q-tab-panel v-for="heading in headings" :key="heading" :name="heading.name" class="q-pa-none">
            <event-table
              v-if="events[heading.name].length"
              :type="heading.name"
              :events="events[heading.name]"
            />
            <div v-else class="q-pa-md"> No data found </div>
          </q-tab-panel>
        </q-tab-panels>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import { provide } from 'vue'
import { ionLayers } from '@quasar/extras/ionicons-v6'
import EventTable from './EventTables/EventTable.vue'

export default {
  setup(props) {
    provide('all-events', props.events)
    return {
      ionLayers,
    }
  },

  components: {
    EventTable,
  },

  props: {
    events: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },

  data() {
    return {
      tab: 'antags',
      headings: [
        { name: 'ai_laws', label: 'AI Laws' },
        { name: 'antags', label: 'Antagonists' },
        { name: 'bee_count', label: 'Bees' },
        { name: 'deaths', label: 'Deaths' },
        { name: 'fines', label: 'Fines' },
        { name: 'tickets', label: 'Tickets' },
      ],
    }
  },
}
</script>
