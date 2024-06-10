<template>
  <q-form @submit="submit">
    <q-card class="gh-card" flat>
      <div class="gh-card__header q-pa-md bordered">
        <span>Lookup Details</span>
      </div>
      <q-card-section style="max-width: 500px">
        <q-banner class="bg-grey-10 q-py-sm q-px-md q-mb-lg" dense>
          <template v-slot:avatar>
            <q-icon :name="ionInformationCircleOutline" color="primary" size="md" class="q-mt-xs" />
          </template>
          <div>
            This tool allows you to lookup bans that contain <strong>ANY</strong> of the provided
            details for the purposes of removing those details from all ban records.
          </div>
        </q-banner>

        <q-input
          v-model="form.ckey"
          label="Ckey"
          filled
          lazy-rules
          dense
          :error="!!form.errors.ckey"
          :error-message="form.errors.ckey"
        />
        <q-input
          v-model="form.comp_id"
          label="Computer ID"
          filled
          lazy-rules
          dense
          :error="!!form.errors.comp_id"
          :error-message="form.errors.comp_id"
        />
        <q-input
          v-model="form.ip"
          label="IP Address"
          filled
          lazy-rules
          dense
          :error="!!form.errors.ip"
          :error-message="form.errors.ip"
        />
        <div class="flex">
          <q-space />
          <q-btn
            label="Lookup"
            type="submit"
            color="primary"
            text-color="black"
            :loading="form.processing"
          />
        </div>
      </q-card-section>
    </q-card>
  </q-form>
</template>

<script>
import { ionInformationCircleOutline } from '@quasar/extras/ionicons-v6'
import BaseForm from './BaseForm.vue'
import BaseSelect from '@/Components/Selects/BaseSelect.vue'

export default {
  extends: BaseForm,

  components: {
    BaseSelect,
  },

  setup() {
    return {
      ionInformationCircleOutline,
    }
  },
}
</script>
