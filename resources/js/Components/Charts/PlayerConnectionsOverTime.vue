<template>
  <div>
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
import { createTrend } from 'trendline'

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
      unixConnections: []
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
            enabled: false
          }
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

      // Group connections by day
      this.data.forEach((connection, idx) => {
        const unixMsCreatedAt = dayjs(connection.created_at).unix() * 1000
        this.unixConnections.push({
          ...connection,
          unix_created_at: unixMsCreatedAt,
        })

        const prevConnection = connections[connections.length - 1]
        if (!prevConnection) {
          connections.push([unixMsCreatedAt, 1])
          trendData.push({ x: idx, y: 1 })
        } else {
          const diff = dayjs(connection.created_at).diff(dayjs(prevConnection[0]), 'd')
          if (diff) {
            connections.push([unixMsCreatedAt, 1])
            trendData.push({ x: idx, y: 1 })
          } else {
            connections[connections.length - 1][1]++
            trendData[trendData.length - 1].y++
          }
        }
      })

      const trend = createTrend(trendData, 'x', 'y')
      const trendSeries = connections.map((item, idx) => [item[0], trend.calcY(idx)])

      this.series = [
        {
          name: 'Connections',
          data: connections,
          type: 'bar',
        },
        {
          name: 'Trend',
          data: trendSeries,
          type: 'line',
        },
      ]

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
