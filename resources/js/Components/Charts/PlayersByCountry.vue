<template>
  <apexchart v-if="series" width="100%" :options="chartOptions" :series="series" />
  <div v-else class="chart-no-data">No data found</div>
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
        labels: null,
        chart: {
          id: 'players-by-country',
          type: 'donut',
          foreColor: '#fff',
          zoom: {
            enabled: false,
          },
          toolbar: {
            show: false,
          },
        },
        theme: {
          monochrome: {
            enabled: true,
            color: '#ffd125',
            shadeTo: 'dark'
          },
        },
        dataLabels: {
          formatter: function (value, opts) {
            return opts.w.config.labels[opts.seriesIndex]
          },
        },
        stroke: {
          width: 1,
          colors: ['#0f0f0f'],
        },
        legend: {
          show: false,
        },
        tooltip: {
          theme: 'gh',
          enabled: false,
        },
        plotOptions: {
          pie: {
            donut: {
              size: '50%',
              labels: {
                show: true,
                value: {
                  formatter: function (value) {
                    return Math.round((parseFloat(value) + Number.EPSILON) * 100) / 100 + '%'
                  }
                },
              }
            }
          }
        },
        responsive: [
          {
            breakpoint: 650,
            options: {
              plotOptions: {
                pie: {
                  donut: {
                    labels: {
                      name: {
                        fontSize: '14px'
                      },
                      value: {
                        fontSize: '16px'
                      }
                    }
                  }
                }
              }
            }
          },
          {
            breakpoint: 400,
            options: {
              plotOptions: {
                pie: {
                  donut: {
                    labels: {
                      name: {
                        fontSize: '12px'
                      },
                      value: {
                        fontSize: '14px'
                      }
                    }
                  }
                }
              }
            }
          }
        ]
      },
    }
  },

  methods: {
    buildGraphData() {
      const labels = []
      const series = []
      this.data.forEach((item) => {
        labels.push(item[0])
        series.push(item[1])
      })
      this.chartOptions.labels = labels
      this.series = series
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
