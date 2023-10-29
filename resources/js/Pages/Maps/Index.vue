<template>
  <Link
    v-for="map in maps"
    class="gh-link-card gh-link-card--bar-left gh-link-card--bar-on q-mb-sm text-weight-medium"
    :href="`/maps/${map.map_id.toLowerCase()}`"
  >
    <img
      :src="`/storage/maps/${map.map_id.toLowerCase()}/thumb.png`"
      :alt="map.name"
      class="q-mr-md"
      width="75"
      height="75"
    />
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

  img {
    position: relative;
    border: 1px solid #444;
    border-radius: 5px;

    &:after {
      content: '';
      display: block;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: $dark;
    }
  }

  span:first-child {
    font-size: 1.1em;
  }
}
</style>

<script>
import dayjs from 'dayjs'
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
