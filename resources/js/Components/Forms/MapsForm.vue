<template>
  <div class="row">
    <div class="col-12 col-md-6">
      <q-form @submit="submit">
        <q-card class="gh-card q-mb-md" flat>
          <q-card-section>
            <q-input
              v-model="form.map_id"
              class="q-mb-md"
              label="Map ID"
              hint="Unique identifier for this map. Should be uppercase and correspond to the ID in var/global/list/mapNames"
              filled
              lazy-rules
              required
              dense
              hide-bottom-space
              :error="!!form.errors.map_id"
              :error-message="form.errors.map_id"
            />
            <q-input
              v-model="form.name"
              class="q-mb-md"
              label="Name"
              hint="The Human readable name for this map"
              filled
              lazy-rules
              required
              dense
              hide-bottom-space
              :error="!!form.errors.name"
              :error-message="form.errors.name"
            />
            <q-toggle v-model="form.active" label="Active" />
            <div class="text-caption q-px-sm">
              If active, this map will be visible in the web map viewer.
            </div>
            <q-toggle v-model="form.admin_only" label="Admin Only" />
            <div class="text-caption q-px-sm">Only display this map to admins.</div>
            <q-toggle v-model="form.is_layer" label="Layer" />
            <div class="text-caption q-px-sm q-mb-md">
              If a layer, this map will only be viewable as an extra layer on other maps.
            </div>
            <q-select
              v-if="!form.is_layer"
              v-model="form.layers"
              :options="mapLayers"
              class="q-mb-md"
              label="Layers"
              hint="Maps that show as an extra layer to this map"
              option-value="id"
              option-label="name"
              multiple
              filled
              emit-value
              map-options
              hide-bottom-space
              lazy-rules
              dense
              clearable
              :error="!!form.errors.layers"
              :error-message="form.errors.layers"
            />
            <q-input
              v-model="form.tile_width"
              type="number"
              class="q-mb-md"
              label="Tile Width"
              hint="The width of the map in tiles"
              filled
              lazy-rules
              required
              dense
              hide-bottom-space
              :error="!!form.errors.tile_width"
              :error-message="form.errors.tile_width"
            />
            <q-input
              v-model="form.tile_height"
              type="number"
              class="q-mb-md"
              label="Tile Height"
              hint="The height of the map in tiles"
              filled
              lazy-rules
              required
              dense
              hide-bottom-space
              :error="!!form.errors.tile_height"
              :error-message="form.errors.tile_height"
            />
          </q-card-section>
        </q-card>

        <q-card v-if="form.is_layer" class="gh-card q-mb-md" flat>
          <q-card-section>
            <div class="q-mb-md">Layer Maps</div>
            <select
              v-model="form.base_maps"
              class="layer-base-map-select full-width rounded-borders text-white"
              multiple
              :size="baseMaps.length + 1"
            >
              <option :value="0">All</option>
              <option v-for="map in baseMaps" :value="map.id">{{ map.name }}</option>
            </select>
            <div class="text-caption q-px-sm q-mt-sm">
              The maps that this layer will show on.
            </div>
          </q-card-section>
        </q-card>

        <div class="flex">
          <q-space />
          <q-btn
            :label="(state === 'edit' ? 'Edit' : 'Add') + ' Map'"
            type="submit"
            color="primary"
            text-color="black"
            :loading="form.processing"
          />
        </div>
      </q-form>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.layer-base-map-select {
  background: rgba(255, 255, 255, 0.07);

  option {
    padding: 5px;
  }
}
</style>

<script>
import BaseForm from './BaseForm.vue'

export default {
  extends: BaseForm,

  props: {
    maps: Object,
  },

  computed: {
    mapLayers() {
      return this.maps.filter((map) => !!map.is_layer)
    },

    baseMaps() {
      return this.maps.filter((map) => !map.is_layer)
    },
  },

  methods: {
    onBaseMapsInput(selectedBaseMaps) {
      if (selectedBaseMaps.includes(0)) {
        this.form.base_maps = this.baseMaps.map((map) => map.id)
      }
    }
  },

  watch: {
    'form.base_maps': {
      handler(val) {
        this.onBaseMapsInput(val)
      }
    }
  }
}
</script>
