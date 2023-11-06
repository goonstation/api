<template>
  <Link
    v-for="map in maps"
    class="gh-link-card gh-link-card--bar-left gh-link-card--bar-on q-mb-sm text-weight-medium"
    :href="`/maps/${map.map_id.toLowerCase()}`"
  >
    <map-thumbnail :map="map" />
    <div>
      <div class="text-weight-medium q-mb-xs">{{ map.name }}</div>
      <div class="text-sm opacity-60">
        <div>Last updated {{ $formats.fromNow(map.last_built_at) }}</div>
        <div v-if="map.latest_game_round">
          Last played {{ $formats.fromNow(map.latest_game_round.ended_at) }}
        </div>
      </div>
    </div>
  </Link>
</template>

<style lang="scss" scoped>
.gh-link-card {
  display: flex;
  align-items: center;

  span:first-child {
    font-size: 1.1em;
  }
}
</style>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import MapThumbnail from '@/Components/MapThumbnail.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Maps' }, () => page),

  components: {
    MapThumbnail
  },

  props: {
    maps: {
      type: Object,
      required: true,
    },
  },
}
</script>
