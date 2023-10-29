<template>
  <div class="row q-col-gutter-md">
    <div :class="[verticalCategories ? 'col-auto' : 'col-12']">
      <q-card class="gh-card gh-card--small" flat>
        <div class="gh-card__header q-mr-md">
          <q-icon :name="ionFilter" size="18px" />
          <span class="text-sm">Category</span>
        </div>

        <q-card-section class="q-pa-none">
          <q-tabs
            v-model="tab"
            active-color="primary"
            indicator-color="transparent"
            align="left"
            :vertical="verticalCategories"
            :switch-indicator="verticalCategories"
          >
            <q-tab
              v-for="type in types"
              class="items-center"
              :name="type.name"
              :class="[verticalCategories ? 'tab-left-align' : null]"
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
.tab-left-align {
  justify-content: initial;
  text-align: left
}

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
        { name: 'Overview', href: '/events' },
        { name: 'Antagonists', href: '/events/antags' },
        { name: 'Deaths', href: '/events/deaths' },
        { name: 'Fines', href: '/events/fines' },
        { name: 'Tickets', href: '/events/tickets' },
      ],
      loading: false,
    }
  },

  computed: {
    verticalCategories() {
      return !this.$q.screen.lt.md
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
