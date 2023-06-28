<template>
  <Head :title="title" />

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
import { Head } from '@inertiajs/vue3'

export default {
  components: {
    Head
  },

  props: {
    title: String,
  },
}
</script>
