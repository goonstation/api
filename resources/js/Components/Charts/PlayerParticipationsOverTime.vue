<template>
  <apexchart v-if="series" width="100%" :height="400" :options="chartOptions" :series="series" />
</template>

<script>
import dayjs from 'dayjs'

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
          id: 'player-connections-over-time',
          type: 'area',
          stacked: false,
          foreColor: '#fff',
          zoom: {
            enabled: false,
          },
          toolbar: {
            show: false,
          },
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'dark',
            shadeIntensity: 0.5,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0,
            stops: [0, 90, 100],
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {
          type: 'datetime',
          // categories: [],
          axisTicks: {
            show: false,
          },
          axisBorder: {
            color: '#333'
          },
          tooltip: {
            enabled: false,
          },
          // min: '2022-12-12'
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
      const players = []
      this.data.forEach((item) => {
        players.push({x: item[0], y: item[1]})
      })

      this.series = [
        {
          name: 'Players',
          data: players,
        },
      ]
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
