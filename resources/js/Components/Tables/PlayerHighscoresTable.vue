<template>
  <base-table
    v-bind="$attrs"
    :routes="routes"
    :columns="columns"
    :pagination="{ rowsPerPage: 10, sortBy: 'value' }"
    flat
    no-timestamp-toggle
  >
    <template v-slot:body="props">
      <q-tr
        :props="props"
        :style="props.rowIndex % 2 === 0 ? '' : 'background-color: rgba(255, 255, 255, 0.02);'"
        :class="`highscore-row position-${props.row.position}`"
      >
        <q-td v-for="col in props.cols" :key="col.name" :props="props">
          <template v-if="col.name === 'position'">
            <span class="position-num">{{ col.value }}</span>
          </template>
          <template v-else-if="col.name === 'ckey'">
            <q-icon v-if="props.row.position === 1" :name="ionTrophy" color="primary" class="q-mr-sm" />
            {{ col.value }}
          </template>
          <template v-else-if="col.name === 'value'">
            <span class="">{{ col.value }}</span>
          </template>
          <template v-else>
            {{ col.value }}
          </template>
        </q-td>
      </q-tr>
    </template>
  </base-table>
</template>

<style lang="scss" scoped>
.position-num {
  display: inline-block;
}

.position-1, .position-2, .position-3 {
  .position-num {
    padding: 2px 4px;
    border-radius: 4px;
  }
}

.position-1 {
  td {
    font-size: 1.3em;
    font-weight: 600;
  }

  .position-num {
    background: $primary !important;
    color: black;
  }
}
.position-2 {
  td {
    font-size: 1.2em;
    font-weight: 500;
  }

  .position-num {
    background: #c0c0c0 !important;
    color: black;
  }
}
.position-3 {
  td {
    font-size: 1.1em;
    font-weight: 500;
  }

  .position-num {
    background: #cd7f32 !important;
    color: black;
  }
}
</style>

<script>
import { ionTrophy } from '@quasar/extras/ionicons-v6'
import BaseTable from './BaseTable.vue'

export default {
  setup() {
    return {
      ionTrophy
    }
  },
  components: { BaseTable },
  data() {
    return {
      routes: { fetch: '/players/highscores' },
      columns: [
        {
          name: 'position',
          label: '',
          field: (row) => `#${row.position}`,
          filterable: false,
          headerClasses: 'q-table--col-auto-width',
        },
        {
          name: 'ckey',
          label: 'Player',
          field: (row) => {
            return row.key || row.ckey
          },
          sortable: true,
          align: 'left',
          style: 'width: 1px; padding-right: 3rem;'
        },
        {
          name: 'value',
          label: 'Score',
          field: 'value',
          sortable: true,
          format: this.$formats.number,
          filter: { type: 'range' },
          align: 'left',
        },
      ],
    }
  },
}
</script>
