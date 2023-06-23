<template>
  <q-card flat class="event-count q-pa-sm">
    <q-card-section>
      <div class="text-weight-medium event-count__label q-mb-sm">
        {{ label }}
      </div>
      <div class="text-weight-bold text-lg text-primary">
        {{ $formats.number(count) }}
      </div>
      <img v-if="label === 'Bees'" src="@img/bee.gif" class="bee" />
    </q-card-section>
  </q-card>
</template>

<style lang="scss">
.event-count {
  line-height: 1;

  &__label {
    font-size: 1.1em;
  }

  .bee {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%) scaleX(-1);
    filter: grayscale(1);
    opacity: 0.5;
  }
}
</style>

<script>
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    count: {
      type: null,
      required: true,
    },
  },
}
</script>
