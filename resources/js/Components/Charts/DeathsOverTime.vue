<template>
  <apexchart v-if="series" width="100%" :height="200" :options="chartOptions" :series="series" />
  <div v-if="!series[0].data.length" class="chart-no-data">No data found</div>
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
          id: 'deaths-over-time',
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
          type: 'datetime',
          categories: [],
          axisTicks: {
            show: false,
          },
          axisBorder: {
            color: '#333',
          },
          tooltip: {
            enabled: false,
          },
          labels: {
            datetimeUTC: false,
            format: 'h:mmtt',
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
          curve: 'smooth',
          lineCap: 'round',
          width: 0,
        },
        markers: {
          size: 0,
          strokeWidth: 0,
        },
        grid: {
          show: true,
          borderColor: '#333',
        },
        colors: ['#ffd125'],
        tooltip: {
          theme: 'gh',
          x: {
            format: 'h:mmtt'
          }
        },
      },
    }
  },

  created() {
    this.buildGraphData()
  },

  methods: {
    buildGraphData() {
      const dates = []
      const deaths = []
      // Group deaths by minute
      this.data.forEach((death) => {
        const prevDate = dates[dates.length - 1]
        if (!prevDate) {
          dates.push(death.created_at)
          deaths.push(1)
        } else {
          const diff = dayjs(death.created_at).diff(dayjs(prevDate), 'm')
          if (diff) {
            dates.push(death.created_at)
            deaths.push(1)
          } else {
            deaths[deaths.length - 1]++
          }
        }
      })

      this.chartOptions.xaxis.categories = dates
      this.series = [
        {
          name: 'Deaths',
          data: deaths,
        },
      ]

      this.chartOptions.yaxis.labels.show = !!deaths.length
    },
  },
}
</script>
