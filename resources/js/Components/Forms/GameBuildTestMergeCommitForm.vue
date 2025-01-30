<template>
  <q-form @submit="submit">
    <q-input
      v-model="form.commit"
      class="q-mb-md"
      label="Commit"
      filled
      lazy-rules
      hide-bottom-space
      :error="!!form.errors.commit"
      :error-message="form.errors.commit"
    >
      <template #append>
        <q-btn
          @click="getPrDetails"
          :icon="ionReload"
          :loading="loadingPrDetails"
          color="primary"
          size="md"
          flat
          round
        >
          <q-tooltip :offset="[14, 4]" :transition-duration="100">
            <span style="font-size: 12px">Set to latest pull request commit</span>
          </q-tooltip>
        </q-btn>
      </template>
    </q-input>
    <div>
      <slot name="actions" :state="state" :loading="form.processing" />
    </div>
  </q-form>
</template>

<script>
import { ionReload } from '@quasar/extras/ionicons-v6'
import BaseForm from './BaseForm.vue'

export default {
  extends: BaseForm,

  props: {
    prId: Number,
  },

  setup() {
    return {
      ionReload,
    }
  },

  data() {
    return {
      loadingPrDetails: false,
      latestPrCommit: this.fields.commit,
    }
  },

  methods: {
    async getPrDetails() {
      if (this.loadingPrDetails) return
      this.loadingPrDetails = true
      try {
        const { data } = await axios.get(route('admin.builds.test-merges.pr-details', this.prId))
        this.form.commit = data.head.sha
      } catch (e) {
        this.$q.notify({
          message:
            e.response.data.message || 'Unable to fetch pull request details, please try again.',
          color: 'negative',
        })
      }
      this.loadingPrDetails = false
    },
  },
}
</script>
