<template>
  <Head :title="niceTitle">
    <meta head-key="title" name="title" :content="niceTitle" />
    <meta head-key="description" name="description" :content="meta.description" />

    <meta head-key="og:title" property="og:title" :content="niceTitle" />
    <meta head-key="og:description" property="og:description" :content="meta.description" />
    <meta head-key="og:image" property="og:image" :content="meta.image" />
    <meta head-key="og:url" property="og:url" :content="meta.url" />

    <meta head-key="twitter:url" property="twitter:url" :content="meta.url" />
    <meta head-key="twitter:title" property="twitter:title" :content="niceTitle" />
    <meta
      head-key="twitter:description"
      property="twitter:description"
      :content="meta.description"
    />
    <meta head-key="twitter:image" property="twitter:image" :content="meta.image" />
  </Head>
</template>

<script>
import { Head } from '@inertiajs/vue3'

export default {
  components: {
    Head,
  },

  props: {
    title: String,
  },

  computed: {
    meta() {
      return this.$page.props.meta
    },

    niceTitle() {
      if (this.title) {
        return (
          this.title.replace(/#(\d+)?/g, (match, contents) => {
            return '#' + this.$formats.number(contents)
          }) + ' - Goonhub'
        )
      } else {
        return this.meta.title
      }
    },
  },
}
</script>
