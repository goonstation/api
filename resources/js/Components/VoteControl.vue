<template>
  <div @click.prevent class="votes flex-inline items-center bg-grey-10">
    <q-btn
      @click.prevent="upVote"
      flat
      size="sm"
      padding="xs 6px"
      :color="hasVotedUp ? 'positive' : ''"
      :icon="ionArrowUp"
      :loading="loadingUp"
    />
    <transition :name="transitionType" mode="out-in">
      <span :key="normalizedVotes" class="votes-total q-px-xs text-sm">
        {{ normalizedVotes }}
      </span>
    </transition>
    <q-btn
      @click.prevent="downVote"
      flat
      size="sm"
      padding="xs 6px"
      :color="hasVotedDown ? 'negative' : ''"
      :icon="ionArrowDown"
      :loading="loadingDown"
    />
  </div>
</template>

<style lang="scss" scoped>
.votes {
  border-radius: 5px;
}

.votes-total {
  display: inline-block;
  line-height: 1;
  font-weight: bold;
}

.slide-up-enter-active,
.slide-up-leave-active,
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.25s ease-out;
}

.slide-up-enter-from {
  opacity: 0;
  transform: translateY(5px);
}

.slide-up-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}

.slide-down-enter-from {
  opacity: 0;
  transform: translateY(-5px);
}

.slide-down-leave-to {
  opacity: 0;
  transform: translateY(5px);
}
</style>

<script>
import { ionArrowUp, ionArrowDown } from '@quasar/extras/ionicons-v6'

export default {
  props: {
    voteableType: {
      type: String,
      required: true,
    },
    voteableId: {
      type: Number,
      required: true,
    },
    votes: {
      type: null,
      default: 0,
    },
    userVotes: {
      type: Array,
    },
  },

  setup() {
    return {
      ionArrowUp,
      ionArrowDown,
    }
  },

  data() {
    return {
      disabled: false,
      loadingUp: false,
      loadingDown: false,
      transitionType: 'slide-up'
    }
  },

  computed: {
    normalizedVotes() {
      return new Intl.NumberFormat('en', { notation: 'compact' }).format(
        this.votes || 0
      )
    },

    hasVotedUp() {
      return !!this.userVotes?.find((vote) => vote.value === 1)
    },

    hasVotedDown() {
      return !!this.userVotes?.find((vote) => vote.value === -1)
    },
  },

  methods: {
    async applyVote(direction) {
      this.disabled = true
      try {
        const response = await axios.post(route(`votes.${direction}`), {
          type: this.voteableType,
          id: this.voteableId,
        })
        this.transitionType = response.data.votes > this.votes ? 'slide-up' : 'slide-down'
        this.$emit('update:votes', response.data.votes)
        this.$emit('update:userVotes', response.data.user_votes)
      } catch (e) {
        //
      }
      this.disabled = false
    },

    async upVote() {
      if (this.disabled) return
      this.loadingUp = true
      await this.applyVote('up')
      this.loadingUp = false
    },

    async downVote() {
      if (this.disabled) return
      this.loadingDown = true
      await this.applyVote('down')
      this.loadingDown = false
    },
  },
}
</script>
