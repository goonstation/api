<template>
  <div class="row">
    <div class="col-12 col-md-6">
      <q-form @submit="submit">
        <q-card class="gh-card q-mb-md" flat>
          <!-- <div class="gh-card__header q-pa-md bordered">
            <span>Upload Map</span>
          </div> -->
          <q-card-section>
            <q-file
              filled
              bottom-slots
              v-model="file"
              label="Map Images"
              accept=".zip"
              class="q-mb-md"
              counter
            >
              <template v-slot:prepend>
                <q-icon :name="ionCloudUpload" @click.stop.prevent />
              </template>
              <template v-slot:append>
                <q-icon :name="ionClose" @click.stop.prevent="file = null" class="cursor-pointer" />
              </template>

              <template v-slot:hint>
                A ZIP file containing map images produced by the ingame "Map-World" verb
              </template>
            </q-file>
          </q-card-section>
        </q-card>

        <q-linear-progress
          v-if="loading"
          :value="progress"
          color="primary"
          class="q-mb-md"
          instant-feedback
        />

        <div class="flex">
          <q-space />
          <q-btn
            label="Submit"
            type="submit"
            color="primary"
            text-color="black"
            :loading="loading"
          />
        </div>
      </q-form>
    </div>
  </div>
</template>

<script>
import Resumable from 'resumablejs'
import { ionCloudUpload, ionClose } from '@quasar/extras/ionicons-v6'
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default {
  layout: (h, page) => h(AdminLayout, { title: 'Upload Map' }, () => page),

  setup() {
    return {
      ionCloudUpload,
      ionClose,
    }
  },

  data() {
    return {
      file: null,
      progress: 0,
      loading: false,
    }
  },

  methods: {
    submit() {
      if (this.loading) return
      this.loading = true
      const resumable = new Resumable({
        target: route('admin.maps.upload-file'),
        query: { _token: this.$page.props.csrf_token },
        chunkSize: 1 * 1024 * 1024, // 1MB
        simultaneousUploads: 3,
        testChunks: false,
        throttleProgressCallbacks: 1,
      })
      resumable.on('fileAdded', () => {
        resumable.upload()
      })
      resumable.on('fileProgress', (file) => {
        this.progress = file.progress()
        console.log(this.progress)
      })
      resumable.on('complete', () => {
        console.log('done')
        this.loading = false
      })

      resumable.addFile(this.file)
    },
  },
}
</script>
