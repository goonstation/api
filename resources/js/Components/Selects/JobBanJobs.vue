<template>
  <q-select v-bind="$attrs" :options="jobs" input-debounce="0" use-input @filter="filterFn">
    <template v-slot:no-option>
      <q-item>
        <q-item-section class="text-grey"> No results </q-item-section>
      </q-item>
    </template>
  </q-select>
</template>

<script>
const jobs = [
  'Chief Engineer',
  'Engineer',
  'Miner',
  'Security Officer',
  'Detective',
  'Geneticist',
  'Pathologist',
  'Scientist',
  'Medical Doctor',
  'Head of Personnel',
  'Head of Security',
  'Research Director',
  'Medical Director',
  'Chaplain',
  'Roboticist',
  'AI',
  'Cyborg',
  'Bartender',
  'Chef',
  'Janitor',
  'Clown',
  'Quartermaster',
  'Botanist',
  'Captain',
  'Syndicate',
  'Special Respawn',
  'Engineering Department',
  'Security Department',
  'Heads of Staff',
  'Everything Except Assistant',
  'Ghostdrone',
  'Custom Names',
].sort()

export default {
  data() {
    return {
      jobs,
    }
  },

  methods: {
    filterFn(val, update) {
      if (val === '') {
        update(() => {
          this.jobs = jobs
        })
        return
      }

      update(() => {
        const needle = val.toLowerCase()
        this.jobs = jobs.filter((v) => v.toLowerCase().indexOf(needle) > -1)
      })
    },
  },
}
</script>
