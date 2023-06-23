<template>
  <div class="q-mx-md q-my-xl bee-count">
    <div class="count">{{ beeCount }}</div>
    <div class="subtitle">Bees Born</div>
  </div>
</template>

<style lang="scss" scoped>
.bee-count {
  text-align: center;

  .count {
    font-size: 5rem;
    font-weight: 700;
    line-height: 1.4;
  }

  .subtitle {
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.8;
  }
}
</style>

<script>
export default {
  props: {
    events: {
      type: Array,
      required: true,
    },
  },

  computed: {
    beeCount() {
      if (!this.events.length) return 0
      return this.events[0].data
    }
  }
}
</script>
