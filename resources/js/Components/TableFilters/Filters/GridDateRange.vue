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
import BaseDateRange from './BaseDateRange.vue'

export default {
  extends: BaseDateRange,

  setup() {
    return {
      ionCalendarClearOutline,
      ionClose,
    }
  },
}
</script>
