<template>
  <q-btn
    v-if="$store.ParentPage.url"
    dense
    flat
    round
    :icon="ionChevronBackCircle"
    @click="router.visit($store.ParentPage.url)"
  />
</template>

<script>
import { router } from '@inertiajs/vue3'
import { ionChevronBackCircle } from '@quasar/extras/ionicons-v6'

export default {
  setup() {
    return {
      router,
      ionChevronBackCircle,
    }
  },
}
</script>
