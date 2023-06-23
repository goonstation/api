<template>
  <div>
    <changelog :changelog="changelog" class="q-mt-none" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Changelog from '@/Components/Changelog/Changelog.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Changelog' }, () => page),

  components: {
    Changelog,
  },

  props: {
    changelog: Object,
  },
}
</script>
