<template>
  <base-table
    v-bind="$attrs"
    :fetch-route="fetchRoute"
    :columns="columns"
    :show-columns="['created_at']"
    :pagination="{ rowsPerPage: 20 }"
    flat
    no-timestamp-toggle
    grid
  >
    <template v-slot:item="props">
      <div class="q-table__grid-item col-xs-12">
        <Link :href="`/events/deaths/${props.row.id}`" class="gh-link-card">
          <div class="row items-center q-col-gutter-md">
            <div class="col">
              <span>{{ props.row.mob_name }}</span>
              <span v-if="props.row.mob_job" class="opacity-60 text-sm q-ml-sm"
                >&nbsp;the {{ props.row.mob_job }}</span
              >
              <div v-if="props.row.last_words" class="text-sm text-italic opacity-60 ellipsis">
                "{{ props.row.last_words }}"
              </div>
            </div>
            <div class="col-xs-12 col-md-auto flex items-center q-ml-auto gap-xs-sm gap-md-md">
              <div class="text-xs opacity-60">Died from</div>
              <q-chip
                v-if="props.row.gibbed"
                class="q-mx-none"
                size="sm"
                color="red"
                square
                outline
              >
                Gibbed
              </q-chip>
              <div class="gh-details-list gh-details-list--small">
                <div>
                  <div class="bruteloss">{{ $formats.number(props.row.bruteloss) }}</div>
                  <div>Brute</div>
                </div>
                <div>
                  <div class="fireloss">{{ $formats.number(props.row.fireloss) }}</div>
                  <div>Fire</div>
                </div>
                <div>
                  <div class="toxloss">{{ $formats.number(props.row.toxloss) }}</div>
                  <div>Toxin</div>
                </div>
                <div>
                  <div class="oxyloss">{{ $formats.number(props.row.oxyloss) }}</div>
                  <div>Oxygen</div>
                </div>
              </div>
            </div>
          </div>
        </Link>
      </div>
    </template>
  </base-table>
</template>

<style lang="scss" scoped>
.gh-link-card {
  padding: 0.6rem 1rem;
}

.gh-details-list {
  line-height: 1.2;
}
</style>

<script>
import { Link } from '@inertiajs/vue3'
import BaseTable from './BaseTable.vue'
import RoundsTableItem from './Partials/RoundsTableItem.vue'

export default {
  components: { Link, BaseTable, RoundsTableItem },
  data() {
    return {
      fetchRoute: '/events/deaths',
      columns: [
        {
          name: 'id',
          label: 'Recent',
          field: 'id',
          sortable: true,
          filterable: false,
          format: this.$formats.number,
        },
        {
          name: 'mob_name',
          label: 'Name',
          field: 'mob_name',
          sortable: true,
        },
        { name: 'mob_job', label: 'Job', field: 'mob_job', sortable: true },
        // {
        //   name: 'coords',
        //   label: 'Coords',
        //   field: (row) => {
        //     return `${row.x},${row.y},${row.z}`
        //   },
        // },
        {
          name: 'bruteloss',
          label: 'Brute Damage',
          field: 'bruteloss',
          sortable: true,
          filter: { type: 'range' },
        },
        {
          name: 'fireloss',
          label: 'Fire Damage',
          field: 'fireloss',
          sortable: true,
          filter: { type: 'range' },
        },
        {
          name: 'toxloss',
          label: 'Toxin Damage',
          field: 'toxloss',
          sortable: true,
          filter: { type: 'range' },
        },
        {
          name: 'oxyloss',
          label: 'Oxygen Damage',
          field: 'oxyloss',
          sortable: true,
          filter: { type: 'range' },
        },
        {
          name: 'gibbed',
          label: 'Gibbed',
          field: 'gibbed',
          sortable: true,
          align: 'center',
          filter: { type: 'boolean' },
        },
        {
          name: 'last_words',
          label: 'Last Words',
          field: 'last_words',
          sortable: true,
        },
        // {
        //   name: 'created_at',
        //   label: 'Died At',
        //   field: 'created_at',
        //   sortable: true,
        //   format: this.$formats.date,
        //   filter: { type: 'daterange' },
        // },
      ],
    }
  },
}
</script>
