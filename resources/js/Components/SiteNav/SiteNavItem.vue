<template>
  <q-item
    class="site-nav__item q-pa-none"
    :class="[
      {
        'site-nav__item--active': item.active,
      },
    ]"
    :active="item.active"
  >
    <Link
      :href="item.href"
      @focus="$emit('onNavItemEnter', $event)"
      @blur="$emit('onNavItemLeave', $event)"
      class="q-px-lg q-py-sm"
      v-ripple
    >
      <q-item-section class="site-nav__label">
        {{ item.label }}
      </q-item-section>
    </Link>
  </q-item>
</template>

<script>
export default {
  props: {
    item: Object,
  },
}
</script>
