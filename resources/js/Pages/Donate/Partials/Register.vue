<template>
  <q-card class="gh-card gh-card--small" flat bordered>
    <div class="gh-card__header">
      <q-icon :name="ionPersonAdd" size="18px" />
      <span>Register</span>
    </div>

    <q-card-section>
      <q-banner v-if="rateLimited" class="bg-negative text-dark q-mb-md" dense>
        Too many requests. Please try again later.
      </q-banner>

      <q-form @submit="submit">
        <q-input
          v-model="form.name"
          type="text"
          label="Name"
          autocomplete="name"
          filled
          lazy-rules
          required
          autofocus
          dense
          :error="!!form.errors.name"
          :error-message="form.errors.name"
        />

        <q-input
          v-model="form.email"
          type="email"
          label="Email"
          filled
          lazy-rules
          required
          dense
          :error="!!form.errors.email"
          :error-message="form.errors.email"
        />

        <q-input
          v-model="form.password"
          type="password"
          label="Password"
          autocomplete="new-password"
          filled
          lazy-rules
          required
          dense
          :error="!!form.errors.password"
          :error-message="form.errors.password"
        />

        <q-input
          v-model="form.password_confirmation"
          type="password"
          label="Confirm Password"
          autocomplete="new-password"
          filled
          lazy-rules
          required
          dense
          :error="!!form.errors.password_confirmation"
          :error-message="form.errors.password_confirmation"
        />

        <div class="flex">
          <q-space />
          <q-btn
            label="Register"
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
import { ionPersonAdd } from '@quasar/extras/ionicons-v6'

export default {
  setup() {
    return {
      ionPersonAdd,
    }
  },

  data() {
    return {
      rateLimited: false,
      form: useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
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
        .post(route('register'), {
          name: this.form.name,
          email: this.form.email,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation,
          terms: this.form.terms,
        })
        .then((response) => {
          this.form.reset('password', 'password_confirmation')
          this.$page.props.user = response.data
          this.$emit('success')
        })
        .catch((error) => {
          const errors = error.response.data.errors
          this.form.setError('name', this.getError(errors, 'name'))
          this.form.setError('email', this.getError(errors, 'email'))
          this.form.setError('password', this.getError(errors, 'password'))
          this.form.setError(
            'password_confirmation',
            this.getError(errors, 'password_confirmation')
          )
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
