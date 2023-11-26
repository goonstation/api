<template>
  <q-card class="gh-card" flat>
    <q-card-section>
      <div class="flex no-wrap-md gap-xs-sm">
        <div>
          <div class="text-weight-medium">
            {{ ticket.target }}
            <span class="opacity-60 text-sm">ticketed by</span>
            {{ ticket.issuer }}
          </div>

          <div class="q-my-md text-lg">"{{ ticket.reason }}"</div>
        </div>

        <q-space />

        <div class="order-xs-first order-md-last">
          <link-game-round :round-id="ticket.round_id" />
        </div>
      </div>

      <div class="text-weight-medium">
        <span class="opacity-60 text-sm">Ticketed on</span>
        {{ dayjs(ticket.created_at).format('YYYY-MM-DD [at] h:mma') }}
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import dayjs from 'dayjs'
import AppLayout from '@/Layouts/AppLayout.vue'
import LinkGameRound from '@/Components/LinkGameRound.vue'

export default {
  layout: (h, page) =>
    h(
      AppLayout,
      {
        title: `Ticket #${page.props.ticket.id}`,
      },
      () => page
    ),

  components: {
    LinkGameRound,
  },

  setup() {
    return {
      dayjs,
    }
  },

  props: {
    ticket: Object,
  },
}
</script>
