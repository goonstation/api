<template>
  <q-form @submit="submit">
    <q-card class="gh-card" flat>
      <div class="gh-card__header q-pa-md bordered">
        <span>Lookup Details</span>
      </div>
      <q-card-section style="max-width: 500px;">
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
import BaseForm from './BaseForm.vue'
import BaseSelect from '@/Components/Selects/BaseSelect.vue'

export default {
  extends: BaseForm,

  components: {
    BaseSelect,
  },
}
</script>
