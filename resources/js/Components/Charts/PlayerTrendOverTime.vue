<template>
  <apexchart
    v-if="series"
    :width="200"
    :height="80"
    :options="chartOptions"
    :series="series"
    @markerClick="onClick"
    @mouseMove="onMouseMove"
    @mouseLeave="onMouseLeave"
  />
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      required: true,
      default: () => []
    }
  },

  data: () => {
    return {
      series: null,
      chartOptions: {
        chart: {
          id: 'player-trend-over-time',
          type: 'line',
          sparkline: {
            enabled: true,
          },
        },
        xaxis: {
          type: 'category',
        },
        stroke: {
          curve: 'smooth',
          lineCap: 'round',
          width: 2,
        },
        markers: {
          size: 0,
          strokeWidth: 0,
        },
        grid: {
          padding: {
            top: 10,
            bottom: 10,
            left: 10,
            right: 10,
          },
        },
        colors: ['#ffd125'],
        tooltip: {
          theme: 'hidden',
        },
      },
    }
  },

  methods: {
    onClick(e, ctx, cfg) {
      if (cfg.dataPointIndex === -1) return
      let dataPoint = cfg.w.config.series[cfg.seriesIndex]?.data[cfg.dataPointIndex]
      if (!dataPoint) {
        dataPoint = cfg.w.config.series[0].data[cfg.w.config.series[0].data.length - 1]
      }
      this.$emit('onPlayerHover', { date: dataPoint.x, players: dataPoint.y })
    },

    onMouseMove(e, ctx, cfg) {
      if (cfg.dataPointIndex === -1) return
      let dataPoint = cfg.config.series[cfg.seriesIndex]?.data[cfg.dataPointIndex]
      if (!dataPoint) {
        dataPoint = cfg.config.series[0].data[cfg.config.series[0].data.length - 1]
      }
      this.$emit('onPlayerHover', { date: dataPoint.x, players: dataPoint.y })
    },

    onMouseLeave() {
      const dataPoint = this.series[0].data[this.series[0].data.length - 1]
      this.$emit('onPlayerHover', {
        date: dataPoint.x,
        players: dataPoint.y,
      })
    },
  },

  watch: {
    data: {
      immediate: true,
      deep: true,
      handler(val) {
        this.series = [{
          data: val
        }]
      }
    }
  }
}
</script>
