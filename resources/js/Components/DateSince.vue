<template>
  <span class="date-since"
    >{{ dateDiff
    }}<q-tooltip :offset="[14, 4]" :transition-duration="100">
      <span style="font-size: 11px">{{ humanDate }}</span>
    </q-tooltip>
  </span>
</template>

<style lang="scss" scoped>
.date-since {
  text-decoration: underline;
  text-decoration-style: dashed;
  text-underline-offset: 2px;
}
</style>

<script>
import dayjs from 'dayjs'

export default {
  props: {
    date: {
      type: String,
      required: true,
    },
  },

  computed: {
    dayjsDate() {
      return dayjs.utc(this.date).local()
    },

    dateDiff() {
      return this.dayjsDate.fromNow()
    },

    humanDate() {
      return this.dayjsDate.format('MMM D, YYYY H:mm z')
    },
  },
}
</script>
