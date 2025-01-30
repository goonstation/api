<template>
  <q-icon
    :name="modelValue ? ionCheckmarkCircleOutline : ionEllipseOutline"
    :color="modelValue ? 'primary' : 'grey'"
    size="sm"
  />
</template>

<script>
import { ionEllipseOutline, ionCheckmarkCircleOutline } from '@quasar/extras/ionicons-v6'

export default {
  props: ['modelValue'],

  setup() {
    return {
      ionEllipseOutline,
      ionCheckmarkCircleOutline,
    }
  },
}
</script>
