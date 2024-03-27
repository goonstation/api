<template>
  <maps-form
    :fields="fields"
    :submit-route="route('admin.maps.store')"
    :map-layers="layers"
    success-message="Map added"
  />
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MapsForm from '@/Components/Forms/MapsForm.vue'

export default {
  components: {
    MapsForm
  },

  layout: (h, page) => h(AdminLayout, { title: 'Add Map' }, () => page),

  props: {
    layers: Object,
  },

  data() {
    return {
      fields: {
        map_id: null,
        name: null,
        active: false,
        is_layer: false,
        tile_width: 300,
        tile_height: 300,
        layers: [],
      },
    }
  }
}
</script>
