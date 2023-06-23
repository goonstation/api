<template>
  <Link
    v-for="map in maps"
    class="gh-link-card gh-link-card--bar-left gh-link-card--bar-on q-mb-sm text-weight-medium"
    :href="`/maps/${map.uri}`"
  >
    <img :src="`/storage/maps/${map.uri}/thumb.png`" class="q-mr-md" width="75" height="75" />
    <div>
      <div class="text-weight-medium q-mb-xs">{{ map.name }}</div>
      <div class="text-sm opacity-60">Last updated {{ $formats.fromNow(map.last_built_at) }}</div>
    </div>
  </Link>
</template>

<style lang="scss" scoped>
.gh-link-card {
  display: flex;
  align-items: center;

  img {
    border: 1px solid #444;
    border-radius: 5px;
  }

  span:first-child {
    font-size: 1.1em;
  }
}
</style>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Maps' }, () => page),

  props: {
    maps: {
      type: Object,
      required: true,
    },
  },
}
</script>
