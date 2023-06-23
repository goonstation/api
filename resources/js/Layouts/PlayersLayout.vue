<template>
  <div class="row q-col-gutter-md">
    <div class="col-12">
      <q-card class="gh-card gh-card--small overflow-hidden" flat>
        <q-card-section class="q-pa-none">
          <q-tabs
            v-model="tab"
            active-color="primary"
            indicator-color="transparent"
            align="left"
          >
            <q-tab
              v-for="type in types"
              :name="type.name"
              class="items-center"
              content-class="q-pa-sm text-sm text-weight-medium"
              @click.prevent="router.visit(type.href)"
              >{{ type.name }}</q-tab
            >
          </q-tabs>
        </q-card-section>
      </q-card>
    </div>

    <div class="col">
      <transition name="page" mode="out-in">
        <slot />
      </transition>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.gh-card {
  position: sticky;
  top: 20px;
}

.page-enter-active {
  transition: all 0.3s ease-out;
}

.page-enter-from,
.page-leave-to {
  transform: translateY(10px);
  opacity: 0;
}
</style>

<script>
import { router } from '@inertiajs/vue3'
import { ionFilter } from '@quasar/extras/ionicons-v6'

export default {
  setup() {
    return {
      router,
      ionFilter,
    }
  },

  data() {
    return {
      tab: null,
      types: [
        { name: 'Overview', href: '/players' },
        { name: 'Highscores', href: '/players/highscores' },
        { name: 'Search', href: '/players/search' },
      ],
      loading: false,
    }
  },

  watch: {
    '$page.url': {
      immediate: true,
      handler(val) {
        const tab = this.types.findLast((type) => {
          return val.startsWith(type.href)
        })
        this.tab = tab && tab.name
      }
    }
  }
}
</script>
