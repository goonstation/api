<template>
  <maps-form
    state="edit"
    :fields="fields"
    :submit-route="route('admin.maps.update', { map: map.id })"
    :map-layers="layers"
    submit-method="put"
    success-message="Map updated"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MapsForm from '@/Components/Forms/MapsForm.vue'

export default {
  components: {
    MapsForm,
  },

  layout: (h, page) => h(AdminLayout, { title: 'Edit Map' }, () => page),

  props: {
    map: Object,
    layers: Object,
  },

  data() {
    return {
      fields: {
        map_id: this.map.map_id,
        name: this.map.name,
        active: this.map.active,
        admin_only: this.map.admin_only,
        is_layer: this.map.is_layer,
        tile_width: this.map.tile_width,
        tile_height: this.map.tile_height,
        layers: this.map.layers ? this.map.layers.map((layer) => layer.id) : [],
      },
    }
  },
}
</script>
