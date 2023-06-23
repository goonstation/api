<template>
  <q-table :rows="events" :columns="columns" :rows-per-page-options="[0]" row-key="name" wrap-cells />
</template>

<script>
const columns = [
  { name: 'ai_name', label: 'AI', field: 'ai_name', align: 'left' },
  { name: 'law_number', label: 'Law Number', field: 'law_number', align: 'left' },
  { name: 'law_text', label: 'Law', field: 'law_text', align: 'left' },
  { name: 'uploader_name', label: 'Uploader', field: 'uploader_name', align: 'left' },
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
