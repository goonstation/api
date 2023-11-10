<script setup>
import { ref, computed, watch } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue'

const props = defineProps({
  requiresConfirmation: Boolean,
})

const enabling = ref(false)
const confirming = ref(false)
const disabling = ref(false)
const qrCode = ref(null)
const setupKey = ref(null)
const recoveryCodes = ref([])

const confirmationForm = useForm({
  code: '',
})

const twoFactorEnabled = computed(() => !enabling.value && usePage().props.user?.two_factor_enabled)

watch(twoFactorEnabled, () => {
  if (!twoFactorEnabled.value) {
    confirmationForm.reset()
    confirmationForm.clearErrors()
  }
})

const enableTwoFactorAuthentication = () => {
  enabling.value = true

  router.post(
    '/user/two-factor-authentication',
    {},
    {
      preserveScroll: true,
      onSuccess: () => Promise.all([showQrCode(), showSetupKey(), showRecoveryCodes()]),
      onFinish: () => {
        enabling.value = false
        confirming.value = props.requiresConfirmation
      },
    }
  )
}

const showQrCode = () => {
  return axios.get('/user/two-factor-qr-code').then((response) => {
    qrCode.value = response.data.svg
  })
}

const showSetupKey = () => {
  return axios.get('/user/two-factor-secret-key').then((response) => {
    setupKey.value = response.data.secretKey
  })
}

const showRecoveryCodes = () => {
  return axios.get('/user/two-factor-recovery-codes').then((response) => {
    recoveryCodes.value = response.data
  })
}

const confirmTwoFactorAuthentication = () => {
  confirmationForm.post('/user/confirmed-two-factor-authentication', {
    errorBag: 'confirmTwoFactorAuthentication',
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      confirming.value = false
      qrCode.value = null
      setupKey.value = null
    },
  })
}

const regenerateRecoveryCodes = () => {
  axios.post('/user/two-factor-recovery-codes').then(() => showRecoveryCodes())
}

const disableTwoFactorAuthentication = () => {
  disabling.value = true

  router.delete('/user/two-factor-authentication', {
    preserveScroll: true,
    onSuccess: () => {
      disabling.value = false
      confirming.value = false
    },
  })
}
</script>

<template>
  <q-card flat>
    <q-card-section>
      <h4 v-if="twoFactorEnabled && !confirming" class="text-lg q-mt-none q-mb-md">
        You have enabled two factor authentication.
      </h4>

      <h4 v-else-if="twoFactorEnabled && confirming" class="text-lg q-mt-none q-mb-md">
        Finish enabling two factor authentication.
      </h4>

      <h4 v-else class="text-lg q-mt-none q-mb-md">
        You have not enabled two factor authentication.
      </h4>

      <div class="text-body2">
        <p>
          When two factor authentication is enabled, you will be prompted for a secure, random token
          during authentication. You may retrieve this token from your phone's Google Authenticator
          application.
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="text-body2">
            <p v-if="confirming">
              To finish enabling two factor authentication, scan the following QR code using your
              phone's authenticator application or enter the setup key and provide the generated OTP
              code.
            </p>

            <p v-else>
              Two factor authentication is now enabled. Scan the following QR code using your
              phone's authenticator application or enter the setup key.
            </p>
          </div>

          <div
            v-html="qrCode"
            class="q-pa-xs bg-white bordered inline-block rounded-borders"
            style="line-height: 1"
          />

          <div v-if="setupKey" class="q-mt-sm text-sm">
            <p>Setup Key: <span v-html="setupKey"></span></p>
          </div>

          <div v-if="confirming" class="mt-4">
            <q-input
              v-model="confirmationForm.code"
              class="q-mb-sm"
              type="text"
              inputmode="numeric"
              label="Code"
              filled
              required
              hide-bottom-space
              autofocus
              autocomplete="one-time-code"
              :error="!!confirmationForm.errors.code"
              :error-message="confirmationForm.errors.code"
            />
          </div>
        </div>

        <div v-if="recoveryCodes.length > 0 && !confirming">
          <div class="text-body2">
            <p>
              Store these recovery codes in a secure password manager. They can be used to recover
              access to your account if your two factor authentication device is lost.
            </p>
          </div>

          <div class="q-pa-md text-sm rounded-lg bg-grey-10">
            <div v-for="code in recoveryCodes" :key="code" class="q-my-xs">
              {{ code }}
            </div>
          </div>
        </div>
      </div>

      <div class="q-mt-lg">
        <div v-if="!twoFactorEnabled">
          <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
            <q-btn
              label="Enable"
              type="button"
              color="primary"
              text-color="black"
              :loading="enabling"
            />
          </ConfirmsPassword>
        </div>

        <div v-else>
          <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
            <q-btn
              v-if="confirming"
              label="Confirm"
              type="button"
              color="primary"
              text-color="black"
              class="q-mr-sm"
              :loading="enabling"
            />
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
            <q-btn
              v-if="recoveryCodes.length > 0 && !confirming"
              label="Regenerate Recovery Codes"
              type="button"
              color="grey"
              class="q-mr-sm"
              outline
            />
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="showRecoveryCodes">
            <q-btn
              v-if="recoveryCodes.length === 0 && !confirming"
              label="Show Recovery Codes"
              type="button"
              color="grey"
              class="q-mr-sm"
              outline
            />
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <q-btn
              v-if="confirming"
              label="Cancel"
              type="button"
              color="grey"
              outline
              :loading="disabling"
            />
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <q-btn
              v-if="!confirming"
              label="Disable"
              type="button"
              color="negative"
              outline
              :loading="disabling"
            />
          </ConfirmsPassword>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>
