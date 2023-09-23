<template>
  <q-card class="gh-card gh-card--small" flat bordered>
    <div class="gh-card__header">
      <q-icon :name="ionLogIn" size="18px" />
      <span>Login</span>
    </div>

    <q-card-section>
      <q-banner v-if="rateLimited" class="bg-negative text-dark q-mb-md" dense>
        Too many requests. Please try again later.
      </q-banner>

      <q-form @submit="submit">
        <q-input
          v-model="form.email"
          type="email"
          label="Email"
          filled
          lazy-rules
          required
          autofocus
          dense
          :error="!!form.errors.email"
          :error-message="form.errors.email"
        />

        <q-input
          v-model="form.password"
          type="password"
          label="Password"
          filled
          lazy-rules
          required
          autocomplete="current-password"
          dense
          :error="!!form.errors.password"
          :error-message="form.errors.password"
        />

        <div class="flex items-center q-mb-sm">
          <q-toggle v-model="form.remember" label="Remember me" />
          <q-space />
          <!-- <Link v-if="canResetPassword" :href="route('password.request')">
            Forgot your password?
          </Link> -->
        </div>

        <div class="flex">
          <q-space />
          <q-btn
            label="Log In"
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

<script>
import { useForm } from '@inertiajs/vue3'
import { ionLogIn } from '@quasar/extras/ionicons-v6'

export default {
  setup() {
    return {
      ionLogIn,
    }
  },

  data() {
    return {
      rateLimited: false,
      form: useForm({
        email: '',
        password: '',
        remember: false,
      }),
    }
  },

  methods: {
    getError(errors, field) {
      if (!errors) return null
      if (errors[field] && errors[field].length) {
        return errors[field][0]
      }
      return null
    },

    submit() {
      this.form.processing = true
      this.rateLimited = false
      axios
        .post(route('login'), {
          email: this.form.email,
          password: this.form.password,
          remember: this.form.remember ? 'on' : '',
        })
        .then(() => {
          this.$emit('success')
        })
        .catch((error) => {
          const errors = error.response.data.errors
          this.form.setError('email', this.getError(errors, 'email'))
          this.form.setError('password', this.getError(errors, 'password'))
          if (error.response.status === 429) {
            this.rateLimited = true
          }
        })
        .finally(() => {
          this.form.processing = false
        })
    },
  },
}
</script>
