<template>
  <q-splitter v-model="splitter">
    <template v-slot:before>
      <q-tree
        v-model:ticked="ticked"
        v-model:selected="selected"
        :nodes="nodes"
        @update:selected="onSelect"
        class="q-pa-sm"
        node-key="id"
        tick-strategy="leaf"
        default-expand-all
      >
        <template v-slot:default-header="prop">
          <ban-removal-plan-node :node="prop.node" :ticked="ticked" :is-ticked="prop.ticked" />
        </template>
      </q-tree>
    </template>
    <template v-slot:after>
      <div v-if="selectedBan || selectedDetail" class="q-pa-md">
        <ban-removal-plan-selected-ban v-if="!selectedDetail" :ban="selectedBan" />
        <ban-removal-plan-selected-detail v-else :ban="selectedBan" :detail="selectedDetail" />
      </div>
      <q-banner
        v-else
        class="bg-grey-10 q-py-sm q-px-md q-ma-md"
        dense
      >
        <template v-slot:avatar>
          <q-icon :name="ionInformationCircleOutline" color="primary" size="md" class="q-mt-xs" />
        </template>
        <div>Select an item from the review plan to view more details.</div>
      </q-banner>
    </template>
  </q-splitter>
</template>

<style lang="scss" scoped>
:deep(.q-tree .q-tree__node-header) {
  align-items: flex-start;
}
</style>

<script>
import { ionInformationCircleOutline } from '@quasar/extras/ionicons-v6'
import BanRemovalPlanNode from './BanRemovalPlanNode.vue'
import BanRemovalPlanSelectedBan from './BanRemovalPlanSelectedBan.vue'
import BanRemovalPlanSelectedDetail from './BanRemovalPlanSelectedDetail.vue'

export default {
  components: {
    BanRemovalPlanNode,
    BanRemovalPlanSelectedBan,
    BanRemovalPlanSelectedDetail,
  },

  props: {
    bans: {
      type: Object,
      required: true,
    },
    matchingFields: {
      type: Object,
      required: true,
    },
  },

  setup() {
    return {
      ionInformationCircleOutline,
    }
  },

  data: () => {
    return {
      splitter: 50,
      nodes: [],
      ticked: [],
      selected: null,
      selectedBan: null,
      selectedDetail: null,
    }
  },

  computed: {
    plan() {
      const data = {
        deleteBans: [],
        deleteDetails: [],
        editDetails: [],
      }

      for (const ban of this.bans) {
        let detailsToEdit = []
        let detailsToDelete = []
        let deletingOriginal = false

        for (const detail of ban.details) {
          const isOriginal = detail.id === ban.original_ban_detail.id
          const willHave = {
            ckey: !!detail.ckey,
            comp_id: !!detail.comp_id,
            ip: !!detail.up,
          }

          if (this.matchingFields.ckey === detail.ckey) willHave.ckey = false
          if (this.matchingFields.comp_id === detail.comp_id) willHave.comp_id = false
          if (this.matchingFields.ip === detail.ip) willHave.ip = false

          if (Object.values(willHave).every((v) => v === false)) {
            if (isOriginal) {
              deletingOriginal = detail.id
              break
            } else {
              detailsToDelete.push(detail.id)
            }
          } else {
            detailsToEdit.push(detail.id)
          }
        }

        if (deletingOriginal || detailsToDelete === ban.details_count) {
          data.deleteBans.push(ban.id)
          data.deleteDetails.push(deletingOriginal)
        } else {
          data.deleteDetails = data.deleteDetails.concat(detailsToDelete)
          data.editDetails = data.editDetails.concat(detailsToEdit)
        }
      }

      return data
    },
  },

  watch: {
    plan: {
      immediate: true,
      handler() {
        this.buildNodes()
        this.buildTicked()
      },
    },

    ticked: {
      immediate: true,
      handler(tickedDetails) {
        const data = { deleting: [], editing: [] }

        for (const detailId of tickedDetails) {
          if (this.plan.deleteDetails.includes(detailId)) data.deleting.push(detailId)
          else if (this.plan.editDetails.includes(detailId)) data.editing.push(detailId)
        }

        this.$emit('details-updated', data)
      },
    },
  },

  methods: {
    buildNode(item, editing = false, deleting = false) {
      const node = {
        id: item.id,
        children: [],
        selectable: true,
        plan: {
          item,
          edit: editing,
          delete: deleting,
        },
      }

      if (!item.details_count) {
        node.plan.matched = {
          ckey: item.ckey === this.matchingFields.ckey,
          comp_id: item.comp_id === this.matchingFields.comp_id,
          ip: item.ip === this.matchingFields.ip,
        }
      }

      return node
    },

    buildNodes() {
      const nodes = []

      for (const ban of this.bans) {
        const banNode = this.buildNode(ban)

        if (this.plan.deleteBans.includes(ban.id)) {
          const originalDetail = ban.details.find(
            (detail) => detail.id === ban.original_ban_detail.id
          )
          banNode.plan.delete = true
          banNode.children.push(this.buildNode(originalDetail, false, true))
        } else {
          for (const detail of ban.details) {
            banNode.children.push(
              this.buildNode(
                detail,
                this.plan.editDetails.includes(detail.id),
                this.plan.deleteDetails.includes(detail.id)
              )
            )
          }
        }

        nodes.push(banNode)
      }

      this.nodes = nodes
    },

    buildTicked() {
      this.ticked = this.plan.deleteDetails.concat(this.plan.editDetails)
    },

    onSelect(id) {
      this.selectedBan = null
      this.selectedDetail = null

      if (!id) return

      for (const node of this.nodes) {
        if (node.id === id) {
          this.selectedBan = node
          break
        }

        for (const detailNode of node.children) {
          if (detailNode.id === id) {
            this.selectedBan = node
            this.selectedDetail = detailNode
            return
          }
        }
      }
    },
  },
}
</script>
