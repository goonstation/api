<template>
  <div>
    <q-btn-group class="full-width" square flat>
      <q-btn
        :label="fromModel ? fromModel : ''"
        :icon="fromModel ? '' : ionCalendarClearOutline"
        :align="fromModel ? 'left' : 'center'"
        class="flex-grow"
        color="grey-10"
      >
        <q-popup-proxy transition-show="scale" transition-hide="scale" class="row">
          <div>
            <div class="row">
              <q-date v-model="fromModel" mask="YYYY/MM/DD HH:mm" text-color="black" />
              <q-time v-model="fromModel" mask="YYYY/MM/DD HH:mm" text-color="black" />
            </div>
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Close" color="primary" flat />
            </div>
          </div>
        </q-popup-proxy>
      </q-btn>
      <q-btn v-if="fromModel" :icon="ionClose" @click="clearFrom" color="grey-10" dense />
    </q-btn-group>

    <div class="text-center">to</div>

    <q-btn-group class="full-width" square flat>
      <q-btn
        :label="toModel ? toModel : ''"
        :icon="toModel ? '' : ionCalendarClearOutline"
        :align="toModel ? 'left' : 'center'"
        class="flex-grow"
        color="grey-10"
      >
        <q-popup-proxy transition-show="scale" transition-hide="scale" class="row">
          <div>
            <div class="row">
              <q-date v-model="toModel" mask="YYYY/MM/DD HH:mm" text-color="black" />
              <q-time v-model="toModel" mask="YYYY/MM/DD HH:mm" text-color="black" />
            </div>
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Close" color="primary" flat />
            </div>
          </div>
        </q-popup-proxy>
      </q-btn>
      <q-btn v-if="toModel" :icon="ionClose" @click="clearTo" color="grey-10" dense />
    </q-btn-group>
  </div>
</template>

<script>
import { ionCalendarClearOutline, ionClose } from '@quasar/extras/ionicons-v6'

export default {
  props: ['modelValue'],

  setup() {
    return {
      ionCalendarClearOutline,
      ionClose,
    }
  },

  computed: {
    dateRanges() {
      if (typeof this.modelValue === 'string') {
        return this.modelValue.split('-')
      }
      return this.modelValue
    },

    fromModel: {
      get() {
        let ret = this.dateRanges
        if (ret && typeof ret === 'object') ret = ret[0]
        return ret
      },
      set(val) {
        let ret = ''
        if (val) ret += val
        if (this.toModel) ret += `-${this.toModel}`
        this.$emit('update:modelValue', ret)
      },
    },

    toModel: {
      get() {
        let ret = this.dateRanges
        if (ret && typeof ret === 'object') ret = ret[1]
        return ret
      },
      set(val) {
        let ret = ''
        if (this.fromModel) ret += this.fromModel
        if (this.fromModel && val) ret += '-'
        if (val) ret += val
        this.$emit('update:modelValue', ret)
      },
    },
  },

  methods: {
    clearFrom() {
      this.fromModel = null
      this.$nextTick(() => {
        if (this.toModel) this.toModel = null
      })
    },

    clearTo() {
      this.toModel = null
    },
  },
}
</script>
