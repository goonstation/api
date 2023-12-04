<template>
  <Head :title="niceTitle" />
  <q-layout view="lhh LpR fff">
    <KeepAlive>
      <page-header :title="niceTitle" @onToggleLeftDrawer="siteNavOpen = !siteNavOpen" />
    </KeepAlive>

    <site-nav :items="siteNavItems" :is-open="siteNavOpen" />

    <q-page-container>
      <q-page class="row column no-wrap q-pa-md page-wrapper">
        <div>
          <slot />
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<style lang="scss" scoped>
.page-wrapper {
  position: relative;
  z-index: 2;
  border-radius: 20px;
  margin-top: -20px;
  background: var(--q-dark-page);
  background-clip: padding-box;
  border-top: 1px solid rgba(255, 255, 255, 0.1);

  > div {
    max-width: 1500px;
  }
}
</style>

<script>
import { Head } from '@inertiajs/vue3'
import PageHeader from '@/Components/PageHeader.vue'
import SiteNav from '@/Components/SiteNav/SiteNav.vue'

export default {
  components: {
    Head,
    PageHeader,
    SiteNav,
  },

  props: {
    title: String,
  },

  data() {
    return {
      siteNavOpen: true,
      siteNavItems: [
        {
          label: 'Home',
          href: route('home'),
        },
        {
          label: 'Rounds',
          href: route('rounds.index'),
        },
        {
          label: 'Players',
          match: route('players.index'),
          children: [
            {
              label: 'Overview',
              href: route('players.index'),
            },
            {
              label: 'Search',
              href: route('players.search'),
            },
          ],
        },
        {
          label: 'Events',
          match: route('events.index'),
          children: [
            {
              label: 'Overview',
              href: route('events.index'),
            },
            {
              label: 'Antagonists',
              href: route('antags.index'),
            },
            {
              label: 'Deaths',
              href: route('deaths.index'),
            },
            {
              label: 'Fines',
              href: route('fines.index'),
            },
            {
              label: 'Tickets',
              href: route('tickets.index'),
            },
          ],
        },
        {
          label: 'Maps',
          href: route('maps.index'),
        },
      ],
    }
  },

  computed: {
    niceTitle() {
      let title = this.title
      title = title.replace(/#(\d+)?/g, (match, contents) => {
        return '#' + this.$formats.number(contents)
      })
      return title
    },
  },
}
</script>
