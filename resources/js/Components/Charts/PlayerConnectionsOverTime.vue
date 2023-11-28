<template>
  <div class="relative">
    <apexchart
      ref="chart"
      v-if="series"
      width="100%"
      :height="400"
      :options="chartOptions"
      :series="series"
      @selection="selection"
      @beforeZoom="clearSelection"
      @beforeResetZoom="clearSelection"
    />
    <div v-if="!data.length" class="chart-no-data">No data found</div>
  </div>
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

  setup() {
    return {
      series: null,
      unixConnections: [],
    }
  },

  data: () => {
    return {
      chartOptions: {
        chart: {
          id: 'player-connections-over-time',
          type: 'line',
          foreColor: '#fff',
          zoom: {
            enabled: true,
          },
          toolbar: {
            show: true,
            tools: {
              download: false,
              zoom: true,
              selection: true,
              reset: true,
            },
            autoSelected: 'selection',
          },
          selection: {
            enabled: true,
            type: 'x',
            fill: {
              color: '#ffd125',
              opacity: 0.2,
            },
          },
          animations: {
            enabled: false,
          },
        },
        fill: {
          opacity: 1,
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
            color: '#333',
          },
          tooltip: {
            enabled: false,
          },
        },
        yaxis: [
          {
            min: 0,
            forceNiceScale: true,
            labels: {
              formatter: function (val) {
                return val.toFixed(0)
              },
            },
          },
          {
            opposite: true,
            labels: {
              show: false,
            },
            min: 0,
            max: (max) => max + 1,
          },
        ],
        stroke: {
          curve: 'straight',
          lineCap: 'round',
          width: [1, 2],
        },
        markers: {
          size: 0,
          strokeWidth: 0,
        },
        grid: {
          show: true,
          borderColor: '#333',
        },
        colors: ['#ffd125', '#8f7b33'],
        tooltip: {
          theme: 'gh',
          x: {
            format: 'dd MMM yyyy',
          },
          y: [
            {
              formatter: function (y) {
                return y
              },
            },
            {
              formatter: function (y) {
                if (typeof y !== 'undefined') {
                  return y.toFixed(2)
                }
                return y
              },
            },
          ],
        },
      },
    }
  },

  methods: {
    buildGraphData() {
      const connections = []
      const trendData = []
      this.unixConnections = []

      let currentDate = dayjs(this.data[0].created_at).startOf('day')
      const endDate = dayjs(this.data[this.data.length - 1].created_at).endOf('day')
      let connectionIdx = 0
      // let currentYear = currentDate.startOf('year')
      let nextYear = currentDate.endOf('year')

      const test = []
      const trendTest = [[((currentDate.startOf('year').unix() + nextYear.unix()) / 2) * 1000, 0]]
      while (currentDate <= endDate) {
        const endOfDay = currentDate.endOf('day')

        let connections = 0
        let connection = this.data[connectionIdx]
        let connectionCreated = dayjs(connection.created_at)
        while (connection && connectionCreated <= endOfDay) {
          connections++

          connectionIdx++
          connection = this.data[connectionIdx]
          if (connection) {
            connectionCreated = dayjs(connection.created_at)
          }
        }

        const startOfDayMs = currentDate.unix() * 1000
        test.push([startOfDayMs, connections])

        if (currentDate <= nextYear) {
          trendTest[trendTest.length - 1][1] += connections
        } else {
          nextYear = nextYear.add(1, 'year')
          const startOfYear = currentDate.startOf('year').unix()
          const endOfYear = nextYear.unix()
          trendTest.push([((startOfYear + endOfYear) / 2) * 1000, connections])
        }

        currentDate = currentDate.add(1, 'day')
      }
      console.log(test)
      console.log(trendTest)

      // Group connections by day
      this.data.forEach((connection, idx) => {
        const connectionDate = dayjs(connection.created_at)
        const unixMsCreatedAt = connectionDate.unix() * 1000
        this.unixConnections.push({
          ...connection,
          unix_created_at: unixMsCreatedAt,
        })

        const prevConnection = connections[connections.length - 1]
        if (!prevConnection) {
          connections.push([unixMsCreatedAt, 1])
        } else {
          const diff = connectionDate.diff(dayjs(prevConnection[0]), 'd')
          if (diff) {
            connections.push([unixMsCreatedAt, 1])
          } else {
            connections[connections.length - 1][1]++
          }
        }

        const prevTrendItem = trendData[trendData.length - 1]
        const trendConnectionFormat = connectionDate.format('YYYY')
        if (prevTrendItem && prevTrendItem.format === trendConnectionFormat) {
          trendData[trendData.length - 1].connections++
        } else {
          const startOfDate = connectionDate.startOf('year').unix()
          const endOfDate = connectionDate.endOf('year').unix()
          trendData.push({
            format: trendConnectionFormat,
            midPoint: ((startOfDate + endOfDate) / 2) * 1000,
            connections: 1,
            daysInMonth: connectionDate.daysInMonth(),
          })

          if (prevTrendItem) {
            prevTrendItem.connections = prevTrendItem.connections / prevTrendItem.daysInMonth
          }
        }
      })

      const trendSeries = []
      trendData[trendData.length - 1].connections =
        trendData[trendData.length - 1].connections / trendData[trendData.length - 1].daysInMonth
      for (const trendItem of trendData) {
        trendSeries.push([trendItem.midPoint, trendItem.connections])
      }

      this.series = [
        {
          name: 'Connections',
          data: test,
          type: 'bar',
        },
      ]

      if (trendSeries.length > 1) {
        this.series.push({
          name: 'Yearly Average',
          data: trendTest,
          type: 'line',
        })
      }

      this.chartOptions.yaxis[0].labels.show = !!connections.length
    },

    selection(chartContext, { xaxis }) {
      const selectedConnections = []
      for (const connection of this.unixConnections) {
        if (connection.unix_created_at >= xaxis.min && connection.unix_created_at <= xaxis.max) {
          selectedConnections.push(connection)
        }
      }
      this.$emit('selected-connections', selectedConnections)
    },

    clearSelection() {
      this.$refs.chart.updateOptions({
        chart: {
          selection: {
            xaxis: {
              min: undefined,
              max: undefined,
            },
          },
        },
      })
      this.$emit('selected-connections', [])
    },
  },

  watch: {
    data: {
      immediate: true,
      handler(val) {
        this.buildGraphData()
      },
    },
  },
}
</script>
