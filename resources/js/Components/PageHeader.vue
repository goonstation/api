<template>
  <q-header>
    <span>
      <q-btn
        dense
        flat
        round
        :icon="ionMenu"
        @click="$emit('onToggleLeftDrawer')"
        class="q-mr-md q-mr-md-xl"
      />
      <page-back class="q-mr-sm" />
      {{ niceTitle }}
    </span>
  </q-header>
</template>

<style lang="scss" scoped>
header {
  z-index: 1;
  overflow: hidden;
  background: black;

  @keyframes space-move {
    0% {
      translate: 0 0;
    }
    25% {
      translate: -500px 0;
    }
    50% {
      translate: -500px -500px;
    }
    75% {
      translate: -1000px -1000px;
    }
    100% {
      translate: -500px -500px;
    }
  }

  &:before {
    content: '';
    position: absolute;
    z-index: -2;
    top: 0;
    left: 0;
    width: 3586px;
    height: 1840px;
    background: url('@img/starfield.png');
    filter: brightness(0.6);
    animation-name: space-move;
    animation-duration: 140s;
    animation-iteration-count: infinite;
    animation-direction: alternate;
    animation-timing-function: linear;
  }

  &:after {
    content: '';
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(-35deg, rgb(128 74 0 / 25%) -80%, transparent);
  }

  span {
    display: inline-flex;
    align-items: center;
    height: 100%;
    padding: 30px 56px 50px 10px;
    background: rgba(0, 0, 0, 0.3);
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
  }

  .breadcrumbs {
    position: absolute;
    top: 10px;
    left: 92px;
  }
}
</style>

<script>
import { ionMenu, ionChevronBackCircle } from '@quasar/extras/ionicons-v6'
import PageBack from '@/Components/PageBack.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'

export default {
  components: {
    PageBack,
    Breadcrumbs,
  },

  props: {
    title: {
      type: String,
      required: false,
      default: '',
    },
  },

  setup() {
    return {
      ionMenu,
      ionChevronBackCircle,
    }
  },

  computed: {
    niceTitle() {
      if (this.title) {
        return this.title.replace(/#(\d+)?/g, (match, contents) => {
          return '#' + this.$formats.number(contents)
        })
      }
      return ''
    },
  }
}
</script>
