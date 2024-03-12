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
    debris: {
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
      layer: 'station',
      layers: [
        { name: 'Station', value: 'station' },
        { name: 'Debris', value: 'debris' },
      ],
    }
  },

  computed: {
    isOnGround() {
      return this.map.map_id === 'OSHAN' || this.map.map_id === 'NADIR'
    },
  },

  mounted() {
    this.buildMap()
    this.buildLayerStation()
    this.buildLayerDebris()
    map.addLayer(tileLayers.station).setView(bounds.getCenter(), -2)
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
      L.CRS.myCRS = L.extend({}, L.CRS.Simple, {
        transformation: new L.Transformation(factorX, 0, factorY, 0),
      })
      map = L.map('map', { crs: L.CRS.myCRS })

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
    },

    buildLayerStation() {
      const mapUri = this.map.map_id.toLowerCase()
      const version = this.map.updated_at ? encodeURI(this.map.updated_at) : ''
      L.TileLayer.Station = L.TileLayer.extend({
        options: layerOptions,
        getTileUrl: (coords) => {
          return `/storage/maps/${mapUri}/${coords.x},${coords.y}.png?v=${version}`
        },
      })
      L.tileLayer.station = function (opts) {
        return new L.TileLayer.Station(opts)
      }
      tileLayers.station = L.tileLayer.station()
    },

    buildLayerDebris() {
      const version = this.debris.updated_at ? encodeURI(this.debris.updated_at) : ''
      L.TileLayer.Debris = L.TileLayer.extend({
        options: layerOptions,
        getTileUrl: (coords) => {
          return `/storage/maps/debris/${coords.x},${coords.y}.png?v=${version}`
        },
      })
      L.tileLayer.debris = function (opts) {
        return new L.TileLayer.Debris(opts)
      }
      tileLayers.debris = L.tileLayer.debris()
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
