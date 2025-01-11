<template>
  <div class="builds-layout flex flex-grow no-wrap bg-dark rounded-borders">
    <current-builds class="q-pb-lg q-pb-md-md" />

    <div class="builds-wrapper flex column flex-grow">
      <slot />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.builds-layout {
  flex-direction: column;
}

.builds {
  flex-shrink: 0;
  border-width: 0;
  border-style: solid;
  border-color: var(--q-dark-page);
  border-bottom-width: 1px;
}

@media (min-width: $breakpoint-md-min) {
  .builds-layout {
    flex-direction: row;
  }

  .builds {
    width: 250px;
    border-right-width: 1px;
    border-bottom-width: 0;
  }

  .builds-wrapper {
    width: 0;
  }
}
</style>

<script>
import CurrentBuilds from './Partials/CurrentBuilds.vue'

export default {
  components: {
    CurrentBuilds,
  },

  beforeUnmount() {
    Echo.leaveChannel('game-builds')
  },
}
</script>
