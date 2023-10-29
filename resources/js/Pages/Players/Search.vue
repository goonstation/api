<template>
  <div>
    <div class="player-search">
      <q-form @submit="onSubmit">
        <q-input
          v-model="search"
          rounded
          bg-color="dark"
          input-class="text-white q-px-md"
          standout="bg-grey-9 text-white"
          placeholder="Player username"
          class="q-mb-md"
        >
          <template v-slot:append>
            <q-btn
              v-if="search"
              flat
              round
              color="white"
              padding="xs"
              :icon="ionCloseOutline"
              @click="onClear"
              class="q-mr-sm"
            />
            <q-btn
              @click="onSubmit"
              type="submit"
              color="primary"
              text-color="dark"
              label="Search"
              :loading="showButtonLoad"
              :icon="ionSearch"
              rounded
            />
          </template>
        </q-input>
      </q-form>

      <q-card v-if="showTutorial" flat class="text-center q-pa-md">
        <q-card-section>
          <q-icon :name="ionPeople" size="5rem" class="q-mb-md" />
          <div class="text-body1">
            Search for your favorite (or least favorite) player above by their BYOND username.
          </div>
        </q-card-section>
      </q-card>
    </div>

    <players-table
      v-show="!showTutorial"
      flat
      :search="{ name: searchFilter }"
      :pagination="{ rowsPerPage: 20 }"
      @loaded="onTableLoaded"
      @fetch-start="tableFetching = true"
      @fetch-end="onTableFetchEnd"
    />
  </div>
</template>

<style lang="scss" scoped>
@media (min-width: $breakpoint-md-min) {
  .player-search {
    padding: 1rem 20%;
  }
}
</style>

<script>
import { ionSearch, ionCloseOutline, ionPeople } from '@quasar/extras/ionicons-v6'
import AppLayout from '@/Layouts/AppLayout.vue'
import PlayersLayout from '@/Layouts/PlayersLayout.vue'
import PlayersTable from '@/Components/Tables/PlayersTable.vue'

export default {
  layout: (h, page) => {
    return h(AppLayout, { title: 'Player Search' }, () => h(PlayersLayout, () => page))
  },

  setup() {
    return {
      ionSearch,
      ionCloseOutline,
      ionPeople
    }
  },

  components: {
    PlayersTable,
  },

  data() {
    return {
      search: null,
      searchFilter: null,
      tableFetching: false,
      forceShowTutorial: false,
    }
  },

  computed: {
    showTutorial() {
      return this.forceShowTutorial || this.searchFilter === null
    },

    showButtonLoad() {
      return this.showTutorial && this.tableFetching
    },
  },

  methods: {
    onSubmit() {
      this.searchFilter = this.search
    },

    onClear() {
      this.search = null
      this.forceShowTutorial = true
      this.onSubmit()
    },

    onTableLoaded({ filters }) {
      if (filters.name) this.search = this.searchFilter = filters.name
    },

    onTableFetchEnd() {
      this.tableFetching = false
      if (this.search && this.forceShowTutorial) this.forceShowTutorial = false
    },
  },
}
</script>
