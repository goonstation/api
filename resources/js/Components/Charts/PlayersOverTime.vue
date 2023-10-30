<template>
  <apexchart v-if="series" width="100%" :height="400" :options="chartOptions" :series="series" />
  <div v-if="!Object.keys(data).length" class="chart-no-data">No data found</div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },

  data: () => {
    return {
      series: null,
      chartOptions: {
        chart: {
          id: 'player-connections-over-time',
          type: 'line',
          stacked: false,
          foreColor: '#fff',
          zoom: {
            enabled: false,
          },
          toolbar: {
            show: false,
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {
          type: 'datetime',
          axisTicks: {
            show: false,
          },
          axisBorder: {
            color: '#333'
          },
          tooltip: {
            enabled: false,
          },
        },
        yaxis: {
          min: 0,
          forceNiceScale: true,
          labels: {
            show: true
          }
        },
        stroke: {
          curve: 'straight',
          lineCap: 'round',
          width: 2,
        },
        markers: {
          size: 0,
          strokeWidth: 0,
        },
        grid: {
          show: true,
          borderColor: '#333',
          padding: {
            bottom: 10
          }
        },
        colors: ['#ffd125', '#24c024', '#e33434', '#5490ff'],
        tooltip: {
          theme: 'gh',
        },
      },
    }
  },

  methods: {
    buildGraphData() {
      const series = []

      for (const serverId in this.data) {
        const data = this.data[serverId]
        const seriesItem = {
          name: serverId,
          data: []
        }

        for (const item of data) {
          seriesItem.data.push({ x: item[0], y: item[1] })
        }

        series.push(seriesItem)
      }

      this.series = series
      this.chartOptions.yaxis.labels.show = !!series.length
    },
  },

  watch: {
    data: {
      immediate: true,
      handler(val) {
        this.buildGraphData()
      }
    }
  }
}
</script>
