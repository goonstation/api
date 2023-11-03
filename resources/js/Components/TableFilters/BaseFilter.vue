<template>
  <component :is="filterType" v-bind="$attrs" class="gh-select--denser" square filled dense />
</template>

<script>
import { defineAsyncComponent } from 'vue'

const componentNames = import.meta.glob('./Filters/*.vue')
const components = []
for (const name in componentNames) {
  const cleanName = name.replace(/(^.\/)|(\.vue$)|(Filters\/)/g, '')
  components[cleanName] = defineAsyncComponent(() =>
    import(`./Filters/${cleanName}.vue`)
  )
}

export default {
  components,

  props: {
    filterType: {
      type: String,
      required: true,
    },
  },
}
</script>
