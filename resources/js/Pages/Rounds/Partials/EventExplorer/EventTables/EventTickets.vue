<template>
  <q-table
    :rows="events"
    :columns="columns"
    :rows-per-page-options="[0]"
    row-key="name"
    wrap-cells
  />
</template>

<script>
const columns = [
  { name: 'issuer', label: 'Issuer', field: 'issuer', align: 'left' },
  { name: 'target', label: 'Target', field: 'target', align: 'left' },
  { name: 'reason', label: 'Reason', field: 'reason', align: 'left' },
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
