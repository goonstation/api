<template>
  <img
    :src="`/storage/medals/${medal.uuid}.png`"
    :alt="medal.title"
    :width="size"
    :height="size"
  />
</template>

<style lang="scss" scoped>
img {
  display: block;
  position: relative;
  // border: 1px solid #444;
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
    medal: Object,
    size: {
      type: String,
      required: false,
      default: '64'
    }
  },
}
</script>
