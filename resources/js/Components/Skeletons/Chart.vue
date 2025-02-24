<template>
  <div class="full-height flex-grow flex column">
    <div
      v-for="line of lines"
      :key="line"
      :class="[line < lines ? 'q-my-auto' : 'q-mt-auto']"
      class="flex items-center"
    >
      <q-skeleton type="text" width="15px" height="30px" class="q-mr-sm" />
      <q-skeleton type="rect" height="2px" class="flex-grow" />
    </div>
    <div class="flex justify-around gap-xs-xl q-ml-lg">
      <q-skeleton v-for="line of lines" :key="line" type="rect" width="30px" height="15px" />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    lines: {
      type: Number,
      required: false,
      default: 5,
    },
  },
}
</script>
