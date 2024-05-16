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

      <vote-control
        class="q-mt-md"
        v-model:votes="ticket.votes"
        v-model:userVotes="ticket.user_votes"
        voteable-type="ticket"
        :voteable-id="ticket.id"
      />
    </q-card-section>
  </q-card>
</template>

<script>
import dayjs from 'dayjs'
import AppLayout from '@/Layouts/AppLayout.vue'
import LinkGameRound from '@/Components/LinkGameRound.vue'
import VoteControl from '@/Components/VoteControl.vue'

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
    VoteControl,
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
