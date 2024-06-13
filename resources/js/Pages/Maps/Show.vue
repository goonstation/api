<template>
  <app-head :title="map.name" />

  <div id="map" :class="{ ground: isOnGround }"></div>

  <Link href="/maps" class="go-back">
    <q-icon :name="ionArrowBack" />
    Return to Goonhub
  </Link>

  <select v-model="layer" @change="onLayerChange" class="layer-select">
    <option v-for="layer in layers" :value="layer.value">{{ layer.name }}</option>
  </select>
</template>

<style lang="scss" scoped>
#map {
  background: black url('@img/space.png');
  width: 100dvw;
  height: 100dvh;
  image-rendering: pixelated;

  &.ground {
    background: #838c8c url('@img/cliffs.png');
  }
}

:deep(.leaflet-control-zoom) {
  margin-top: 50px;
}

:deep(.coord-marker) {
  display: flex;
  align-items: flex-start;
  white-space: nowrap;
}
:deep(.coord-marker div) {
  transform: translate(-50%, -100%);
  padding: 5px 8px;
  background: white;
  color: black;
  font-size: 11px;
  border-radius: 3px;
}

.go-back {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 999;
  background: white;
  border-radius: 2px;
  padding: 3px 10px;
  line-height: 1.4;
  font-weight: 500;
  color: black;
}

.layer-select {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 999;
  padding: 5px 10px 5px 5px;
  border-radius: 2px;
  border: none;
  color: black;
}
</style>

<script>
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { ionArrowBack } from '@quasar/extras/ionicons-v6'
import { Link } from '@inertiajs/vue3'
import { copyToClipboard } from 'quasar'
import AppHead from '@/Components/AppHead.vue'

let map
let bounds
let mapDimensions
let layerOptions
let tileLayers = []
let coordMarker = null

export default {
  components: {
    AppHead,
    Link,
  },

  props: {
    map: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },

  setup() {
    return {
      ionArrowBack,
    }
  },

  data() {
    return {
      loading: true,
      layer: this.map.map_id,
      selecting: null,
      removingCoordMarker: false,
    }
  },

  computed: {
    isOnGround() {
      return this.map.map_id === 'OSHAN' || this.map.map_id === 'NADIR'
    },

    layers() {
      const items = [{ name: this.map.name, value: this.map.map_id }]
      for (const layer of this.map.layers) {
        items.push({ name: layer.name, value: layer.map_id })
      }
      return items
    },
  },

  mounted() {
    this.buildMap()

    this.buildLayer(this.map)
    for (const layer of this.map.layers) {
      this.buildLayer(layer)
    }

    map.addLayer(tileLayers[this.map.map_id]).setView(bounds.getCenter(), -2)

    this.loadUrlParams()
  },

  methods: {
    buildMap() {
      const tileSize = this.map.screenshot_tiles * 32
      const tilesPerRow = this.map.tile_width / this.map.screenshot_tiles
      const tilesPerColumn = this.map.tile_height / this.map.screenshot_tiles
      const mapSizeWidth = tilesPerRow * tileSize
      const mapSizeHeight = tilesPerColumn * tileSize

      mapDimensions = {
        tileSize,
        tilesPerRow,
        tilesPerColumn,
        mapSizeWidth,
        mapSizeHeight,
      }

      L.CRS.myCRS = L.extend({}, L.CRS.Simple, {})
      map = L.map('map', { crs: L.CRS.myCRS, attributionControl: false })

      const southWest = map.unproject([0, mapSizeHeight], 0)
      const northEast = map.unproject([mapSizeWidth, 0], 0)
      bounds = L.latLngBounds(southWest, northEast)

      layerOptions = {
        tileSize,
        bounds,
        minZoom: -3,
        maxZoom: 2,
        minNativeZoom: 0,
        maxNativeZoom: 0,
        noWrap: true,
      }

      map.setMaxBounds(bounds)

      map.on('click', (e) => {
        this.markTile(map.mouseEventToLatLng(e.originalEvent))
      })

      map.on('moveend', () => {
        this.setUrlParams()
      })
    },

    buildLayer(map) {
      const mapUri = map.map_id.toLowerCase()
      const version = map.updated_at ? encodeURI(map.updated_at) : ''
      const layerBuilder = L.TileLayer.extend({
        options: layerOptions,
        getTileUrl: (coords) => {
          const tileUri = `${mapUri}/${coords.x},${coords.y}.png?v=${version}`
          if (map.admin_only) {
            return `/maps/private/${tileUri}`
          }
          return `/storage/maps/${tileUri}`
        },
      })
      L.tileLayer[map.map_id] = (opts) => {
        return new layerBuilder(opts)
      }
      tileLayers[map.map_id] = L.tileLayer[map.map_id]()
    },

    changeLayer(mapId) {
      map.eachLayer((layer) => map.removeLayer(layer))
      map.addLayer(tileLayers[mapId])
    },

    onLayerChange() {
      this.changeLayer(this.layer)
      map.setView(bounds.getCenter(), -2)
    },

    setUrlParams() {
      if (this.loading) return

      const zoomLevel = map.getZoom()
      const center = map.getCenter()
      const centerCoords = map.project(center, 0)
      const x = Math.floor(centerCoords.x / 32) + 1
      const y = Math.floor((mapDimensions.mapSizeHeight - centerCoords.y) / 32) + 1
      const layerIndex = this.layers.findIndex((layer) => layer.value === this.layer)

      const url = new URL(window.location.origin + window.location.pathname)
      const urlSearch = new URLSearchParams(url.search)
      urlSearch.append('x', x)
      urlSearch.append('y', y)
      urlSearch.append('zoom', zoomLevel)
      if (layerIndex === 0) urlSearch.delete('layer')
      else urlSearch.append('layer', this.layer.toLowerCase())
      if (this.selecting) {
        urlSearch.append('sx', this.selecting.x)
        urlSearch.append('sy', this.selecting.y)
      }

      const newParams = decodeURI(urlSearch.toString())
      let newUrl = window.location.pathname
      if (newParams) newUrl += `?${newParams}`
      history.replaceState(null, '', newUrl)
      this.$inertia.page.url = newUrl
    },

    loadUrlParams() {
      const url = new URL(window.location.href)
      const urlSearch = new URLSearchParams(url.search)

      const coords = { x: 0, y: 0 }
      const selectCoords = { x: 0, y: 0 }
      urlSearch.forEach((param, key) => {
        if (key === 'x') coords.x = parseInt(param)
        else if (key === 'y') coords.y = parseInt(param)
        else if (key === 'zoom') map.setZoom(parseInt(param), { animate: false })
        else if (key === 'layer') {
          const layerMapId = param.toUpperCase()
          if (!this.layers.find((layer) => layer.value === layerMapId)) return
          this.layer = layerMapId
          this.changeLayer(layerMapId)
        } else if (key === 'sx') selectCoords.x = parseInt(param)
        else if (key === 'sy') selectCoords.y = parseInt(param)
      })

      if (selectCoords.x && selectCoords.y) {
        this.markCoords(selectCoords)
        this.moveToCoords(selectCoords)
      } else if (coords.x || coords.y) this.moveToCoords(coords)
      this.loading = false
    },

    moveToCoords(coords) {
      const x = (coords.x - 1) * 32 + 16
      const y = mapDimensions.mapSizeHeight - coords.y * 32 + 16
      const latlng = map.unproject([x, y], 0)
      map.panTo(latlng, { animate: false })
    },

    markCoords(coords) {
      const x = (coords.x - 1) * 32 + 16
      const y = mapDimensions.mapSizeHeight - coords.y * 32 + 16
      const latlng = map.unproject([x, y], 0)
      this.markTile(latlng)
    },

    markTile(latlng) {
      if (this.removingCoordMarker) {
        this.removingCoordMarker = false
        return
      }
      if (!layerOptions.bounds.contains(latlng)) return
      if (coordMarker) map.removeLayer(coordMarker)

      const coords = map.project(latlng, 0)
      const x = Math.floor(coords.x / 32)
      const y = Math.floor(coords.y / 32)

      const leftEdge = x * 32
      const topEdge = y * 32
      const topLeft = map.unproject([leftEdge - 1, topEdge - 1], 0)
      const bottomRight = map.unproject([leftEdge + 32, topEdge + 32], 0)
      const bounds = [
        [topLeft.lat, topLeft.lng],
        [bottomRight.lat, bottomRight.lng],
      ]
      const rect = L.rectangle(bounds, { color: '#ff7800', weight: 1 })

      const topMiddle = map.unproject([leftEdge + 16, topEdge], 0)
      const displayX = x + 1
      const displayY = Math.floor((mapDimensions.mapSizeHeight - coords.y) / 32) + 1
      const coordText = `(x: ${displayX}, y: ${displayY})`
      const marker = L.marker(topMiddle, {
        icon: L.divIcon({
          html: `<div>${coordText}</div>`,
          className: 'coord-marker',
          iconSize: [1, 1],
        }),
      })

      this.selecting = { x: displayX, y: displayY }
      coordMarker = L.layerGroup([rect, marker])
      coordMarker.addTo(map)
      this.setUrlParams()

      marker.on('click', () => {
        copyToClipboard(coordText).then(() => {
          this.$q.notify({
            message: 'Copied text',
            color: 'positive',
          })
        })
      })

      rect.on('click', () => {
        this.selecting = null
        this.removingCoordMarker = true
        map.removeLayer(coordMarker)
        this.setUrlParams()
      })
    },
  },
}
</script>
