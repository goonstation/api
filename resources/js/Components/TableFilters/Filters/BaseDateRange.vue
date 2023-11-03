<script>
export default {
  props: ['modelValue'],

  computed: {
    dateRanges() {
      if (typeof this.modelValue === 'string') {
        return this.modelValue.split('-')
      }
      return this.modelValue
    },

    fromModel: {
      get() {
        let ret = this.dateRanges
        if (ret && typeof ret === 'object') ret = ret[0]
        return ret
      },
      set(val) {
        let ret = ''
        if (val) ret += val
        if (this.toModel) ret += `-${this.toModel}`
        this.$emit('update:modelValue', ret)
      },
    },

    toModel: {
      get() {
        let ret = this.dateRanges
        if (ret && typeof ret === 'object') ret = ret[1]
        return ret
      },
      set(val) {
        let ret = ''
        if (this.fromModel) ret += this.fromModel
        if (this.fromModel && val) ret += '-'
        if (val) ret += val
        this.$emit('update:modelValue', ret)
      },
    },
  },

  methods: {
    clearFrom() {
      this.fromModel = null
      this.$nextTick(() => {
        if (this.toModel) this.toModel = null
      })
    },

    clearTo() {
      this.toModel = null
    },
  },
}
</script>
