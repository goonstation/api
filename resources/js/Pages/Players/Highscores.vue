<template>
  <q-card class="gh-card" flat>
    <div class="q-pa-md">
      <span class="flex items-center">
        <span class="text-weight-medium text-body2">Highscores for</span>
        <q-select
          v-model="type"
          class="q-ml-md"
          :options="options"
          @filter="filterFn"
          use-input
          fill-input
          hide-selected
          input-debounce="0"
          dense
        >
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey"> No results </q-item-section>
            </q-item>
          </template>
        </q-select>
      </span>
    </div>
    <q-separator />
    <player-highscores-table :initial="highscores" :search="search" />
  </q-card>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import PlayersLayout from '@/Layouts/PlayersLayout.vue'
import PlayerHighscoresTable from '@/Components/Tables/PlayerHighscoresTable.vue'

export default {
  layout: (h, page) => {
    return h(AppLayout, { title: 'Player Highscores' }, () => h(PlayersLayout, () => page))
  },

  components: {
    PlayerHighscoresTable,
  },

  props: {
    highscores: Object,
    types: Array,
    filteredType: String,
  },

  data() {
    return {
      type: null,
      options: [],
    }
  },

  computed: {
    search() {
      return { type: this.type }
    },
  },

  created() {
    this.options = this.types
    this.type = this.filteredType
  },

  methods: {
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase()
        this.options = this.types.filter((v) => v.toLowerCase().indexOf(needle) > -1)
      })
    },
  },
}
</script>
