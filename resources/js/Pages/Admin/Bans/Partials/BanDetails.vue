<template>
  <q-card class="gh-card" flat>
    <div class="gh-card__header q-pa-md bordered">
      <span>Additional Connection Details</span>
    </div>
    <q-card-section class="q-pa-none">
      <q-banner class="bg-grey-10 q-ma-sm">
        <template v-slot:avatar>
          <q-icon :name="ionInformationCircleOutline" color="primary" size="md" class="q-mt-xs" />
        </template>
        This ban also applies to any players who connect with the following details.
      </q-banner>
      <q-table
        :rows="details"
        :columns="banDetailsColumns"
        :pagination="{ rowsPerPage: 20 }"
        flat
        dense
      >
        <template v-slot:body-cell-ckey="props">
          <q-td :props="props">
            <Link v-if="props.row.ckey" :href="route('admin.player.show-by-ckey', props.row.ckey)">
              {{ props.row.ckey }}
            </Link>
          </q-td>
        </template>

        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn
              @click="openConfirmDelete(props.row.id)"
              :icon="ionClose"
              class="q-ma-xs"
              color="negative"
              size="xs"
              round
              outline
            />
          </q-td>
        </template>

        <template v-slot:bottom-row>
          <q-tr v-if="addingItem">
            <q-td>
              <q-input v-model="addFields.ckey" label="Ckey" dense outlined clearable />
            </q-td>
            <q-td>
              <q-input v-model="addFields.comp_id" label="Computer ID" dense outlined clearable />
            </q-td>
            <q-td>
              <q-input v-model="addFields.ip" label="IP" dense outlined clearable />
            </q-td>
            <q-td>
              <q-btn
                @click="addItem"
                :icon="ionCheckmark"
                class="q-ma-xs"
                color="positive"
                size="xs"
                round
                outline
              />
            </q-td>
          </q-tr>
          <q-tr>
            <q-td colspan="100%" class="text-center">
              <q-btn
                @click="addingItem = !addingItem"
                :icon="addingItem ? ionRemove : ionAdd"
                color="primary"
                text-color="dark"
                size="sm"
                class="q-ma-xs"
              >
                {{ addingItem ? 'Cancel' : 'Add' }}
              </q-btn>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </q-card-section>
  </q-card>

  <q-dialog v-model="confirmDelete">
    <q-card flat bordered>
      <q-card-section class="row items-center no-wrap">
        <q-avatar :icon="ionInformationCircleOutline" color="negative" text-color="dark" />
        <span class="q-ml-sm"> Are you sure you want to delete this? </span>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Cancel" v-close-popup />
        <q-btn flat label="Confirm" color="negative" @click="deleteItem" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import {
  ionInformationCircleOutline,
  ionClose,
  ionAdd,
  ionRemove,
  ionCheckmark,
} from '@quasar/extras/ionicons-v6'

export default {
  setup() {
    return {
      ionInformationCircleOutline,
      ionClose,
      ionAdd,
      ionRemove,
      ionCheckmark,
    }
  },

  props: {
    modelValue: Object,
  },

  data() {
    return {
      banDetailsColumns: [
        {
          name: 'ckey',
          field: 'ckey',
          label: 'Ckey',
          sortable: true,
        },
        {
          name: 'comp_id',
          field: 'comp_id',
          label: 'Computer ID',
          sortable: true,
        },
        {
          name: 'ip',
          field: 'ip',
          label: 'IP',
          sortable: true,
        },
        {
          name: 'actions',
          label: '',
          sortable: false,
        },
      ],
      deletingItem: null,
      confirmDelete: false,
      addingItem: false,
      addFields: {
        ckey: null,
        comp_id: null,
        ip: null,
      },
    }
  },

  computed: {
    ban: {
      get() {
        return this.modelValue
      },
      set(val) {
        console.log('in setter', val)
        this.$emit('update:modelValue', val)
      },
    },

    details() {
      return this.ban.details.filter((detail) => {
        return detail.id !== this.ban.original_ban_detail.id
      })
    },
  },

  methods: {
    openConfirmDelete(id) {
      this.deletingItem = id
      this.confirmDelete = true
    },

    async deleteItem() {
      try {
        const response = await axios.delete(route('admin.bans.destroy-detail', this.deletingItem))
        this.$q.notify({
          message: response.data.message || 'Item successfully deleted.',
          color: 'positive',
        })
      } catch {
        this.deletingItem = null
        this.confirmDelete = false
        this.$q.notify({
          message: 'Failed to delete item, please try again.',
          color: 'negative',
        })
        return
      }

      // remove row
      this.ban.details = this.ban.details.filter((detail) => {
        return detail.id !== this.deletingItem
      })

      this.deletingItem = null
      this.confirmDelete = false
    },

    async addItem() {
      try {
        const response = await axios.post(
          route('admin.bans.store-detail', this.ban.id),
          this.addFields
        )
        console.log(response)

        // add row
        this.ban.details.unshift(response.data.data)

        this.$q.notify({
          message: 'Item successfully added.',
          color: 'positive',
        })
      } catch (e) {
        this.$q.notify({
          message: e.response.data.message || 'Failed to add item, please try again.',
          color: 'negative',
        })
        return
      }

      this.addingItem = false
    },
  },
}
</script>
