<template>
  <div class="row q-col-gutter-md">
    <div class="col-12 col-md-6">
      <q-card class="gh-card q-mb-md" flat>
        <div class="gh-card__header q-pa-md bordered">
          <span>Status</span>
        </div>
        <q-card-section class="q-pa-sm">
          <q-chip v-if="!isExpired && !isDeleted" color="positive" square> Active Ban </q-chip>
          <q-chip v-else color="negative" square> Inactive Ban </q-chip>
        </q-card-section>
      </q-card>

      <q-card class="gh-card q-mb-md" flat>
        <div class="gh-card__header q-pa-md bordered">
          <span>Player Details</span>
        </div>
        <q-card-section>
          <q-markup-table flat bordered>
            <tbody>
              <tr>
                <td><strong>Ckey</strong></td>
                <td>
                  <Link :href="route('admin.player.show-by-ckey', ban.original_ban_detail.ckey)">
                    {{ ban.original_ban_detail.ckey }}
                  </Link>
                </td>
              </tr>
              <tr>
                <td><strong>Computed ID</strong></td>
                <td>{{ ban.original_ban_detail.comp_id }}</td>
              </tr>
              <tr>
                <td><strong>IP</strong></td>
                <td>{{ ban.original_ban_detail.ip }}</td>
              </tr>
            </tbody>
          </q-markup-table>
        </q-card-section>
      </q-card>

      <q-card class="gh-card" flat>
        <div class="gh-card__header q-pa-md bordered">
          <span>Ban Details</span>
        </div>
        <q-card-section>
          <q-markup-table flat bordered wrap-cells>
            <tbody>
              <tr>
                <td><strong>Banning Admin</strong></td>
                <td>{{ ban.game_admin.name || ban.game_admin.ckey }}</td>
              </tr>
              <tr>
                <td><strong>Server/s</strong></td>
                <td>
                  <template v-if="ban.server_id">
                    {{ ban.game_server.name }}
                  </template>
                  <template v-else> All </template>
                </td>
              </tr>
              <tr>
                <td><strong>Reason</strong></td>
                <td>{{ ban.reason }}</td>
              </tr>
              <tr v-if="isDeleted">
                <td><strong>Removed At</strong></td>
                <td>
                  {{ humanDeletedAt }}
                  <span class="text-caption opacity-80 q-ml-xs">
                    ({{ dayjs(ban.deleted_at).fromNow() }})
                  </span>
                </td>
              </tr>
              <tr v-else>
                <td><strong>Expires At</strong></td>
                <td>
                  <template v-if="ban.expires_at">
                    <template v-if="isExpired"> Expired </template>
                    <template v-else>
                      {{ humanExpiresAt }}
                    </template>
                    <span class="text-caption opacity-80 q-ml-xs">
                      ({{ dayjs(ban.expires_at).fromNow() }})
                    </span>
                  </template>
                  <template v-else> Permanent </template>
                </td>
              </tr>
            </tbody>
          </q-markup-table>
        </q-card-section>
      </q-card>
    </div>

    <div class="col-12 col-md-6">
      <q-card class="gh-card q-mb-md" flat>
        <div class="gh-card__header q-pa-md bordered">
          <span>Connection Details</span>
        </div>
        <q-card-section class="q-pa-none">
          <q-banner class="bg-grey-10 q-ma-sm">
            <template v-slot:avatar>
              <q-icon
                :name="ionInformationCircleOutline"
                color="primary"
                size="md"
                class="q-mt-xs"
              />
            </template>
            This ban applies to any players who connect with the following details.
          </q-banner>
          <q-table :rows="ban.details" :columns="banDetailsColumns" flat dense>
            <template v-slot:body-cell-ckey="props">
              <q-td :props="props">
                <Link
                  v-if="props.row.ckey"
                  :href="route('admin.player.show-by-ckey', props.row.ckey)"
                >
                  {{ props.row.ckey }}
                </Link>
              </q-td>
            </template>
          </q-table>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<style lang="scss" scoped>
tbody {
  td:first-child {
    width: 150px;
  }
}
</style>

<script>
import dayjs from 'dayjs'
import { date } from 'quasar'
import { ionInformationCircleOutline } from '@quasar/extras/ionicons-v6'
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default {
  layout: (h, page) =>
    h(
      AdminLayout,
      {
        title: `Ban #${page.props.ban.id}`,
      },
      () => page
    ),

  setup() {
    return {
      dayjs,
      ionInformationCircleOutline,
    }
  },

  props: {
    ban: Object,
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
      ],
    }
  },

  computed: {
    isExpired() {
      if (!this.ban.expires_at) return false
      return new Date(this.ban.expires_at) <= new Date()
    },

    humanExpiresAt() {
      if (!this.ban.expires_at) return null
      const expiresAt = new Date(this.ban.expires_at)
      return date.formatDate(expiresAt, 'YYYY/MM/DD HH:mm')
    },

    isDeleted() {
      return !!this.ban.deleted_at
    },

    humanDeletedAt() {
      if (!this.ban.deleted_at) return null
      const deletedAt = new Date(this.ban.deleted_at)
      return date.formatDate(deletedAt, 'YYYY/MM/DD HH:mm')
    },
  },
}
</script>
