<template>
  <q-timeline color="primary">
    <q-timeline-entry>
      <ban-lookup-form :fields="fields" :submit-route="route('admin.bans.lookup-details')" />
    </q-timeline-entry>

    <q-timeline-entry>
      <q-card class="gh-card" flat>
        <div class="gh-card__header q-pa-md bordered">
          <span>Review Removal Plan</span>
        </div>
        <ban-removal-plan
          v-if="lookup?.length"
          :bans="lookup"
          :matching-fields="lookupFields"
          @details-updated="onDetailsUpdated"
        />
        <div v-else class="q-pa-md">
          <div v-if="lookupFields">No bans found for those details, please try again.</div>
          <div v-else>Enter some connection details to lookup above.</div>
        </div>
      </q-card>
    </q-timeline-entry>

    <q-timeline-entry>
      <q-card class="gh-card" flat>
        <div class="gh-card__header q-pa-md bordered">
          <span>Confirm Removal Plan</span>
        </div>
        <q-card-section style="max-width: 500px">
          <div v-if="details.length">
            <div class="q-mb-sm">You are about to:</div>
            <q-banner v-if="totals.deleteBans" class="bg-negative q-py-sm q-px-md q-mb-sm" dense>
              <div>
                Delete {{ totals.deleteBans }} ban<template v-if="totals.deleteBans !== 1"
                  >s</template
                >
              </div>
            </q-banner>
            <q-banner v-if="totals.deleteDetails" class="bg-negative q-py-sm q-px-md q-mb-sm" dense>
              <div>
                Delete {{ totals.deleteDetails }} connection detail<template
                  v-if="totals.deleteDetails !== 1"
                  >s</template
                >
              </div>
            </q-banner>
            <q-banner v-if="totals.editDetails" class="bg-info q-py-sm q-px-md q-mb-sm" dense>
              <div>
                Edit {{ totals.editDetails }} connection detail<template
                  v-if="totals.editDetails !== 1"
                  >s</template
                >
              </div>
            </q-banner>
            <div class="q-mb-sm text-right">
              This cannot be undone! Are you sure you want to continue?
            </div>
          </div>
          <div class="flex">
            <q-space />
            <q-btn
              label="Confirm"
              color="primary"
              text-color="black"
              :disabled="!details.length"
              :loading="false"
            />
          </div>
        </q-card-section>
      </q-card>
    </q-timeline-entry>
  </q-timeline>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BanLookupForm from '@/Components/Forms/BanLookupForm.vue'
import BanRemovalPlan from './Partials/BanRemovalPlan.vue'

export default {
  components: {
    BanLookupForm,
    BanRemovalPlan,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Remove Ban' }, () => page),

  props: {
    lookup: Object,
    lookupFields: Object,
  },

  data() {
    return {
      fields: {
        ckey: this.lookupFields?.ckey || null,
        comp_id: this.lookupFields?.comp_id || null,
        ip: this.lookupFields?.ip || null,
      },
      details: [],
      totals: {
        deleteBans: 0,
        deleteDetails: 0,
        editDetails: 0,
      },
    }
  },

  methods: {
    onDetailsUpdated({ deleting, editing }) {
      const data = {
        deleteBans: 0,
        deleteDetails: 0,
        editDetails: editing.length,
      }

      for (const detailId of deleting) {
        let deletingBan = false
        for (const ban of this.lookup) {
          if (detailId === ban.original_ban_detail.id) {
            deletingBan = true
            data.deleteBans++
            break
          }
        }

        if (!deletingBan) {
          data.deleteDetails++
        }
      }

      this.totals = data
      this.details = [...deleting, ...editing]
    },
  },
}
</script>
