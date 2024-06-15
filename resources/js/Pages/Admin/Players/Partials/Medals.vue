<template>
  <q-table
    :rows="modelValue"
    :columns="columns"
    :pagination="{ sortBy: 'title' }"
    flat
    dense
    wrap-cells
  >
    <template #body-cell-image="props">
      <q-td :props="props">
        <medal-thumbnail :medal="props.row.medal" size="32" />
      </q-td>
    </template>
    <template #body-cell-actions="props">
      <q-td :props="props">
        <q-btn
          @click="openConfirmDelete(props.row.medal.id)"
          :icon="ionClose"
          class="q-ma-xs"
          color="negative"
          size="xs"
          round
          outline
        />
      </q-td>
    </template>
  </q-table>

  <q-dialog v-model="confirmDelete">
    <q-card flat bordered>
      <q-card-section class="row items-center no-wrap">
        <q-avatar :icon="ionInformationCircleOutline" color="negative" text-color="dark" />
        <span class="q-ml-sm"
          >Are you sure you want to remove this medal award from this player?</span
        >
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Cancel" v-close-popup />
        <q-btn flat label="Confirm" color="negative" @click="deleteItem" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { ionClose, ionInformationCircleOutline } from '@quasar/extras/ionicons-v6'
import MedalThumbnail from '@/Components/MedalThumbnail.vue'

export default {
  emits: ['update:modelValue'],

  components: {
    MedalThumbnail,
  },

  props: {
    modelValue: Object,
    playerId: Number,
  },

  setup() {
    return {
      ionClose,
      ionInformationCircleOutline,
    }
  },

  data() {
    return {
      columns: [
        {
          name: 'image',
          label: '',
          field: 'image',
          align: 'center',
          sortable: false,
          filterable: false,
          headerClasses: 'q-table--col-auto-width',
        },
        {
          name: 'title',
          label: 'Title',
          field: 'title',
          align: 'left',
          sortable: true,
          format: (val, row) => row.medal.title,
          rawSort: (a, b, rowA, rowB) => rowA.medal.title.localeCompare(rowB.medal.title),
        },
        {
          name: 'description',
          label: 'Description',
          field: 'description',
          align: 'left',
          sortable: true,
          format: (val, row) => row.medal.description,
          rawSort: (a, b, rowA, rowB) => rowA.medal.description.localeCompare(rowB.medal.description),
        },
        {
          name: 'created_at',
          label: 'Earned',
          field: 'created_at',
          sortable: true,
          format: this.$formats.date,
        },
        {
          name: 'actions',
          label: '',
          sortable: false,
          headerClasses: 'q-table--col-auto-width',
        },
      ],
      deletingItem: null,
      confirmDelete: false,
    }
  },

  methods: {
    openConfirmDelete(id) {
      this.deletingItem = id
      this.confirmDelete = true
    },

    async deleteItem() {
      try {
        const response = await axios.delete(
          route('admin.medals.remove-from-player', {
            player: this.playerId,
            medal: this.deletingItem,
          })
        )
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
      const newMedals = this.modelValue.filter((medal) => {
        return medal.medal.id !== this.deletingItem
      })

      this.deletingItem = null
      this.confirmDelete = false

      this.$emit('update:modelValue', newMedals)
    },
  },
}
</script>
