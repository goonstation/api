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
export default {
  props: {
    events: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      columns: [
        { name: 'issuer', label: 'Issuer', field: 'issuer', align: 'left' },
        { name: 'target', label: 'Target', field: 'target', align: 'left' },
        { name: 'reason', label: 'Reason', field: 'reason', align: 'left' },
        {
          name: 'amount',
          label: 'Amount',
          field: 'amount',
          align: 'left',
          format: this.$formats.currency,
        },
      ],
    }
  },
}
</script>
