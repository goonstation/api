<template>
  <q-dialog v-model="open" persistent>
    <q-card class="gh-card text-center" style="width: 300px" flat bordered>
      <div class="gh-card__header q-pa-md bordered justify-center">
        <span>Game Authentication</span>
      </div>

      <q-card-section>
        <p>You logged in from {{ server.short_name }}.</p>

        <div v-if="loading">
          <p>Contacting the game server...</p>
          <q-spinner color="primary" size="3em" />
        </div>

        <p v-else>
          <template v-if="success">
            Successfully contacted game server, you may switch back to the game window now.
          </template>
          <template v-else>
            <template v-if="error">
              {{ error }}
            </template>
            <template v-else>
              Failed to contact game server, please switch back to the game window and try again.
            </template>
          </template>
        </p>
      </q-card-section>

      <q-card-actions align="center" class="text-primary">
        <q-btn :label="buttonLabel" flat color="primary" @click="open = false" :disabled="loading" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    serverId: {
      type: [Boolean, String],
      required: true,
      default: false,
    },
    server: {
      type: [Object, null],
      required: true,
      default: null,
    },
  },

  data() {
    return {
      open: false,
      loading: true,
      success: false,
      error: null,
    }
  },

  computed: {
    buttonLabel() {
      if (this.loading) {
        return 'Hold your horses'
      } else if (this.success) {
        return 'Ok cool'
      } else {
        return 'Ruh roh'
      }
    }
  },

  mounted() {
    if (this.serverId) {
      this.open = true
      this.informGame(this.serverId)
    }
  },

  methods: {
    async informGame(serverId) {
      try {
        const response = await axios.post(route('admin.game-auth-callback'), {
          server_id: serverId,
        })
        this.success = response.data.success
      } catch (e) {
        this.error =
          e.response.data.message ||
          'Failed to contact game server, please switch back to the game window and try again.'
        this.success = false
      }

      this.loading = false
    },
  },
}
</script>
