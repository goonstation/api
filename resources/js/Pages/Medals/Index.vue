<template>
  <div>
    <Link
      v-for="medal in medals"
      :href="route('medals.show', medal.uuid)"
      class="gh-link-card"
      style="padding: 0"
    >
      <medal :medal="medal" />
    </Link>
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Medal from './Partials/Medal.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Medals' }, () => page),

  components: {
    Medal,
  },

  props: {
    medals: Object,
  },
}
</script>
