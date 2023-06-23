<template>
  <component v-if="eventComponent" :is="eventComponent" :events="events" />
</template>

<script>
import { shallowRef } from 'vue'
import { startCase, camelCase } from 'lodash'

export default {
  props: {
    type: {
      type: String,
      required: true
    },
    events: {
      type: Array,
      required: true
    },
  },

  setup(props) {
    const eventComponent = shallowRef('')
    const type = startCase(camelCase(props.type)).replace(/ /g, '')
    import(`./Event${type}.vue`).then(val => {
      eventComponent.value = val.default
    })
    return {
      eventComponent
    }
  }
}
</script>
