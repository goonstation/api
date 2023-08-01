<template>
  <q-form @submit="submit">
    <slot :form="form" />
  </q-form>
</template>

<script>
import { useForm } from '@inertiajs/vue3'

export default {
  props: {
    fields: {
      type: Object,
      default: () => ({}),
      required: true,
    },
    submitRoute: {
      type: String,
      required: true,
    },
    submitMethod: {
      type: String,
      required: false,
      default: 'post'
    },
    successMessage: {
      type: String,
      required: true,
    }
  },

  data: () => {
    return {
      form: {}
    }
  },

  created() {
    this.form = useForm(this.fields)
  },

  methods: {
    submit() {
      this.$emit('submit')
      this.form.submit(this.submitMethod, this.submitRoute, {
        onSuccess: () => {
          this.$emit('success')
          this.$q.notify({
            message: this.successMessage,
            color: 'positive',
          })
        },
      })
    }
  }
}
</script>
