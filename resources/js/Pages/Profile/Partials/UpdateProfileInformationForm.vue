<template>
  <q-card class="gh-card q-pa-sm" flat>
    <q-card-section>
      <q-form @submit="updateProfileInformation">
        <div v-if="$page.props.jetstream.managesProfilePhotos" class="q-mb-md">
          <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview" />

          <div class="q-mb-md text-weight-medium text-body1">Photo</div>

          <!-- Current Profile Photo -->
          <div v-show="!photoPreview" class="q-mt-sm">
            <img :src="user.profile_photo_url" :alt="user.name" class="photo" />
          </div>

          <!-- New Profile Photo Preview -->
          <div v-show="photoPreview" class="q-mt-sm">
            <span class="photo block" :style="'background-image: url(\'' + photoPreview + '\');'" />
          </div>

          <q-btn type="button" class="q-mt-sm q-mr-sm" @click="selectNewPhoto" color="grey-4" outline>
            Select A New Photo
          </q-btn>

          <q-btn v-if="user.profile_photo_path" type="button" class="q-mt-sm" @click="deletePhoto">
            Remove Photo
          </q-btn>

          <div v-if="form.errors.photo" class="text-negative q-mt-sm">
            {{ form.errors.photo }}
          </div>
        </div>

        <q-input
          v-model="form.name"
          class="q-mb-sm"
          type="text"
          label="Name"
          filled
          required
          hide-bottom-space
          autocomplete="name"
          :error="!!form.errors.name"
          :error-message="form.errors.name"
        />

        <q-input
          v-model="form.email"
          class="q-mb-sm"
          type="email"
          label="Email"
          filled
          required
          hide-bottom-space
          :error="!!form.errors.email"
          :error-message="form.errors.email"
        />

        <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
          <p class="text-sm q-mt-sm">
            Your email address is unverified.

            <Link
              :href="route('verification.send')"
              method="post"
              as="button"
              @click.prevent="sendEmailVerification"
            >
              Click here to re-send the verification email.
            </Link>
          </p>

          <div v-show="verificationLinkSent" class="q-mt-sm text-weight-medium text-sm text-positive">
            A new verification link has been sent to your email address.
          </div>
        </div>

        <div class="flex items-center q-mt-md">
          <q-space />
          <ActionMessage :on="form.recentlySuccessful" class="q-mr-sm">
              Saved.
          </ActionMessage>
          <q-btn
            label="Save"
            type="submit"
            color="primary"
            text-color="black"
            :loading="form.processing"
          />
        </div>
      </q-form>
    </q-card-section>
  </q-card>
</template>

<style lang="scss" scoped>
.photo {
  border-radius: 50%;
  width: 80px;
  height: 80px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  object-fit: cover;
}
</style>

<script>
import { router, useForm } from '@inertiajs/vue3'
import ActionMessage from '@/Components/ActionMessage.vue'

export default {
  components: {
    ActionMessage
  },

  props: {
    user: Object,
  },

  data() {
    return {
      form: useForm({
        _method: 'PUT',
        name: this.user.name,
        email: this.user.email,
        photo: null,
      }),
      verificationLinkSent: null,
      photoPreview: null,
    }
  },

  methods: {
    updateProfileInformation() {
      if (this.$refs.photoInput) {
        this.form.photo = this.$refs.photoInput.files[0]
      }

      this.form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => this.clearPhotoFileInput(),
      })
    },

    sendEmailVerification() {
      this.verificationLinkSent = true
    },

    selectNewPhoto() {
      this.$refs.photoInput.click()
    },

    updatePhotoPreview() {
      const photo = this.$refs.photoInput.files[0]

      if (!photo) return

      const reader = new FileReader()

      reader.onload = (e) => {
        this.photoPreview = e.target.result
      }

      reader.readAsDataURL(photo)
    },

    deletePhoto() {
      router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
          this.photoPreview = null
          this.clearPhotoFileInput()
        },
      })
    },

    clearPhotoFileInput() {
      if (this.$refs.photoInput?.value) {
        this.$refs.photoInput.value = null
      }
    },
  },
}
</script>
