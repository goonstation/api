<template>
  <q-card flat bordered>
    <q-item>
      <q-item-section>
        <q-item-label
          >Pull Request
          <a :href="`https://github.com/goonstation/goonstation/pull/${pr.number}`" target="_blank"
            >#{{ pr.number }}</a
          >
        </q-item-label>
        <q-item-label caption>{{ pr.title }}</q-item-label>
        <q-item-label v-if="pr.head?.sha" caption>
          Latest commit is
          <a
            :href="`https://github.com/goonstation/goonstation/pull/${pr.number}/commits/${pr.head.sha}`"
            target="_blank"
          >
            {{ pr.head.sha.substr(0, 7) }}
          </a>
        </q-item-label>
      </q-item-section>
      <q-item-section side>
        <q-item-label caption> Created {{ $formats.fromNow(pr.created_at) }} </q-item-label>
        <q-item-label caption>
          By
          <a :href="pr.user?.html_url" target="_blank">{{ pr.user?.login }}</a>
        </q-item-label>
      </q-item-section>
    </q-item>
  </q-card>
</template>

<script>
export default {
  props: {
    pr: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },
}
</script>
