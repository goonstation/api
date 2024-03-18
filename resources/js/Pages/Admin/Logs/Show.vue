<template>
  <div class="flex-grow flex column">
    <div class="flex-grow flex column" :class="{ 'table-full': fullscreen }">
      <div class="table-top flex">
        <q-btn-dropdown
          class="q-ml-sm"
          label="Filters"
          menu-anchor="bottom start"
          menu-self="top start"
          square
          dense
        >
          <div class="q-pa-md">
            <div class="row q-col-gutter-md">
              <div class="col">
                <q-form @submit="search">
                  <q-input
                    v-model="searchInput"
                    class="q-mb-xs"
                    type="textarea"
                    placeholder="Term: Match must include&#10;-Term: Match must not include"
                    filled
                    dense
                  />
                  <div class="flex">
                    <q-btn
                      v-if="searchFilters.length"
                      @click="clearSearch"
                      color="grey"
                      text-color="dark"
                      size="sm"
                      >Clear Filters</q-btn
                    >
                    <q-space />
                    <q-btn type="submit" color="primary" text-color="dark" size="sm"
                      >Apply Filters</q-btn
                    >
                  </div>
                </q-form>
              </div>
              <div class="col">
                <div class="flex flex-wrap gap-xs-xs">
                  <div class="log-type-filter">
                    <q-checkbox v-model="logTypesAll" val="all" label="All" dense />
                  </div>
                  <template v-for="logType in logTypes">
                    <div class="log-type-filter" :class="`log-type-${logType.value}`">
                      <q-checkbox
                        v-model="logTypesToShow"
                        @update:model-value="filterLogs"
                        :val="logType.value"
                        :label="logType.label"
                        dense
                      />
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </q-btn-dropdown>
        <div class="flex items-center q-ml-md">Showing {{ $formats.number(logs.length) }} logs</div>
        <q-space />
        <q-btn square dense @click="fullscreen = !fullscreen" :icon="ionExpand" />
      </div>
      <div class="relative flex-grow">
        <q-virtual-scroll
          type="table"
          style="position: absolute; top: 0; left: 0; right: 0; bottom: 0"
          :virtual-scroll-item-size="48"
          :virtual-scroll-sticky-size-start="0"
          :virtual-scroll-sticky-size-end="32"
          :items="logs"
          flat
          dense
        >
          <template v-slot:before>
            <thead class="thead-sticky text-left">
              <tr>
                <th style="width: 0">Time</th>
                <th style="width: 0">Type</th>
                <th>Log</th>
              </tr>
            </thead>
            <tbody v-if="loading">
              <tr>
                <td colspan="100%" class="text-center">
                  <div class="q-pa-md">Loading logs...</div>
                </td>
              </tr>
            </tbody>
          </template>

          <template v-slot="{ item: row, index }">
            <log-entry :key="index" :log="row" />
          </template>
        </q-virtual-scroll>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.table-full {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 5999;
}

.table-top {
  background: black;
  padding: 5px;
}

.log-type-filter {
  display: inline-block;
  background: grey;
  border-radius: 3px;
  padding: 3px 5px;
  line-height: 1;
}

.thead-sticky tr > * {
  position: sticky;
  opacity: 1;
  z-index: 1;
  background: black;
}

.thead-sticky tr:last-child > * {
  top: 0;
}
</style>

<style lang="scss">
.log-type-label {
  background: #5f5f5f;
}

.log-type-filter,
.log-type-row {
  &.log-type-ahelp {
    background: #4a4aa7;
  }
  .log-type-label-ahelp {
    background: #6e6eff;
  }
  &.log-type-admin {
    background: #2c5f79;
  }
  .log-type-label-admin {
    background: #3e97c3;
  }
  &.log-type-bombing {
    background: #575757;
  }
  .log-type-label-bombing {
    background: #979797;
  }
  &.log-type-chemistry {
    background: #c56504;
  }
  .log-type-label-chemistry {
    background: #ff8000;
  }
  &.log-type-debug {
    background: #6b5731;
  }
  .log-type-label-debug {
    background: #a9884a;
  }
  &.log-type-diary {
    background: #8f6031;
  }
  .log-type-label-diary {
    background: #d38c45;
  }
  &.log-type-ooc {
    background: #5252c5;
  }
  .log-type-label-ooc {
    background: #6b6bff;
  }
  &.log-type-pdamsg {
    background: #779144;
  }
  .log-type-label-pdamsg {
    background: #9cc747;
  }
  &.log-type-say {
    background: #5c5ca9;
  }
  .log-type-label-say {
    background: #8888f7;
  }
  &.log-type-combat {
    background: #9b2424;
  }
  .log-type-label-combat {
    background: #e93636;
  }
  &.log-type-pathology {
    background: #2a7f2a;
  }
  .log-type-label-pathology {
    background: #36c336;
  }
  &.log-type-whisper {
    background: #6060a1;
  }
  .log-type-label-whisper {
    background: #8888d5;
  }
}
</style>

<script>
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import LogEntry from './Partials/LogEntry.vue'
import { ionExpand } from '@quasar/extras/ionicons-v6'

export default {
  components: {
    LogEntry,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Logs' }, () => page),

  setup() {
    return {
      ionExpand,
    }
  },

  props: {
    round: Object,
  },

  data() {
    return {
      allLogs: [],
      loading: true,
      logs: [],
      searchInput: '',
      searchFilters: [],
      logTypesToShow: [],
      logTypes: [],
      fullscreen: false,
    }
  },

  computed: {
    logTypesAll: {
      get() {
        return this.logTypes.length === this.logTypesToShow.length
      },
      set(val) {
        const newLogTypes = []
        if (val) {
          for (const logType of this.logTypes) {
            newLogTypes.push(logType.value)
          }
        }
        this.logTypesToShow = newLogTypes
        this.filterLogs()
      },
    },
  },

  created() {
    this.getLogs()
  },

  methods: {
    async getLogs() {
      try {
        const response = await axios.get(route('admin.logs.get-logs', { gameRound: this.round.id }))
        this.allLogs = response.data

        const logTypes = [...new Set(this.allLogs.map((log) => log.type))].sort()
        this.logTypes = logTypes.map((logType) => {
          return {
            label: logType,
            value: logType,
          }
        })
        this.logTypesToShow = logTypes

        this.filterLogs()
      } catch (e) {
        console.log(e)
      }

      this.loading = false
    },

    filterLogs() {
      this.logs = this.allLogs.filter((log) => {
        let valid = this.logTypesToShow.includes(log.type)
        if (valid && this.searchFilters.length) {
          const logMessage = (log.source + ' ' + log.message).toLowerCase()
          for (const filter of this.searchFilters) {
            if (filter.negative && logMessage.includes(filter.term)) {
              valid = false
              break
            }
            if (!filter.negative && !logMessage.includes(filter.term)) {
              valid = false
            }
          }
        }
        return valid
      })
    },

    search() {
      const terms = this.searchInput.split('\n')
      const filters = []
      for (let term of terms) {
        let negative = false
        if (term.startsWith('-')) {
          term = term.substring(1)
          negative = true
        }
        if (term.length < 3) continue
        filters.push({
          term: term.toLowerCase(),
          negative,
        })
      }
      this.searchFilters = filters
      this.filterLogs()
    },

    clearSearch() {
      this.searchInput = ''
      this.searchFilters = []
      this.filterLogs()
    },
  },
}
</script>
