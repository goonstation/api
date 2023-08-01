<template>
  hi
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default {
  layout: (h, page) => h(AdminLayout, { title: 'Admin' }, () => page),

  props: {
    gameAdmin: Object
  }
}
</script>
