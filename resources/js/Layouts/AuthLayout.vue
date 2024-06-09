<template>
  <app-head :title="title" />

  <div class="page-wrapper flex items-center justify-center q-pa-md">
    <slot />
  </div>
</template>

<style lang="scss" scoped>
.page-wrapper {
  min-height: 100vh;
}
</style>

<script>
import AppHead from '@/Components/AppHead.vue'

export default {
  components: {
    AppHead
  },

  props: {
    title: String,
  },
}
</script>
