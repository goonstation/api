<template>
  <div class="check">
    <div :class="statusClass" class="check__status">
      <q-icon :name="statusIcon" size="20px" />
    </div>
    <div class="check__details">
      <div class="check__label">{{ check.label }}</div>
      <div v-if="check.shortSummary" class="check__summary">{{ check.shortSummary }}</div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.check {
  display: flex;
  flex: 1 1 auto;
  align-items: center;
  padding: 12px 15px 12px 10px;
  border-radius: 4px;
  gap: 5px;
  background: $dark;
}

.check__details {
  font-size: 12px;
  line-height: 1;

  .check__label {
    font-weight: 500;
  }

  .check__summary {
    margin-top: 3px;
    font-size: 11px;
    color: rgba(255, 255, 255, 0.75);
  }
}

.check__status {
  margin-right: 5px;
  padding: 5px;
  background: change-color($color: grey, $alpha: 0.3);
  border-radius: 50%;
  line-height: 1;

  .q-icon {
    opacity: 0.75;
  }

  &--success {
    background: change-color($color: $positive, $alpha: 0.25);
    color: $positive;
  }

  &--error {
    background: change-color($color: $negative, $alpha: 0.25);
    color: $negative;
  }
}
</style>

<script>
import { ionAlertCircle, ionCheckmarkCircle, ionCloseCircle } from '@quasar/extras/ionicons-v6'

export default {
  props: {
    check: {
      type: Object,
      required: true,
    },
  },

  computed: {
    success() {
      return this.check.status === 'ok'
    },

    noneFound() {
      return this.check.status === 'none'
    },

    statusClass() {
      let className = 'check__status--'
      if (this.noneFound) className += 'none'
      else if (this.success) className += 'success'
      else className += 'error'
      return className
    },

    statusIcon() {
      if (this.noneFound) return ionAlertCircle
      else if (this.success) return ionCheckmarkCircle
      return ionCloseCircle
    },
  },
}
</script>
