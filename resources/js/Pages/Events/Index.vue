<template>
  <div class="row q-col-gutter-md">
    <!-- <div class="col-xs-12 col-md-auto" :style="verticalCategories && 'max-width: 150px'">
      <div flat class="flex items-center gap-xs-sm">
        <q-icon :name="verticalCategories ? ionReturnUpBack : ionArrowUp" size="3rem" />
        <div class="text-body2">Explore events in detail here.</div>
      </div>
    </div> -->

    <div class="col">
      <div class="row q-col-gutter-md">
        <div v-for="countCard in countCards" class="col-xs-12 col-md-6 col-lg-3">
          <event-count :label="countCard.label" :count="countCard.counts" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ionReturnUpBack, ionArrowUp } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import EventsLayout from '@/Layouts/EventsLayout.vue'
import EventCount from './Partials/EventCount.vue'

export default {
  components: {
    EventCount,
  },

  layout: (h, page) => {
    return h(AppLayout, { title: 'Events' }, () => h(EventsLayout, () => page))
  },

  setup() {
    return {
      ionReturnUpBack,
      ionArrowUp,
    }
  },

  props: {
    counts: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },

  computed: {
    countCards() {
      return [
        { label: 'Deaths', counts: this.counts.deaths },
        { label: 'Tickets', counts: this.counts.tickets },
        { label: 'Fines', counts: this.counts.fines },
        { label: 'Bees', counts: this.counts.bees },
      ]
    },

    verticalCategories() {
      return !this.$q.screen.lt.md
    },
  },
}
</script>
