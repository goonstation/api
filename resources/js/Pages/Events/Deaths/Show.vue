<template>
  <q-card class="gh-card" flat>
    <q-card-section>
      <div class="flex gap-xs-sm">
        <div>
          <div class="text-primary text-lg">{{ death.mob_name }}</div>
          <div v-if="death.mob_job">{{ death.mob_job }}</div>

          <div v-if="death.last_words" class="text-italic">"{{ death.last_words }}"</div>
        </div>

        <q-space />

        <div class="q-pt-sm">
          <link-game-round :round-id="death.round_id" />
        </div>
      </div>

      <div class="text-sm opacity-60 q-mt-md q-mb-xs">Died from</div>
      <div class="flex items-center">
        <div class="gh-details-list gh-details-list--non-collapsible">
          <div>
            <div class="bruteloss">{{ $formats.number(death.bruteloss) }}</div>
            <div>Brute</div>
          </div>
          <div>
            <div class="fireloss">{{ $formats.number(death.fireloss) }}</div>
            <div>Fire</div>
          </div>
          <div>
            <div class="toxloss">{{ $formats.number(death.toxloss) }}</div>
            <div>Toxin</div>
          </div>
          <div>
            <div class="oxyloss">{{ $formats.number(death.oxyloss) }}</div>
            <div>Oxygen</div>
          </div>
        </div>

        <q-chip
          v-if="death.gibbed"
          class="q-ml-md text-weight-bold"
          size="sm"
          color="red"
          square
          outline
        >
          Gibbed
        </q-chip>
      </div>

      <vote-control
        class="q-mt-md"
        v-model:votes="death.votes"
        v-model:userVotes="death.user_votes"
        voteable-type="death"
        :voteable-id="death.id"
      />
    </q-card-section>
  </q-card>
</template>

<style lang="scss" scoped>
.bruteloss {
  color: #f34d4d;
}
.fireloss {
  color: #ffc457;
}
.toxloss {
  color: #24c024;
}
.oxyloss {
  color: #9292fa;
}
</style>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import LinkGameRound from '@/Components/LinkGameRound.vue'
import VoteControl from '@/Components/VoteControl.vue'

export default {
  layout: (h, page) =>
    h(
      AppLayout,
      {
        title: `Death #${page.props.death.id}`,
      },
      () => page
    ),

  components: {
    LinkGameRound,
    VoteControl,
  },

  props: {
    death: Object,
  },
}
</script>
