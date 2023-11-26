<template>
  <q-card class="gh-card" flat>
    <q-card-section>
      <div class="flex gap-xs-sm">
        <div>
          <div class="text-primary text-lg">{{ antag.mob_name }}</div>
          <div v-if="antag.mob_job">{{ antag.mob_job }}</div>
          <q-chip
            v-if="antag.success"
            color="positive"
            class="q-ml-none text-weight-bold"
            text-color="dark"
            square
          >
            Succeeded
          </q-chip>
          <q-chip
            v-else
            color="negative"
            class="q-ml-none text-weight-bold"
            text-color="dark"
            square
          >
            Failed
          </q-chip>
        </div>

        <q-space />

        <div>
          <link-game-round :round-id="antag.round_id" />
        </div>
      </div>

      <q-list class="q-mt-md" dense>
        <q-item-label header class="q-py-sm">Objectives</q-item-label>
        <q-item v-if="antag.objectives.length" v-for="objective in antag.objectives">
          <q-item-section
            style="white-space: pre-wrap"
            v-dompurify-html="objective.objective"
          ></q-item-section>
          <q-item-section avatar>
            <q-chip
              v-if="objective.success"
              class="text-sm text-weight-bold"
              color="green"
              text-color="dark"
              square
            >
              Succeeded
            </q-chip>
            <q-chip v-else class="text-sm text-weight-bold" color="red" text-color="dark" square>
              Failed
            </q-chip>
          </q-item-section>
        </q-item>
        <q-item v-else>None</q-item>
      </q-list>

      <q-list dense>
        <q-item-label header class="q-py-sm">Item Purchases</q-item-label>
        <q-item v-if="antag.item_purchases.length">
          <q-item-section>
            <div class="flex wrap">
              <q-chip v-for="itemPurchase in antag.item_purchases" color="grey-9" square>
                {{ itemPurchase.item }}
              </q-chip>
            </div>
          </q-item-section>
        </q-item>
        <q-item v-else>None</q-item>
      </q-list>
    </q-card-section>
  </q-card>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import LinkGameRound from '@/Components/LinkGameRound.vue'

export default {
  layout: (h, page) =>
    h(
      AppLayout,
      {
        title: `Antagonist #${page.props.antag.id}`,
      },
      () => page
    ),

  components: {
    LinkGameRound,
  },

  props: {
    antag: Object,
  },
}
</script>
