<template>
  <div>
    <div class="text-lg q-mb-md">Connection Detail for Ban #{{ detail.plan.item.ban_id }}</div>

    <q-banner
      v-if="ban.plan.item.original_ban_detail.id === detail.id"
      class="bg-grey-10 q-mb-md q-py-sm q-px-md"
      dense
    >
      <template v-slot:avatar>
        <q-icon :name="ionInformationCircleOutline" color="primary" size="md" class="q-mt-xs" />
      </template>
      <div>This is the root connection detail for a ban.</div>
    </q-banner>

    <q-markup-table class="q-mb-md" flat bordered>
      <tbody>
        <tr>
          <td><strong>Ckey</strong></td>
          <td>{{ detail.plan.item.ckey }}</td>
        </tr>
        <tr>
          <td><strong>Computer ID</strong></td>
          <td>{{ detail.plan.item.comp_id }}</td>
        </tr>
        <tr>
          <td><strong>IP</strong></td>
          <td>{{ detail.plan.item.ip }}</td>
        </tr>
      </tbody>
    </q-markup-table>

    <q-banner v-if="detail.plan.delete" class="bg-negative q-mb-md q-py-sm q-px-md" dense>
      <template v-slot:avatar>
        <q-icon :name="ionWarningOutline" color="white" size="md" />
      </template>
      <div>This connection detail will be deleted.</div>
    </q-banner>

    <template v-else-if="detail.plan.edit">
      <q-banner class="bg-info q-mb-md q-py-sm q-px-md" dense>
        <template v-slot:avatar>
          <q-icon :name="ionWarningOutline" color="white" size="md" />
        </template>
        <div>This connection detail will be edited to the following:</div>
      </q-banner>

      <div class="text-center q-mb-md">
        <q-icon :name="ionArrowDown" size="lg" />
      </div>

      <q-markup-table flat bordered>
        <tbody>
          <tr>
            <td><strong>Ckey</strong></td>
            <td>
              <template v-if="!detail.plan.matched.ckey">
                {{ detail.plan.item.ckey }}
              </template>
            </td>
          </tr>
          <tr>
            <td><strong>Computer ID</strong></td>
            <td>
              <template v-if="!detail.plan.matched.comp_id">
                {{ detail.plan.item.comp_id }}
              </template>
            </td>
          </tr>
          <tr>
            <td><strong>IP</strong></td>
            <td>
              <template v-if="!detail.plan.matched.ip">
                {{ detail.plan.item.ip }}
              </template>
            </td>
          </tr>
        </tbody>
      </q-markup-table>
    </template>
  </div>
</template>

<script>
import {
  ionInformationCircleOutline,
  ionWarningOutline,
  ionArrowDown,
} from '@quasar/extras/ionicons-v6'

export default {
  props: {
    ban: Object,
    detail: Object,
  },

  setup() {
    return {
      ionInformationCircleOutline,
      ionWarningOutline,
      ionArrowDown,
    }
  },
}
</script>
