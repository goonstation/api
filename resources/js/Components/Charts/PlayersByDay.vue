<template>
  <apexchart v-if="series" width="100%" :height="400" :options="chartOptions" :series="series" />
  <div v-if="!data.length" class="chart-no-data">No data found</div>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      required: true,
      default: () => [],
    },
  },

  data: () => {
    return {
      series: null,
      chartOptions: {
        chart: {
          id: 'players-by-day',
          type: 'bar',
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
            formatter: function (val) {
              return val.toFixed(0)
            },
          },
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
          borderColor: '#333'
        },
        colors: ['#ffd125'],
        tooltip: {
          theme: 'gh',
        },
      },
    }
  },

  methods: {
    buildGraphData() {
      const days = []
      this.data.forEach((item) => {
        days.push({x: item[0], y: item[1]})
      })

      this.series = [
        {
          name: 'Players',
          data: days,
        },
      ]

      this.chartOptions.yaxis.labels.show = !!days.length
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
