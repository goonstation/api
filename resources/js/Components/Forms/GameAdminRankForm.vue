<template>
  <div class="row">
    <q-card flat class="col-12 col-md-6">
      <q-card-section>
        <base-form
          :fields="fields"
          :submit-route="route('admin.game-admin-ranks.store')"
          success-message="Admin rank created."
        >
          <template v-slot="{ form }">
            <q-input
              v-model="form.rank"
              label="Rank"
              filled
              lazy-rules
              required
              autofocus
              :error="!!form.errors.rank"
              :error-message="form.errors.rank"
            />

            <div class="flex">
              <q-space />
              <q-btn
                label="Create"
                type="submit"
                color="primary"
                text-color="black"
                :loading="form.processing"
              />
            </div>
          </template>
        </base-form>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import BaseForm from './BaseForm.vue'

export default {
  components: {
    BaseForm,
  },

  data() {
    return {
      fields: {
        rank: '',
      },
    }
  },
}
</script>
