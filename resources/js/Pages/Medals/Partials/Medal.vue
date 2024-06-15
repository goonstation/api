<template>
  <div class="medal">
    <div class="medal__top">
      <medal-thumbnail :medal="medal" />
      <div>
        <div class="text-primary text-bold">{{ medal.title }}</div>
        <div class="text-sm opacity-80">{{ medal.description }}</div>
      </div>
    </div>
    <div v-if="medal.hasOwnProperty('earned_count') && !hideEarned" class="medal__bottom">
      <div class="text-sm opacity-80">
        Earned by
        {{ medal.earned_count }}
        player<template v-if="medal.earned_count !== 1">s</template>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.medal {
  display: flex;
  flex-direction: column;
  background: $dark;
  border-radius: 5px;
  margin-bottom: 0.5rem;
  overflow: hidden;

  &__top {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    padding: 10px;
  }

  &__bottom {
    padding: 5px 15px;
    background: rgba(255, 255, 255, 0.05);
  }
}
</style>

<script>
import MedalThumbnail from '@/Components/MedalThumbnail.vue'

export default {
  components: {
    MedalThumbnail,
  },

  props: {
    medal: Object,
    hideEarned: Boolean,
  }
}
</script>
