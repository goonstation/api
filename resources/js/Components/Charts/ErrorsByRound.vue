<template>
  <apexchart
    v-if="series"
    ref="chart"
    width="100%"
    :height="200"
    :options="chartOptions"
    :series="series"
    @markerClick="onSelect"
  />
  <div v-if="!series[0].data.length" class="chart-no-data">No data found</div>
</template>

<script>
export default {
  emits: ['on-round-select'],

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
        plotOptions: {
          bar: {
            distributed: true,
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
          labels: {
            show: false,
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
        legend: {
          show: false,
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
          },
        },
        tooltip: {
          theme: 'gh',
          shared: true,
          intersect: false,
          x: {
            formatter: (value, { dataPointIndex, w }) => {
              const serverName = w.config._serverNames[dataPointIndex]
              return serverName + '<br>Round #' + value
            },
          },
          y: {
            formatter: (value) => {
              return value
            },
          },
        },
        _serverNames: [],
      },
    }
  },

  methods: {
    buildGraphData() {
      let groups = {}
      for (const error of this.data) {
        for (const roundId in error.round_error_counts) {
          const roundError = error.round_error_counts[roundId]

          let group = groups[roundId]
          if (!group) {
            group = {
              count: 0,
              name: this.$helpers.serverIdToFriendlyName(roundError.server_id, true),
              color: this.$helpers.getChartColorForServer(roundError.server_id),
            }
          }

          group.count += roundError.count
          groups[roundId] = group
        }
      }

      groups = Object.keys(groups)
        .sort()
        .reduce((obj, key) => {
          obj[key] = groups[key]
          return obj
        }, {})

      const roundIds = []
      const errors = []
      const meta = []
      const colors = []

      for (const roundId in groups) {
        const round = groups[roundId]
        roundIds.push(roundId)
        errors.push(round.count)
        meta.push(round.name)
        colors.push(round.color)
      }

      this.chartOptions.colors = colors
      this.chartOptions.xaxis.categories = roundIds
      this.chartOptions.yaxis.labels.show = !!errors.length
      this.chartOptions._serverNames = meta

      this.series = [
        {
          name: 'Errors',
          data: errors,
        },
      ]

      if (this.$refs.chart) {
        this.$refs.chart.updateOptions({
          colors,
          _serverNames: meta,
          xaxis: {
            categories: roundIds,
          },
          yaxis: {
            labels: {
              show: !!errors.length,
            },
          },
        })
      }
    },

    onSelect(event, chartContext, { dataPointIndex }) {
      this.$emit('on-round-select', chartContext.w.config.xaxis.categories[dataPointIndex])
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
