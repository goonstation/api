<template>
  <img
    :src="`/storage/maps/${map.map_id.toLowerCase()}/thumb.png`"
    :alt="map.name"
    class="q-mr-md"
    width="75"
    height="75"
  />
</template>

<style lang="scss" scoped>
img {
  display: block;
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
</style>

<script>
export default {
  props: {
    map: Object
  }
}
</script>
