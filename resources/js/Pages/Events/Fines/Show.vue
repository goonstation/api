<template>
  <q-card class="gh-card" flat>
    <q-card-section>
      <div class="flex no-wrap-md gap-xs-sm">
        <div>
          <div class="text-weight-medium">
            {{ fine.target }}
            <span class="opacity-60 text-sm">fined by</span>
            {{ fine.issuer }}
            <span class="opacity-60 text-sm">for</span>
            {{ $formats.currency(fine.amount) }}
          </div>

          <div class="q-my-md text-lg">
            "{{ fine.reason }}"
          </div>
        </div>

        <q-space />

        <div class="order-xs-first order-md-last">
          <link-game-round :round-id="fine.round_id" />
        </div>
      </div>

      <div class="text-weight-medium">
        <span class="opacity-60 text-sm">Fined on</span>
        {{ dayjs(fine.created_at).format('YYYY-MM-DD [at] h:mma') }}
      </div>

      <vote-control
        class="q-mt-md"
        v-model:votes="fine.votes"
        v-model:userVotes="fine.user_votes"
        voteable-type="fine"
        :voteable-id="fine.id"
      />
    </q-card-section>
  </q-card>
</template>

<script>
import dayjs from 'dayjs';
import AppLayout from '@/Layouts/AppLayout.vue'
import LinkGameRound from '@/Components/LinkGameRound.vue'
import VoteControl from '@/Components/VoteControl.vue'

export default {
  layout: (h, page) =>
    h(
      AppLayout,
      {
        title: `Fine #${page.props.fine.id}`,
      },
      () => page
    ),

  components: {
    LinkGameRound,
    VoteControl,
  },

  setup() {
    return {
      dayjs
    }
  },

  props: {
    fine: Object,
  },
}
</script>
