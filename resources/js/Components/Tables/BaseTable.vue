<template>
  <div>
    <q-table
      ref="tableRef"
      v-bind="$attrs"
      v-model:pagination="_pagination"
      v-model:selected="selected"
      :rows="rows"
      :columns="_columns"
      :loading="loading"
      :rows-per-page-options="[3, 5, 7, 10, 15, 20, 25, 30, 50]"
      :visible-columns="visibleColumns"
      :selection="selection"
      row-key="id"
      separator="none"
      binary-state-sort
      @request="onRequest"
    >
      <template v-for="(_, name) in $slots" v-slot:[name]="slotData">
        <slot :name="name" v-bind="slotData" />
      </template>

      <template v-slot:top v-if="gridTopHasContent">
        <div class="flex full-width bg-dark q-pa-md rounded-borders items-start no-wrap">
          <div v-if="showGridFilters" class="gh-grid-filters flex items-start gap-xs-sm">
            <!-- sort button/menu -->
            <q-btn color="grey-9" class="text-sm" padding="xs sm" dense no-caps unelevated>
              <q-icon :name="ionSwapVertical" size="xs" class="q-mr-sm" /> {{ sortedByLabel }}
              <q-menu :offset="[0, 10]">
                <q-list style="min-width: 100px">
                  <q-item>
                    <q-toggle
                      v-model="_pagination.descending"
                      :label="_pagination.descending ? 'Descending' : 'Ascending'"
                      @update:model-value="onSortChange({ descending: $event })"
                    />
                  </q-item>
                  <q-separator />
                  <q-item class="column">
                    <template v-for="column in columns">
                      <div v-if="column.sortable">
                        <q-radio
                          v-model="_pagination.sortBy"
                          :val="column.name"
                          :label="column.label"
                          @update:model-value="onSortChange({ column: $event })"
                          size="sm"
                        />
                      </div>
                    </template>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>

            <template v-for="(filter, name) in filters">
              <grid-header-filter
                v-if="filter"
                :column="columns.find((col) => col.name === name)"
                :filter="filter"
                @update="onFilterInput(name, $event)"
                @clear="filters[name] = null"
              >
              </grid-header-filter>
            </template>

            <q-btn class="text-sm" color="grey-9" padding="xs sm" :icon="ionAdd" dense unelevated>
              <q-tooltip anchor="center right" self="center left"> Add a filter </q-tooltip>
              <q-menu :offset="[0, 10]">
                <q-markup-table class="q-py-sm" separator="none" flat dense>
                  <tbody>
                    <template v-for="col in columns">
                      <tr v-if="col.filterable !== false">
                        <td style="width: 1px">
                          <q-chip color="grey-9" square>{{ col.label }}</q-chip>
                        </td>
                        <td>
                          <table-filter
                            :model-value="filters[col.name]"
                            @update:modelValue="onFilterInput(col.name, $event)"
                            @clear="filters[col.name] = null"
                            :filter-type="col.filter?.type || 'text'"
                          />
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </q-markup-table>
                <q-separator />
                <div class="row q-pa-sm">
                  <q-space />
                  <q-btn v-close-popup>Close</q-btn>
                </div>
              </q-menu>
            </q-btn>
          </div>

          <q-space />

          <div class="flex items-center gap-xs-sm">
            <slot name="header-right" />

            <q-btn
              v-if="routes.create"
              @click="router.visit(getRoute(routes.create))"
              color="primary"
              text-color="dark"
            >
              {{ createButtonText }}
            </q-btn>
          </div>

          <q-btn :icon="ionSettings" class="q-ml-md" dense unelevated>
            <q-tooltip>Table Settings</q-tooltip>
            <q-menu :offset="[0, 10]">
              <q-markup-table class="q-py-none" flat dense>
                <tbody>
                  <tr v-if="hasTimestamps && !noTimestampToggle">
                    <td>Toggle Timestamps</td>
                    <td>
                      <q-toggle v-model="showTimestamps" :icon="ionCalendar" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-right">
                      <q-btn color="primary" text-color="dark" class="q-my-sm" @click="reset">
                        Reset
                      </q-btn>
                    </td>
                  </tr>
                </tbody>
              </q-markup-table>
            </q-menu>
          </q-btn>
        </div>
      </template>

      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-if="canSelect">
            <q-checkbox v-model="props.selected" dense />
          </q-th>
          <q-th v-if="hasActions" class="q-table--col-auto-width" />
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.label }}
          </q-th>
        </q-tr>
        <q-tr no-hover>
          <q-th v-if="canSelect"></q-th>
          <q-th v-if="hasActions" class="q-table--col-auto-width" />
          <q-th v-for="col in props.cols" :key="col.name">
            <table-filter
              v-if="col.filterable !== false"
              :model-value="filters[col.name]"
              @update:modelValue="onFilterInput(col.name, $event)"
              @clear="filters[col.name] = null"
              :filter-type="col.filter?.type || 'text'"
            />
          </q-th>
        </q-tr>
      </template>

      <!-- <template v-slot:header-cell="props">
        <q-th :props="props">
          {{ props.col.label }}
          <table-filter
            v-if="props.col.filterable !== false"
            :model-value="filters[props.col.name]"
            @update:modelValue="onFilterInput(props.col.name, $event)"
            @clear="filters[props.col.name] = null"
            :filter-type="props.col.filter?.type || 'text'"
          />
        </q-th>
      </template> -->

      <!-- <template v-slot:body="props">
        <slot name="body-prepend" :props="props" />
        <q-tr
          :props="props"
          :style="props.rowIndex % 2 === 0 ? '' : 'background-color: rgba(255, 255, 255, 0.02);'"
        >
          <q-td v-if="hasActions">
            <q-btn-dropdown menu-self="top middle" flat dense>
              <q-list class="action-dropdown" dense>
                <q-item
                  v-if="routes.view"
                  @click="router.visit(getRoute(routes.view, props.row))"
                  clickable
                  v-close-popup
                >
                  <q-item-section avatar><q-icon :name="ionEye" /></q-item-section>
                  <q-item-section>View</q-item-section>
                </q-item>
                <q-item
                  v-if="routes.edit"
                  @click="router.visit(getRoute(routes.edit, props.row))"
                  clickable
                  v-close-popup
                >
                  <q-item-section avatar><q-icon :name="ionPencil" /></q-item-section>
                  <q-item-section>Edit</q-item-section>
                </q-item>
                <q-item
                  v-if="routes.delete"
                  @click="openConfirmDelete(props.row)"
                  clickable
                  v-close-popup
                >
                  <q-item-section avatar><q-icon :name="ionTrash" /></q-item-section>
                  <q-item-section>Delete</q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </q-td>
          <q-td v-for="col in props.cols" :key="col.name" :props="props">
            <slot
              v-if="$slots[`cell-content-${col.name}`]"
              :name="`cell-content-${col.name}`"
              :props="props"
              :col="col"
            />
            <template v-else>
              <template v-if="col.name === 'id'">
                <Link v-if="routes.view" :href="getRoute(routes.view, props.row)">
                  {{ col.value }}
                </Link>
                <template v-else>{{ col.value }}</template>
              </template>
              <template v-else-if="booleanColumns.includes(col.name)">
                <q-icon
                  :name="col.value === 'true' || col.value === true ? ionCheckmark : ionClose"
                  size="xs"
                />
              </template>
              <template v-else>
                {{ col.value }}
              </template>
            </template>
          </q-td>
        </q-tr>
        <slot name="body-append" :props="props" />
      </template> -->

      <template v-slot:body-cell-actions="props">
        <q-td :props="props"> actions </q-td>
      </template>

      <template v-slot:body-cell-id="props">
        <q-td :props="props">
          {{ props }}
          <Link v-if="routes.view" :href="getRoute(routes.view, props.row)">
            {{ props.col.value }}
          </Link>
          <template v-else>{{ props.col.value }}</template>
        </q-td>
      </template>
    </q-table>

    <q-dialog v-model="confirmDelete">
      <q-card flat bordered>
        <q-card-section class="row items-center no-wrap">
          <q-avatar :icon="ionInformationCircleOutline" color="negative" text-color="dark" />
          <span class="q-ml-sm"> Are you sure you want to delete this? </span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancel" v-close-popup />
          <q-btn flat label="Confirm" color="negative" @click="deleteItem" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<style lang="scss" scoped>
:deep(.q-table__top) {
  padding: 0 4px;
}

.action-dropdown {
  .q-item__section--avatar {
    min-width: 0;
  }
}
</style>

<script>
import { router } from '@inertiajs/vue3'
import { merge, isEmpty, isEqual } from 'lodash'
import axios from 'axios'
import {
  ionCalendar,
  ionCheckmark,
  ionClose,
  ionSwapVertical,
  ionAdd,
  ionEye,
  ionPencil,
  ionTrash,
  ionSettings,
  ionInformationCircleOutline,
} from '@quasar/extras/ionicons-v6'
import TableFilter from '@/Components/TableFilters/BaseFilter.vue'
import GridHeaderFilter from './Partials/GridHeaderFilter.vue'

export default {
  setup() {
    return {
      router,
      ionCalendar,
      ionCheckmark,
      ionClose,
      ionSwapVertical,
      ionAdd,
      ionEye,
      ionPencil,
      ionTrash,
      ionSettings,
      ionInformationCircleOutline,
    }
  },

  components: {
    TableFilter,
    GridHeaderFilter,
  },

  props: {
    initial: {
      type: Object,
      default: () => ({}),
    },
    routes: {
      type: Object,
      default: () => ({}),
    },
    columns: {
      type: Array,
      default: () => [],
    },
    search: {
      type: Object,
      default: () => ({}),
    },
    pagination: {
      type: Object,
      default: () => ({}),
    },
    hideColumns: {
      type: Array,
      default: () => [],
    },
    showColumns: {
      type: Array,
      default: () => [],
    },
    noTimestampToggle: {
      type: Boolean,
      default: false,
    },
    noFilters: {
      type: Boolean,
      default: false,
    },
    createButtonText: {
      type: String,
      default: 'Create',
    },
    selection: {
      type: String,
      default: 'none',
    },
  },

  data() {
    return {
      rows: [],
      _columns: [],
      loading: false,
      _pagination: {
        sortBy: 'id',
        descending: true,
        page: 1,
        rowsPerPage: 15,
        rowsNumber: 0,
      },
      defaultPagination: {},
      filters: {},
      settingFiltersFromUrl: false,
      showTimestamps: false,
      timestampColumns: ['created_at', 'updated_at'],
      confirmDelete: false,
      deletingItem: null,
      selected: [],
    }
  },

  computed: {
    hasTimestamps() {
      return this.columns.some((column) => {
        return this.timestampColumns.includes(column.name)
      })
    },

    visibleColumns() {
      const visible = []
      for (const column of this.columns) {
        if (
          !this.showTimestamps &&
          this.timestampColumns.includes(column.name) &&
          !this.showColumns.includes(column.name)
        )
          // If we're not showing timestamps, and this is a timestamp, and we're not forcing it to show
          continue
        if (this.hideColumns.includes(column.name) && !this.showColumns.includes(column.name))
          continue
        visible.push(column.name)
      }
      return visible
    },

    booleanColumns() {
      const booleanColumns = []
      this.columns.forEach((column) => {
        if (column?.filter?.type === 'boolean') booleanColumns.push(column.name)
      })
      return booleanColumns
    },

    showGridFilters() {
      return !this.noFilters && Object.keys(this.$attrs).includes('grid')
    },

    currentSortColumn() {
      return this.columns.find((column) => column.name === this._pagination.sortBy)
    },

    sortedByLabel() {
      if (!this.currentSortColumn) return
      const dir = this._pagination.descending ? 'descending' : 'ascending'
      return `Sorted by ${this.currentSortColumn.label.toLowerCase()} ${dir}`
    },

    gridTopHasContent() {
      return this.showGridFilters || (this.hasTimestamps && !this.noTimestampToggle)
    },

    hasActions() {
      let ret = false
      const actionRoutes = ['view', 'edit', 'delete']
      for (const route in this.routes) {
        if (actionRoutes.includes(route)) {
          ret = true
          break
        }
      }
      return ret
    },

    canSelect() {
      return this.selection !== 'none'
    },
  },

  created() {
    const mergedPagination = merge(this._pagination, this.pagination)

    // For an initial server-built packet of data
    if (!isEmpty(this.initial)) {
      this.rows = this.initial.data
      // if (this.initial.current_page > 1) {
      //   mergedPagination.page = this.initial.current_page
      // }
      // mergedPagination.rowsPerPage = this.initial.per_page || 15
      mergedPagination.rowsNumber = this.initial.total
    }

    this.defaultPagination = Object.assign({}, mergedPagination)
    this._pagination = mergedPagination
  },

  mounted() {
    this.loadUrlParams()
    this.$emit('loaded', { filters: this.filters })
  },

  methods: {
    async fetchFromServer(page, fetchCount, sortBy, descending) {
      return await axios.get(this.routes.fetch, {
        params: {
          page,
          per_page: fetchCount,
          sort_by: sortBy,
          descending,
          filters: this.filters,
        },
      })
    },

    async onRequest(tableProps) {
      const { page, rowsPerPage, sortBy, descending } = tableProps.pagination
      this.loading = true
      this.$emit('fetch-start')

      let res
      try {
        res = await this.fetchFromServer(page, rowsPerPage, sortBy, descending)
      } catch (e) {
        this.loading = false
        return
      }
      this.rows.splice(0, this.rows.length, ...res.data.data)

      this._pagination.page = res.data.current_page
      this._pagination.rowsPerPage = res.data.per_page
      this._pagination.sortBy = sortBy
      this._pagination.descending = descending
      this._pagination.rowsNumber = res.data.total
      this.setUrlParams()
      this.loading = false
      this.$emit('fetch-end')
    },

    loadUrlParams() {
      const url = new URL(window.location.href)
      const urlSearch = new URLSearchParams(url.search)
      const newFilters = Object.assign({}, this.filters)
      urlSearch.forEach((param, key) => {
        const match = key.match(/filters\[(.*?)\]/)
        if (match && match[1]) {
          newFilters[match[1]] = param
        } else if (key === 'page') this._pagination.page = parseInt(param)
        else if (key === 'sort_by') this._pagination.sortBy = param
        else if (key === 'descending') this._pagination.descending = param === 'true'
        else if (key === 'per_page') this._pagination.rowsPerPage = parseInt(param)
      })
      if (!isEqual(this.filters, newFilters)) {
        this.settingFiltersFromUrl = true
        this.filters = merge(this.filters, newFilters)
      }
    },

    setUrlParams() {
      const url = new URL(window.location.origin + window.location.pathname)
      const urlSearch = new URLSearchParams(url.search)

      if (this._pagination.page !== this.defaultPagination.page)
        urlSearch.append('page', this._pagination.page)
      if (this._pagination.sortBy !== this.defaultPagination.sortBy)
        urlSearch.append('sort_by', this._pagination.sortBy)
      if (this._pagination.descending !== this.defaultPagination.descending)
        urlSearch.append('descending', this._pagination.descending)
      if (this._pagination.rowsPerPage !== this.defaultPagination.rowsPerPage)
        urlSearch.append('per_page', this._pagination.rowsPerPage)

      for (const p in this.filters) {
        const filter = this.filters[p]
        const propKey = `filters[${p}]`
        if (!filter && urlSearch.has(propKey)) {
          urlSearch.delete(propKey)
        } else if (filter) {
          urlSearch.append(propKey, filter)
        }
      }

      const newParams = decodeURI(urlSearch.toString())
      let newUrl = window.location.pathname
      if (newParams) {
        newUrl += `?${newParams}`
      }
      history.replaceState(null, '', newUrl)
      this.$inertia.page.url = newUrl
    },

    onFiltersChange() {
      this.$refs.tableRef.requestServerInteraction()
    },

    onFilterInput(col, val) {
      this.filters[col] = val
    },

    onSortChange({ column, descending }) {
      if (column) {
        this._pagination.sortBy = column
      }
      if (descending) {
        this._pagination.descending = descending
      }
      this.$refs.tableRef.requestServerInteraction()
    },

    getRoute(goToRoute, row) {
      if (!row) return goToRoute
      return goToRoute.replace('_id', row.id)
    },

    reset() {
      if (!isEmpty(this.filters)) {
        this.filters = {}
      }
      this._pagination = Object.assign({}, this.defaultPagination)
    },

    openConfirmDelete(item) {
      this.deletingItem = item
      this.confirmDelete = true
    },

    async deleteItem() {
      const deleteRoute = this.getRoute(this.routes.delete, this.deletingItem)
      try {
        await axios.delete(deleteRoute)
      } catch {
        this.deletingItem = null
        this.confirmDelete = false
        this.$q.notify({
          message: 'Failed to delete item, please try again.',
          color: 'negative',
        })
        return
      }

      this.deletingItem = null
      this.confirmDelete = false
      this.$refs.tableRef.requestServerInteraction()
      this.$q.notify({
        message: 'Item successfully deleted.',
        color: 'positive',
      })
    },
  },

  watch: {
    columns: {
      deep: true,
      immediate: true,
      handler(newColumns) {
        newColumns = Object.assign([], newColumns)
        if (this.hasActions) {
          newColumns.unshift({
            name: 'actions',
            label: '',
            field: 'actions',
            required: true,
            headerClasses: 'q-table--col-auto-width',
          })
        }

        newColumns.forEach((column) => (this.filters[column.name] = null))
        this._columns = newColumns
        console.log(this._columns)
      },
    },

    filters: {
      deep: true,
      handler() {
        // Skip a server fetch if we're setting filters from the URL
        // Because we can assume our initial data from the server is already filtered
        if (this.settingFiltersFromUrl) {
          this.settingFiltersFromUrl = false
          return
        }
        this.onFiltersChange()
      },
    },

    search: {
      deep: true,
      handler(val) {
        this.filters = merge(this.filters, val)
      },
    },

    selected: {
      deep: true,
      handler(val) {
        console.log('selected', val)
      },
    },
  },
}
</script>
