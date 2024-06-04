<template>
  <div :class="{ 'opacity-40': fadeOut }" :style="!hasCaption && 'margin-top: 2px;'">
    <div class="flex items-center">
      <div v-if="node.plan.delete || node.plan.edit" class="q-mr-xs flex items-center">
        <q-badge
          v-if="node.plan.delete"
          color="negative"
          text-color="dark"
          class="text-weight-bold"
          square
          >Delete</q-badge
        >
        <q-badge
          v-else-if="node.plan.edit"
          color="info"
          text-color="dark"
          class="text-weight-bold"
          >Edit</q-badge
        >
      </div>
      <div style="line-height: 1;">
        <template v-if="isBan">Ban</template>
        <template v-else>Connection Detail</template>
      </div>
    </div>
    <div v-if="hasCaption" class="text-caption opacity-80 q-mt-xs" style="line-height: 1.4;">
      <template v-if="isBan && node.plan.delete">
        This ban will be deleted because the root detail will be deleted.
      </template>
      <template v-else-if="node.plan.matched">
        <template v-if="node.plan.delete">
          This detail will be deleted because all existing connection fields match the lookup parameters.
        </template>
        <template v-else-if="node.plan.edit">
          This detail will be edited to remove the connection fields: {{ humanMatchedFields }}.
        </template>
      </template>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.q-badge {
  border-radius: 2px;
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
    }
  },
}
</script>
