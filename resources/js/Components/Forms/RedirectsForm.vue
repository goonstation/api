<template>
  <div class="row">
    <div class="col-12 col-md-6">
      <q-form @submit="submit">
        <q-card class="gh-card q-mb-md" flat>
          <q-card-section>
            <q-input
              v-model="form.from"
              class="q-mb-md"
              label="From"
              hint="The path to direct from"
              :prefix="prefix"
              filled
              lazy-rules
              required
              hide-bottom-space
              :error="!!form.errors.from"
              :error-message="form.errors.from"
            />
            <q-input
              v-model="form.to"
              type="url"
              class="q-mb-md"
              label="To"
              hint="The URL to direct to"
              filled
              lazy-rules
              required
              hide-bottom-space
              :error="!!form.errors.to"
              :error-message="form.errors.to"
            />
          </q-card-section>
        </q-card>

        <div class="flex">
          <q-space />
          <q-btn
            :label="(state === 'edit' ? 'Edit' : 'Add') + ' Redirect'"
            type="submit"
            color="primary"
            text-color="black"
            :loading="form.processing"
          />
        </div>
      </q-form>
    </div>
  </div>
</template>

<script>
import BaseForm from './BaseForm.vue'

export default {
  extends: BaseForm,

  computed: {
    prefix() {
      return `${window.location.origin}/r/`
    }
  }
}
</script>
