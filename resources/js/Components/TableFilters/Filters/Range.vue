<template>
  <q-btn class="gh-btn gh-btn--denser full-width" square flat>
    <div class="text-weight-regular">
      <template v-if="label">{{ label }}</template>
      <q-icon v-else :name="ionSwapHorizontal" />
    </div>
    <q-menu transition-show="scale" transition-hide="scale">
      <q-item>
        <q-item-section>
          <q-select
            class="q-mb-sm"
            v-model="operator"
            :options="operators"
            map-options
            emit-value
            square
            filled
            dense
          />
          <q-input
            class="q-mb-sm"
            v-model.number="amount"
            @keyup.native.enter="onEnter"
            type="number"
            square
            filled
            dense
          />
          <template v-if="operator === 'between'">
            <div class="text-center q-mb-sm">and</div>
            <q-input
              class="q-mb-sm"
              v-model.number="amount2"
              @keyup.native.enter="onEnter"
              type="number"
              square
              filled
              dense
            />
          </template>
          <q-btn @click="clear" flat>Clear</q-btn>
        </q-item-section>
      </q-item>
    </q-menu>
  </q-btn>
</template>

<style lang="scss" scoped>
.gh-btn {
  background: rgba(255, 255, 255, 0.07);
}
</style>

<script>
import { ionSwapHorizontal } from '@quasar/extras/ionicons-v6'
import { debounce } from 'lodash'

export default {
  props: ['modelValue'],

  setup() {
    return {
      ionSwapHorizontal,
    }
  },

  data() {
    return {
      label: '',
      operator: '=',
      amount: '',
      amount2: '',
      operators: [
        { value: '=', label: 'Equals' },
        { value: '>', label: 'Greater than' },
        { value: '>=', label: 'Greater or equal to' },
        { value: '<', label: 'Less than' },
        { value: '<=', label: 'Less or equal to' },
        { value: 'between', label: 'Between' },
      ],
    }
  },

  computed: {
    watchThis() {
      return [this.operator, this.amount, this.amount2]
    },
  },

  watch: {
    modelValue: {
      immediate: true,
      handler(val) {
        const group = val?.split(' ')
        if (group?.length) {
          this.operator = group.length === 1 ? 'between' : group[0]
          const amount = group.length === 1 ? group[0] : group[1]
          if (this.operator === 'between') {
            const range = amount.split('-')
            this.amount = parseInt(range[0])
            this.amount2 = parseInt(range[1])
          } else {
            this.amount = parseInt(amount)
          }
          this.label = this.getLabel(this.operator, this.amount, this.amount2)
        }
      },
    },

    watchThis: {
      handler: debounce(function (val) {
        this.$emit('update:modelValue', this.getLabel(val[0], val[1], val[2]))
      }, 1000),
    },
  },

  methods: {
    clear() {
      this.operator = '='
      this.amount = ''
      this.amount2 = ''
      this.label = ''
      this.$emit('update:modelValue', null)
    },

    getLabel(operator, amount, amount2) {
      if (!operator || amount === '') return
      let label = ''
      if (operator !== 'between') label += `${operator} `
      label += `${amount}`
      if (operator === 'between') {
        if (amount2 === '') return
        label += `-${amount2}`
      }
      return label
    },

    onEnter() {
      this.$emit('update:modelValue', this.getLabel(this.operator, this.amount, this.amount2))
    },
  },
}
</script>
