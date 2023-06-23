<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({
    layout: (h, page) => h(AdminLayout, { title: 'Dashboard' }, () => page)
})
</script>

<template>
    <div>This is a test</div>
</template>
