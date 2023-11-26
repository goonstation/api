<template>
  <q-table :rows="rows" :columns="columns" :rows-per-page-options="[0]" row-key="id">
    <template v-slot:header="props">
      <q-tr :props="props">
        <q-th v-for="col in props.cols" :key="col.name" :props="props">
          {{ col.label }}
        </q-th>
        <q-th auto-width />
      </q-tr>
    </template>

    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td v-for="col in props.cols" :key="col.name" :props="props">
          <template v-if="col.name === 'success'">
            <q-chip
              v-if="col.value"
              class="text-sm text-weight-bold"
              color="positive"
              text-color="dark"
              square
            >
              Succeeded
            </q-chip>
            <q-chip v-else class="text-sm text-weight-bold" color="red" text-color="dark" square>
              Failed
            </q-chip>
          </template>

          <template v-else>{{ col.value }}</template>
        </q-td>
        <q-td auto-width>
          <q-btn
            size="sm"
            color="grey-8"
            dense
            no-wrap
            @click="props.expand = !props.expand"
            label="Objectives"
            :icon-right="props.expand ? ionChevronUp : ionChevronDown"
            padding="xs sm"
          />
        </q-td>
      </q-tr>
      <q-tr v-show="props.expand" :props="props">
        <q-td colspan="100%">
          <div class="text-left">
            <q-list dense>
              <q-item-label header class="q-py-sm">Objectives</q-item-label>
              <q-item v-if="props.row.objectives.length" v-for="objective in props.row.objectives">
                <q-item-section
                  style="white-space: pre-wrap"
                  v-dompurify-html="objective.objective"
                ></q-item-section>
                <q-item-section avatar>
                  <q-chip
                    v-if="objective.success"
                    class="text-sm text-weight-bold"
                    color="positive"
                    text-color="dark"
                    square
                  >
                    Succeeded
                  </q-chip>
                  <q-chip
                    v-else
                    class="text-sm text-weight-bold"
                    color="red"
                    text-color="dark"
                    square
                  >
                    Failed
                  </q-chip>
                </q-item-section>
              </q-item>
              <q-item v-else>None</q-item>
            </q-list>

            <q-list dense>
              <q-item-label header class="q-py-sm">Item Purchases</q-item-label>
              <q-item v-if="props.row.item_purchases.length">
                <q-item-section>
                  <div class="flex wrap">
                    <q-chip v-for="itemPurchase in props.row.item_purchases" color="grey-9" square>
                      {{ itemPurchase.item }}
                    </q-chip>
                  </div>
                </q-item-section>
              </q-item>
              <q-item v-else>None</q-item>
            </q-list>
          </div>
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script>
import { inject } from 'vue'
import { startCase, toLower } from 'lodash'
import { ionChevronUp, ionChevronDown } from '@quasar/extras/ionicons-v6'

const columns = [
  { name: 'mob_name', label: 'Name', field: 'mob_name', align: 'left' },
  { name: 'mob_job', label: 'Job', field: 'mob_job', align: 'left' },
  {
    name: 'traitor_type',
    label: 'Type',
    field: 'traitor_type',
    align: 'left',
    format: (val) => {
      if (!val) return 'Unknown'
      return startCase(toLower(val.replace(/_/g, ' ')))
    },
  },
  { name: 'success', label: 'Status', field: 'success', align: 'left' },
]

export default {
  props: {
    events: {
      type: Array,
      required: true,
    },
  },

  setup() {
    const allEvents = inject('all-events')
    return {
      allEvents,
      columns,
      ionChevronUp,
      ionChevronDown,
    }
  },

  computed: {
    rows() {
      return this.events.map((event) => {
        return {
          ...event,
          objectives: this.getObjectives(event.player_id),
          item_purchases: this.getItemPurchases(event.player_id),
        }
      })
    },
  },

  methods: {
    getObjectives(playerId) {
      return this.allEvents.antag_objectives.filter((event) => event.player_id === playerId)
    },

    getItemPurchases(playerId) {
      return this.allEvents.antag_item_purchases.filter((event) => event.player_id === playerId)
    },
  },
}
</script>
