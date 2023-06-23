<template>
  <Head :title="map" />

  <div id="map"></div>

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

const tileSize = 960
const mapSize = 9600

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
      type: String,
      required: true,
      default: 'cogmap',
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

  mounted() {
    this.buildMap()
    this.buildLayerStation()
    this.buildLayerDebris()
    map.addLayer(tileLayers.station).setView(bounds.getCenter(), -2)
  },

  methods: {
    buildMap() {
      const factor = tileSize / mapSize
      L.CRS.myCRS = L.extend({}, L.CRS.Simple, {
        transformation: new L.Transformation(factor, 0, factor, 0),
      })
      map = L.map('map', { crs: L.CRS.myCRS })

      const southWest = map.unproject([0, mapSize], 0)
      const northEast = map.unproject([mapSize, 0], 0)
      bounds = L.latLngBounds(southWest, northEast)

      layerOptions = {
        tileSize,
        bounds,
        minZoom: -2,
        maxZoom: 2,
        minNativeZoom: 0,
        maxNativeZoom: 0,
        noWrap: true,
      }

      map.setMaxBounds(bounds)
    },

    buildLayerStation() {
      L.TileLayer.Station = L.TileLayer.extend({
        options: layerOptions,
        getTileUrl: (coords) => {
          return `/storage/maps/${this.map}/${coords.x},${coords.y}.png`
        },
      })
      L.tileLayer.station = function (opts) {
        return new L.TileLayer.Station(opts)
      }
      tileLayers.station = L.tileLayer.station()
    },

    buildLayerDebris() {
      L.TileLayer.Debris = L.TileLayer.extend({
        options: layerOptions,
        getTileUrl: (coords) => {
          return `/storage/maps/debris/${coords.x},${coords.y}.png`
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
