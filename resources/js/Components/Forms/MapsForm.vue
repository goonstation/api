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
            <div class="text-caption q-px-sm">If active, this map will be visible in the web map viewer.</div>
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

<script>
import BaseForm from './BaseForm.vue'

export default {
  extends: BaseForm,

  props: {
    mapLayers: Object,
  },
}
</script>
