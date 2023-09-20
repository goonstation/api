<template>
  <component :is="filterComponent" v-bind="$attrs" class="gh-select--denser" square filled dense />
</template>

<script>
import { upperFirst } from 'lodash'
import { defineAsyncComponent } from 'vue'

const componentNames = import.meta.glob('./Filters/*.vue')
const components = []
for (const name in componentNames) {
  const cleanName = name.replace(/(^.\/)|(\.vue$)|(Filters\/)/g, '')
  components[cleanName] = defineAsyncComponent(() =>
    import(/* @vite-ignore */ `./Filters/${cleanName}.vue`)
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

  computed: {
    filterComponent() {
      return upperFirst(this.filterType.toLowerCase())
    },
  },
}
</script>
