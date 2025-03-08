<template>
  <div>Auditable is a {{ type }}</div>
</template>

<script>
export default {
  props: {
    filter: null,
    filterOption: null,
  },

  data() {
    return {
      type: null,
    }
  },

  watch: {
    filter: {
      immediate: true,
      handler(val) {
        if (this.filterOption) {
          this.type = this.filterOption.label
        } else {
          this.type = val.replace(/([A-Z])/g, ' $1').trim()
        }
      },
    },
  },
}
</script>
