<template>
  <div class="flex items-center no-wrap">
    <q-btn-group class="flex-grow" square flat>
      <q-btn
        :icon="fromModel ? ionCalendar : ionCalendarClearOutline"
        align="center"
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
        <q-tooltip v-if="fromModel" class="text-subtitle2">
          {{ fromModel }}
        </q-tooltip>
      </q-btn>
      <q-btn v-if="fromModel" :icon="ionClose" @click="clearFrom" color="grey-10" dense />
    </q-btn-group>

    <div class="q-mx-sm">to</div>

    <q-btn-group class="flex-grow" square flat>
      <q-btn
        :icon="toModel ? ionCalendar : ionCalendarClearOutline"
        align="center"
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
        <q-tooltip v-if="toModel" class="text-subtitle2">
          {{ toModel }}
        </q-tooltip>
      </q-btn>
      <q-btn v-if="toModel" :icon="ionClose" @click="clearTo" color="grey-10" dense />
    </q-btn-group>
  </div>
</template>

<script>
import { ionCalendarClearOutline, ionCalendar, ionClose } from '@quasar/extras/ionicons-v6'
import BaseDateRange from './BaseDateRange.vue'

export default {
  extends: BaseDateRange,

  setup() {
    return {
      ionCalendar,
      ionCalendarClearOutline,
      ionClose,
    }
  },
}
</script>
