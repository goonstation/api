<template>
  <div class="builds q-pb-md">
    <q-card class="gh-card q-mb-lg transparent" flat square>
      <div class="gh-card__header" style="border-top-left-radius: 4px">
        <span>Current Builds</span>
        <q-space />
        <q-btn
          :icon-right="ionAddCircleOutline"
          @click="startOpened = true"
          label="Start build"
          color="primary"
          size="sm"
          flat
          dense
        />
      </div>

      <q-card-section class="q-pa-none">
        <q-list>
          <template v-if="firstLoad">
            <q-item v-for="n in 2" :key="`current-skeleton-${n}`" class="q-py-md">
              <div>
                <q-skeleton type="text" width="50px" />
                <q-skeleton type="text" width="100px" />
              </div>
            </q-item>
          </template>

          <template v-else-if="status.current?.length">
            <build-status
              v-for="build in status.current"
              :key="`current-${build.server.id}`"
              :model-value="build"
              @update:model-value="build = $event"
              type="current"
            />
          </template>

          <q-item v-else class="q-py-md text-caption">
            <q-item-section>No builds are running</q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>

    <q-card class="gh-card transparent" flat square>
      <div class="gh-card__header">
        <span>Queued Builds</span>
      </div>

      <q-card-section class="q-pa-none">
        <q-list>
          <template v-if="firstLoad">
            <q-item v-for="n in 2" :key="`queued-skeleton-${n}`" class="q-py-md">
              <div>
                <q-skeleton type="text" width="50px" />
                <q-skeleton type="text" width="100px" />
              </div>
            </q-item>
          </template>

          <template v-else-if="status.queued?.length">
            <build-status
              v-for="build in status.queued"
              :key="`queued-${build.server.id}`"
              :model-value="build"
              @update:model-value="build = $event"
              type="queued"
            />
          </template>

          <q-item v-else class="q-py-md text-caption">
            <q-item-section>No builds are queued</q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>

    <start-game-build-dialog v-model="startOpened" />
  </div>
</template>

<style lang="scss" scoped>
.gh-card__header {
  padding: 12px 16px;
  background: rgba(255, 255, 255, 0.075);
  font-size: 0.85em;
}
</style>

<script>
import StartGameBuildDialog from '@/Components/Dialogs/StartGameBuild.vue'
import { ionAddCircleOutline } from '@quasar/extras/ionicons-v6'
import BuildStatus from './BuildStatus.vue'

export default {
  components: {
    StartGameBuildDialog,
    BuildStatus,
  },

  setup() {
    return {
      ionAddCircleOutline,
    }
  },

  data() {
    return {
      status: { current: [], queued: [] },
      firstLoad: true,
      startOpened: false,
    }
  },

  mounted() {
    this.update()
  },

  beforeUnmount() {
    Echo.private('game-builds').stopListening('GameBuildStarting', this.onBuildStarting)
    Echo.private('game-builds').stopListening('GameBuildQueuedStarting', this.onBuildQueuedStarting)
    Echo.private('game-builds').stopListening('GameBuildQueued', this.onBuildQueued)
    Echo.private('game-builds').stopListening('GameBuildFinished', this.onBuildFinished)
  },

  methods: {
    onBuildStarting(build) {
      if (this.status.current.find((v) => v.server.id === build.server.id)) return
      this.status.current.push(build)
    },

    onBuildQueuedStarting({ serverId }) {
      this.status.queued = this.status.queued.filter(
        (queuedBuild) => queuedBuild.server.id !== serverId
      )
    },

    onBuildQueued(build) {
      this.status.queued.push(build)
    },

    onBuildFinished({ serverId }) {
      this.status.current = this.status.current.filter(
        (currentBuild) => currentBuild.server.id !== serverId
      )
    },

    async update() {
      try {
        const { data } = await axios.get(route('admin.builds.status'))
        this.status = data
      } catch {
        //
      }

      Echo.private('game-builds').listen('GameBuildStarting', this.onBuildStarting)
      Echo.private('game-builds').listen('GameBuildQueuedStarting', this.onBuildQueuedStarting)
      Echo.private('game-builds').listen('GameBuildQueued', this.onBuildQueued)
      Echo.private('game-builds').listen('GameBuildFinished', this.onBuildFinished)

      this.firstLoad = false
    },
  },
}
</script>
