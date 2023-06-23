<template>
  <nav v-if="breadcrumbs && breadcrumbs.length > 1" aria-label="Breadcrumb">
    <ol>
      <li v-for="(crumb, idx) in breadcrumbs" :class="{ active: crumb.is_current_page }">
        <Link :href="crumb.url">
          {{ crumb.title }}
        </Link>
        <span v-if="idx < breadcrumbs.length - 1 && breadcrumbs[idx + 1].title" class="separator"
          ><q-icon :name="ionChevronForward" /></span
        >
      </li>
    </ol>
  </nav>
</template>

<style lang="scss">
nav {
  font-size: 0.7rem;
  font-weight: 500;
  line-height: 1;

  ol {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  a {
    display: inline-block;
    color: white;
    opacity: 0.7;
  }

  .separator {
    position: relative;
    top: -1px;
    display: inline-block;
    margin: 0 10px;
  }
}
</style>

<script>
import { Link } from '@inertiajs/vue3'
import { ionChevronForward } from '@quasar/extras/ionicons-v6'

export default {
  setup() {
    return {
      ionChevronForward
    }
  },

  components: {
    Link,
  },

  computed: {
    breadcrumbs() {
      return this.$page.props.breadcrumbs
    },
  },
}
</script>
