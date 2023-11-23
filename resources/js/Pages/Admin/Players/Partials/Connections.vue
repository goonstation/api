<template>
  <div class="row">
    <div class="col-xs-12 col-md-8">
      <player-connections-over-time
        :data="connections"
        @selected-connections="selectedConnections = $event"
      />
    </div>
    <div class="flex col-xs-12 col-md-4">
      <q-table
        :rows="selectedConnections"
        :columns="columns"
        :rows-per-page-options="[10]"
        :class="{ 'no-data': !selectedConnections.length }"
        class="selected-connections q-mb-md q-mr-md q-ml-md q-md-ml-none"
        title="Selected Connections"
        title-class="text-body1"
        flat
        dense
        bordered
      >
        <template #no-data="{ icon }">
          <div class="flex no-wrap">
            <q-icon :name="icon" size="sm" class="q-mr-sm" />
            <div class="text-body2">
              Click and drag a selection in the chart to display a list of connections in that time
              period.
            </div>
          </div>
        </template>
        <template v-slot:body-cell-round_id="props">
          <q-td :props="props">
            <Link v-if="props.row.round_id" :href="route('rounds.show', props.row.round_id)">
              #{{ props.row.round_id }}
            </Link>
            <template v-else> N/A </template>
          </q-td>
        </template>
      </q-table>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.selected-connections {
  width: 100%;

  &.no-data:deep(.q-table__middle) {
    flex: 0 1 auto;
  }
}
</style>

<script>
import PlayerConnectionsOverTime from '@/Components/Charts/PlayerConnectionsOverTime.vue'

export default {
  components: {
    PlayerConnectionsOverTime,
  },

  props: {
    connections: Object,
  },

  data() {
    return {
      selectedConnections: [],
      columns: [
        {
          name: 'created_at',
          field: 'created_at',
          label: 'Connected At',
          format: this.$formats.dateWithTime,
        },
        { name: 'round_id', field: 'round_id', label: 'Round', sortable: true },
      ],
    }
  },
}
</script>
