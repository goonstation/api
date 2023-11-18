<template>
  <q-btn
    v-if="parentPage"
    dense
    flat
    round
    :icon="ionChevronBackCircle"
    @click="router.visit(parentPage)"
  />
</template>

<script>
import { router } from '@inertiajs/vue3'
import { ionChevronBackCircle } from '@quasar/extras/ionicons-v6'

let removeBeforeListener

export default {
  setup() {
    return {
      router,
      ionChevronBackCircle,
    }
  },

  data() {
    return {
      searchParams: ''
    }
  },

  created() {
    removeBeforeListener = router.on('before', () => {
      this.searchParams = window.location.search
    })
  },

  beforeUnmount() {
    removeBeforeListener()
  },

  computed: {
    breadcrumbs() {
      return this.$page.props.breadcrumbs
    },

    parentPage() {
      if (!this.breadcrumbs || this.breadcrumbs.length < 2) return null
      return this.breadcrumbs[this.breadcrumbs.length - 2].url + this.searchParams
    },
  },
}
</script>
