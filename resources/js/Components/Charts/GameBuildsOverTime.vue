<template>
  <apexchart v-if="series" width="100%" height="100%" :options="chartOptions" :series="series" />
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
          id: 'game-builds-over-time',
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
        fill: {
          opacity: 1,
        },
        xaxis: {
          type: 'datetime',
          axisTicks: {
            show: false,
          },
          axisBorder: {
            color: '#333',
          },
          tooltip: {
            enabled: false,
          },
        },
        yaxis: {
          min: 0,
          forceNiceScale: true,
          labels: {
            show: true,
          },
        },
        stroke: {
          curve: 'straight',
          lineCap: 'round',
          width: 2,
        },
        markers: {
          size: 3,
          strokeWidth: 0,
        },
        grid: {
          show: true,
          borderColor: '#333',
          padding: {
            bottom: 10,
          },
        },
        colors: ['#24c024', '#e33434', '#ffd125'],
        tooltip: {
          theme: 'gh',
        },
        legend: {
          show: false,
        },
      },
    }
  },

  methods: {
    buildGraphData() {
      const success = { name: 'Success', data: [] }
      const failed = { name: 'Failed', data: [] }
      const cancelled = { name: 'Cancelled', data: [] }

      for (const item of this.data) {
        success.data.push({ x: item.day, y: item.success })
        failed.data.push({ x: item.day, y: item.failed })
        cancelled.data.push({ x: item.day, y: item.cancelled })
      }

      this.series = [success, failed, cancelled]
    },
  },

  watch: {
    data: {
      immediate: true,
      handler() {
        this.buildGraphData()
      },
    },
  },
}
</script>
