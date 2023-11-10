<template>
  <q-card class="gh-card" flat style="width: 100%; max-width: 500px">
    <div class="gh-card__header">
      <q-icon :name="ionPersonAdd" size="22px" />
      <span>Register</span>
    </div>

    <q-card-section>
      <q-banner v-if="$page.props.flash.error" class="bg-negative q-mb-md" dense>
        {{ $page.props.flash.error }}
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
          :error="!!form.errors.password_confirmation"
          :error-message="form.errors.password_confirmation"
        />

        <div class="flex">
          <Link :href="route('login')"> Already registered? </Link>
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
import { ionPersonAdd } from '@quasar/extras/ionicons-v6'
import { Link, useForm } from '@inertiajs/vue3'
import AuthLayout from '@/Layouts/AuthLayout.vue'

export default {
  layout: (h, page) => h(AuthLayout, { title: 'Register' }, () => page),

  components: {
    Link,
  },

  setup() {
    return {
      ionPersonAdd
    }
  },

  data() {
    return {
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
    submit() {
      this.form.post(route('register'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      })
    },
  },
}
</script>
