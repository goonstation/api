<template>
  <q-card flat>
    <q-card-section horizontal class="items-center q-pl-sm q-pr-lg">
      <q-card-section class="q-py-md q-pr-lg">
        <div class="players-title">Players online</div>
        <div class="players-subtitle">
          <q-icon :name="ionPeople" class="q-mr-xs" />
          {{ date }}
        </div>
        <div class="players-amount">{{ players }}</div>
      </q-card-section>
      <q-space />
      <div class="row justify-end col-6 q-py-sm">
        <player-trend-over-time
          v-if="trendData.length && trendData.length > 1"
          :data="trendData"
          @onPlayerHover="updateAmount"
        />
      </div>
    </q-card-section>
  </q-card>
</template>

<style lang="scss" scoped>
.players-title {
  font-size: 0.9em;
  font-weight: 600;
  letter-spacing: 0.5px;
  line-height: 1;
  margin-bottom: 5px;
}

.players-subtitle {
  font-size: 0.8em;
  letter-spacing: 0.5px;
  opacity: 0.8;
  line-height: 1;
  margin-bottom: 5px;
}

.players-amount {
  font-size: 1.8em;
  line-height: 1;
  font-weight: 800;
  letter-spacing: 4px;
}
</style>

<script>
import dayjs from 'dayjs'
import { ionPeople } from '@quasar/extras/ionicons-v6'
import PlayerTrendOverTime from '@/Components/Charts/PlayerTrendOverTime.vue'

export default {
  components: {
    PlayerTrendOverTime,
  },

  props: {
    data: {
      type: Array,
      required: true,
      default: () => [],
    },
  },

  setup() {
    return {
      ionPeople,
    }
  },

  data: () => {
    return {
      players: 0,
      date: null,
    }
  },

  computed: {
    trendData() {
      const res = []
      this.data.forEach((point, idx) => {
        const newPoint = { y: Math.round(parseInt(point.online)) }
        if (idx === this.data.length - 1) {
          newPoint.x = 'Right now'
        } else {
          newPoint.x = dayjs(point.date).format('DD MMM YYYY')
        }
        res.push(newPoint)
      })
      return res
    },
  },

  methods: {
    updateAmount({ date, players }) {
      this.date = date
      this.players = Math.round(players)
    },
  },

  watch: {
    trendData: {
      immediate: true,
      handler(val) {
        const latest = val[val.length - 1]
        if (!latest) return
        this.updateAmount({ date: latest.x, players: latest.y })
      }
    }
  }
}
</script>
