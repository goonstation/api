<template>
  <game-build-settings-table prop-key="settings" />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import GameBuildSettingsTable from '@/Components/Tables/Admin/GameBuildSettingsTable.vue'
import GameBuildsLayout from '../Layout.vue'
import GameBuildsIndexLayout from '../IndexLayout.vue'

export default {
  layout: (h, page) => {
    return h(AdminLayout, { title: 'Build Settings' }, () =>
      h(
        GameBuildsLayout,
        () => page,
        () => h(GameBuildsIndexLayout, () => page)
      )
    )
  },

  components: {
    GameBuildSettingsTable,
  },
}
</script>
