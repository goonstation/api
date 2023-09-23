<template>
  <!-- register / login
  select subscription
  payment
  submit -->
  <q-card class="gh-card q-mb-lg" flat>
    <q-card-section> foo </q-card-section>
  </q-card>

  <q-stepper v-model="step" vertical color="primary" animated flat>
    <q-step :name="1" title="Select donation type" icon="settings" :done="step > 1">
      <q-card class="gh-card" flat>
        <q-card-section>
          <q-card class="q-mb-md" bordered flat>
            <q-card-section> What type of donation would you like to make? </q-card-section>
          </q-card>

          <div class="flex no-wrap payment-type-buttons">
            <q-btn
              @click="paymentType = 'once'"
              :color="paymentType === 'once' ? 'primary' : 'default'"
              :text-color="paymentType === 'once' ? 'dark' : 'white'"
              class="bordered"
            >
              <div>One Time</div>
            </q-btn>
            <span class="q-mx-md text-h6 self-center">or</span>
            <q-btn
              @click="paymentType = 'subscription'"
              :color="paymentType === 'subscription' ? 'primary' : 'default'"
              :text-color="paymentType === 'subscription' ? 'dark' : 'white'"
              class="bordered"
            >
              <div>Subscription</div>
            </q-btn>
          </div>
        </q-card-section>
      </q-card>

      <q-stepper-navigation>
        <q-btn @click="step = 2" color="primary" text-color="dark" label="Continue" />
      </q-stepper-navigation>
    </q-step>

    <q-step :name="2" title="Set up account" icon="settings" :done="step > 2">
      <div class="row">
        <div class="col-md-6">
          <template v-if="showLogin">
            <login @success="step = 3" />
            <q-btn
              class="q-mt-sm"
              label="I don't have an account"
              @click="showLogin = false"
              text
            />
          </template>
          <template v-else>
            <register @success="step = 3" />
            <q-btn
              class="q-mt-sm"
              label="I already have an account"
              @click="showLogin = true"
              text
            />
          </template>
        </div>
      </div>
    </q-step>
  </q-stepper>
</template>

<style lang="scss" scoped>
.payment-type-buttons {
  button {
    border-color: $grey-8;
    width: 100%;
    max-width: 200px;
    padding-top: 20px;
    padding-bottom: 20px;
  }
}
</style>

<script>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Login from './Partials/Login.vue'
import Register from './Partials/Register.vue'

export default {
  layout: (h, page) => h(AppLayout, { title: 'Donate' }, () => page),

  setup() {
    return {
      router,
    }
  },

  components: {
    Login,
    Register,
  },

  data() {
    return {
      step: 1,
      paymentType: 'once',
      showLogin: false,
    }
  },

  computed: {
    currentUrl() {
      return window.location.href
    },

    isLoggedIn() {
      return !!this.$page.props.user
    },
  },

  created() {
    if (this.isLoggedIn) this.step = 2
  },
}
</script>
