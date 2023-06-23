<template>
  <q-table :rows="events" :columns="columns" :rows-per-page-options="[0]" row-key="name" wrap-cells>
    <template v-slot:body-cell-gibbed="props">
      <q-td :props="props">
        <q-chip v-if="props.value" class="text-sm" color="red" text-color="white" square
          >Gibbed</q-chip
        >
      </q-td>
    </template>
  </q-table>
</template>

<script>
const columns = [
  { name: 'mob_name', label: 'Name', field: 'mob_name', align: 'left' },
  { name: 'mob_job', label: 'Job', field: 'mob_job', align: 'left' },
  {
    name: 'bruteloss',
    label: 'Brute Dmg',
    field: 'bruteloss',
    classes: 'bruteloss',
    align: 'left',
  },
  { name: 'fireloss', label: 'Fire Dmg', field: 'fireloss', classes: 'fireloss', align: 'left' },
  { name: 'toxloss', label: 'Toxin Dmg', field: 'toxloss', classes: 'toxloss', align: 'left' },
  { name: 'oxyloss', label: 'Oxygen Dmg', field: 'oxyloss', classes: 'oxyloss', align: 'left' },
  { name: 'gibbed', label: 'Gibbed', field: 'gibbed', align: 'left' },
]

export default {
  props: {
    events: {
      type: Array,
      required: true,
    },
  },

  setup() {
    return {
      columns,
    }
  },
}
</script>
