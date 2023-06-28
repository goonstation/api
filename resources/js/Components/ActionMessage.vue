<script setup>
defineProps({
  on: Boolean,
})
</script>

<template>
  <div>
    <transition
      leave-active-class="transition"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-show="on" class="text-sm text-grey">
        <slot />
      </div>
    </transition>
  </div>
</template>

<style scoped>
.transition {
  transition: all 1000ms ease-in;
}
.opacity-100 {
  opacity: 1;
}
.opacity-0 {
  opacity: 0;
}
</style>
