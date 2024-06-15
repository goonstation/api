<template>
  <div class="row">
    <div class="col-12 col-md-6">
      <q-form @submit="submit">
        <q-card class="gh-card q-mb-md" flat>
          <q-card-section>
            <div class="flex">
              <q-img
                :src="imageUrl"
                :class="{ 'text-grey-8 bordered rounded-borders': !imageUrl }"
                spinner-color="white"
                class="q-mr-md"
                width="64px"
                height="64px"
              />
              <div class="flex-grow">
                <q-file
                  v-model="form.image"
                  :label="hasExistingImage ? `${medal.uuid}.png` : 'Image'"
                  accept="image/png"
                  class="q-mb-md"
                  hint="PNG, ideal size is 64px by 64px"
                  filled
                  :error="!!form.errors.image"
                  :error-message="form.errors.image"
                  @update:model-value="onFileSelect"
                >
                  <template v-if="form.image || hasExistingImage" v-slot:append>
                    <q-icon
                      :name="ionClose"
                      @click.stop.prevent="clearFileSelect"
                      class="cursor-pointer"
                    />
                  </template>
                </q-file>
              </div>
            </div>
            <q-input
              v-model="form.title"
              class="q-mb-md"
              label="Title"
              filled
              lazy-rules
              required
              dense
              hide-bottom-space
              :error="!!form.errors.title"
              :error-message="form.errors.title"
            />
            <q-input
              v-model="form.description"
              class="q-mb-md"
              label="Description"
              type="textarea"
              filled
              lazy-rules
              dense
              hide-bottom-space
              :error="!!form.errors.description"
              :error-message="form.errors.description"
            />
            <q-toggle v-model="form.hidden" label="Hidden" />
            <div class="text-caption q-px-sm">
              If hidden, the medal won't display on public pages
            </div>
          </q-card-section>
        </q-card>

        <div class="flex">
          <q-space />
          <q-btn
            :label="(state === 'edit' ? 'Edit' : 'Add') + ' Medal'"
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

<style lang="scss" scoped></style>

<script>
import { ionClose } from '@quasar/extras/ionicons-v6'
import BaseForm from './BaseForm.vue'

function checkIfImageExists(url, callback) {
  const img = new Image()
  img.src = url

  if (img.complete) {
    callback(true)
  } else {
    img.onload = () => {
      callback(true)
    }

    img.onerror = () => {
      callback(false)
    }
  }
}

export default {
  extends: BaseForm,

  props: {
    medal: Object,
  },

  setup() {
    return {
      ionClose,
    }
  },

  data() {
    return {
      previewImageUrl: null,
      hasExistingImage: false,
    }
  },

  computed: {
    imageUrl() {
      return this.previewImageUrl ? this.previewImageUrl : this.form.image
    },
  },

  mounted() {
    if (this.medal) {
      const currentImagePath = `/storage/medals/${this.medal.uuid}.png`
      checkIfImageExists(currentImagePath, (exists) => {
        if (exists) {
          this.previewImageUrl = currentImagePath
          this.hasExistingImage = true
        }
      })
    }
  },

  methods: {
    onFileSelect() {
      if (!this.form.image) {
        this.previewImageUrl = null
        return
      }
      this.previewImageUrl = URL.createObjectURL(this.form.image)
    },

    clearFileSelect() {
      this.form.image = null

      if (this.hasExistingImage) {
        this.form.clear_image = true
        this.previewImageUrl = null
        this.hasExistingImage = false
      }
    },
  },
}
</script>
