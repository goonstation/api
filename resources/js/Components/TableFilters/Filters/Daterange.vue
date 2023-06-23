<template>
  <q-input
    v-model="inputModel"
    class="gh-input--denser"
    placeholder="YYYY/MM/DD-YYYY/MM/DD"
    square
    filled
    dense
    clearable
  >
    <template v-slot:append>
      <q-icon :name="ionCalendarClearOutline" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-date v-model="model" range text-color="black">
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Close" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>

<script>
import { isObject } from 'lodash'
import { ionCalendarClearOutline } from '@quasar/extras/ionicons-v6'

export default {
  props: ['modelValue'],

  setup() {
    return {
      ionCalendarClearOutline
    }
  },

  computed: {
    model: {
      get() {
        let dateObj = this.modelValue
        if (typeof this.modelValue === 'string') {
          const dates = this.modelValue.split('-')
          if (dates && dates.length > 1) {
            dateObj = { from: dates[0], to: dates[1] }
          }
        }
        return dateObj
      },
      set(val) {
        if (isObject(val)) val = `${val.from}-${val.to}`
        this.$emit('update:modelValue', val)
      },
    },

    inputModel: {
      get() {
        if (!this.model) return
        return `${this.model.from}-${this.model.to}`
      },
      set(val) {
        if (!val) return (this.model = null)
        const dates = val.split('-')
        if (!dates || dates.length < 2) return (this.model = null)
        this.model = { from: dates[0], to: dates[1] }
      },
    },
  },
}
</script>
