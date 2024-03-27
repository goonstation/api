<template>
  <Head :title="map.name" />

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
import { Head, Link } from '@inertiajs/vue3'

let map
let bounds
let layerOptions
let tileLayers = []

export default {
  components: {
    Head,
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
      layer: this.map.map_id,
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

    // L.GridLayer.GridDebug = L.GridLayer.extend({
    //   createTile: function (coords) {
    //     const tile = document.createElement('div')
    //     tile.style.width = '32px'
    //     tile.style.height = '32px'
    //     tile.style.outline = '1px solid green'
    //     tile.style.fontWeight = 'bold'
    //     tile.style.fontSize = '14pt'
    //     tile.innerHTML = [coords.z, coords.x, coords.y].join('/')
    //     return tile
    //   },
    // })

    // L.gridLayer.gridDebug = function (opts) {
    //   return new L.GridLayer.GridDebug(opts)
    // }

    // map.addLayer(L.gridLayer.gridDebug())
  },

  methods: {
    buildMap() {
      const tileSize = this.map.screenshot_tiles * 32
      const tilesPerRow = this.map.tile_width / this.map.screenshot_tiles
      const tilesPerColumn = this.map.tile_height / this.map.screenshot_tiles
      const mapSizeWidth = tilesPerRow * tileSize
      const mapSizeHeight = tilesPerColumn * tileSize

      const factorX = tilesPerRow / mapSizeWidth
      const factorY = tilesPerColumn / mapSizeHeight

      console.log({
        tileSize,
        tilesPerRow,
        tilesPerColumn,
        mapSizeWidth,
        mapSizeHeight,
        factorX,
        factorY
      })

      L.CRS.myCRS = L.extend({}, L.CRS.Simple, {
        // transformation: new L.Transformation(factorX, 0, factorY, 0),
      })
      map = L.map('map', { crs: L.CRS.myCRS })

      const southWest = map.unproject([0, mapSizeHeight], 0)
      const northEast = map.unproject([mapSizeWidth, 0], 0)
      bounds = L.latLngBounds(southWest, northEast)

      console.log({
        southWest,
        northEast,
        bounds
      })

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
        const coords = map.project(map.mouseEventToLatLng(e.originalEvent), 0)
        console.log(coords)

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
        L.rectangle(bounds, { color: '#ff7800', weight: 1 })
          .addTo(map)

        const topMiddle = map.unproject([leftEdge + 16, topEdge], 0)
        L.marker(topMiddle, {
          icon: L.divIcon({
            html: `<div>(x: ${x}, y: ${y})</div>`,
            className: 'coord-marker',
            iconSize: [1, 1],
          }),
        }).addTo(map)
      })
    },

    buildLayer(map) {
      const mapUri = map.map_id.toLowerCase()
      const version = map.updated_at ? encodeURI(map.updated_at) : ''
      const layerBuilder = L.TileLayer.extend({
        options: layerOptions,
        getTileUrl: (coords) => {
          return `/storage/maps/${mapUri}/${coords.x},${coords.y}.png`
        },
      })
      L.tileLayer[map.map_id] = (opts) => {
        return new layerBuilder(opts)
      }
      tileLayers[map.map_id] = L.tileLayer[map.map_id]()
    },

    onLayerChange() {
      map.eachLayer(function (layer) {
        map.removeLayer(layer)
      })
      map.addLayer(tileLayers[this.layer]).setView(bounds.getCenter(), -2)
    },
  },
}
</script>
