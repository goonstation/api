<template>
  <div :class="{ 'opacity-40': fadeOut }" :style="!hasCaption && 'margin-top: 2px;'">
    <div class="flex items-center q-mb-xs">
      <div v-if="node.plan.delete || node.plan.edit" class="q-mr-xs flex items-center">
        <q-badge
          v-if="node.plan.delete"
          color="negative"
          text-color="dark"
          class="text-weight-bold"
          square
          >Delete</q-badge
        >
        <q-badge v-else-if="node.plan.edit" color="info" text-color="dark" class="text-weight-bold"
          >Edit</q-badge
        >
      </div>
      <div style="line-height: 1">
        <template v-if="isBan">Ban</template>
        <template v-else>Connection Detail</template>
      </div>
    </div>
    <div class="details">
      <q-chip v-if="ckey" :class="{ matched: this.node.plan?.matched?.ckey }" size="12px" square>
        <q-avatar color="grey" text-color="black">Ckey</q-avatar>
        {{ ckey }}
      </q-chip>
      <q-chip
        v-if="compId"
        :class="{ matched: this.node.plan?.matched?.comp_id }"
        size="12px"
        square
      >
        <q-avatar color="grey" text-color="black">CID</q-avatar>
        {{ compId }}
      </q-chip>
      <q-chip v-if="ip" :class="{ matched: this.node.plan?.matched?.ip }" size="12px" square>
        <q-avatar color="grey" text-color="black">IP</q-avatar>
        {{ ip }}
      </q-chip>
    </div>
    <div v-if="hasCaption" class="text-caption opacity-80 q-mt-xs" style="line-height: 1.4">
      <template v-if="isBan && node.plan.delete">
        This ban will be deleted because the root detail will be deleted.
      </template>
      <template v-else-if="node.plan.matched">
        <template v-if="node.plan.delete">
          This detail will be deleted because all existing fields match the lookup
          parameters.
        </template>
        <template v-else-if="node.plan.edit">
          This detail will be edited to remove the fields: {{ humanMatchedFields }}.
        </template>
      </template>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.q-badge {
  border-radius: 2px;
}

.details {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;

  .q-chip {
    margin: 0;
    height: 16px;
    padding-right: 4px;
    border-radius: 2px;
  }

  .matched {
    background: darkorange;
    color: black;
  }

  .q-avatar {
    width: auto;
    height: 16px;
    padding-left: 5px;
    padding-right: 5px;
    border-top-left-radius: 2px;
    border-bottom-left-radius: 2px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    font-size: 24px;
  }
}
</style>

<script>
export default {
  props: {
    node: {
      type: Object,
      required: true,
    },
    ticked: {
      type: Array,
    },
    isTicked: {
      type: Boolean,
    },
  },

  computed: {
    isBan() {
      return !!this.node.children.length
    },

    fadeOut() {
      if (this.isTicked) return false
      if (this.isBan) {
        for (const child of this.node.children) {
          if (this.ticked.includes(child.id)) {
            return false
          }
        }
      }
      return !this.isTicked
    },

    hasCaption() {
      return (this.isBan && this.node.plan.delete) || this.node.plan.matched
    },

    humanMatchedFields() {
      if (!this.node.plan.matched) return ''
      const keys = []
      for (const key in this.node.plan.matched) {
        if (this.node.plan.matched[key]) keys.push(key)
      }
      return keys.join(', ')
    },

    ckey() {
      return this.isBan ? this.node.plan.item.original_ban_detail.ckey : this.node.plan.item.ckey
    },

    compId() {
      return this.isBan
        ? this.node.plan.item.original_ban_detail.comp_id
        : this.node.plan.item.comp_id
    },

    ip() {
      return this.isBan ? this.node.plan.item.original_ban_detail.ip : this.node.plan.item.ip
    },
  },
}
</script>
