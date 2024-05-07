<template>
  <apexchart v-if="series" ref="chart" width="100%" :height="200" :options="chartOptions" :series="series" />
  <div v-if="!series[0].data.length" class="chart-no-data">No data found</div>
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
          id: 'errors-by-round',
          type: 'bar',
          stacked: false,
          foreColor: '#fff',
          parentHeightOffset: 0,
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
          padding: {
            top: -15,
            bottom: 0,
          }
        },
        tooltip: {
          theme: 'gh',
          x: {
            formatter: (value, { seriesIndex, dataPointIndex, w })  => {
              const serverName = w.config.series[seriesIndex]._meta[dataPointIndex]
              return serverName + '<br>Round #' + value
            }
          },
          y: {
            formatter: (value) => {
              return value
            }
          }
        },
      },
    }
  },

  methods: {
    buildGraphData() {
      const roundIds = []
      const errors = []
      const meta = []
      const colors = []

      this.data.forEach((round) => {
        if (round.errors_count > 0) {
          roundIds.push(round.id)
          errors.push(round.errors_count)
          meta.push(this.$helpers.serverIdToFriendlyName(round.server_id, true))
          colors.push(this.$helpers.getChartColorForServer(round.server_id))
        }
      })

      this.chartOptions.colors = colors
      this.chartOptions.xaxis.categories = roundIds
      this.series = [
        {
          name: 'Errors',
          data: errors,
          _meta: meta
        },
      ]

      this.chartOptions.yaxis.labels.show = !!errors.length

      if (this.$refs.chart) {
        this.$refs.chart.updateOptions({
          colors,
          xaxis: {
            categories: roundIds
          },
          yaxis: {
            labels: {
              show: !!errors.length
            }
          }
        })
      }
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
