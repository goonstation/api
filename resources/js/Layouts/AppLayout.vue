<template>
  <Head :title="niceTitle" />
  <q-layout view="lhh LpR fff">
    <KeepAlive>
      <page-header :title="niceTitle" @onToggleLeftDrawer="toggleLeftDrawer" />
    </KeepAlive>

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left" :width="175" :breakpoint="600">
      <q-scroll-area class="fit">
        <q-list class="site-nav" ref="siteNav">
          <a class="block q-mt-lg q-mb-md logo" @click.prevent="router.visit('/')" href="/">
            <img src="@img/logo.png" alt="Logo" class="block q-mx-auto" width="100" height="97" />
          </a>
          <template v-for="(menuItem, index) in menuList" :key="index">
            <q-expansion-item
              v-if="menuItem.children"
              class="site-nav__item site-nav__item-parent"
              :class="[
                {
                  'site-nav__item-parent--active': menuItem.expandedActive,
                },
                `site-nav__item-${index}`,
              ]"
              :expand-icon="ionChevronDown"
              dense-toggle
              v-model="menuItem.active"
              @after-hide="onNavItemLeave"
              @after-show="onExpansionShow"
            >
              <template #header>
                <q-item-section class="site-nav__label q-pl-sm">
                  {{ menuItem.label }}
                </q-item-section>
              </template>

              <div class="site-nav__exp-content q-pl-sm">
                <q-item
                  v-for="(childItem, index) in menuItem.children"
                  class="site-nav__item q-px-lg"
                  :class="[
                    {
                      'site-nav__item--active': childItem.active,
                    },
                    `site-nav__item-${index}`,
                  ]"
                  clickable
                  @click.prevent="onNavItemClick($event, childItem.href)"
                  @focus="onNavItemEnter"
                  @blur="onNavItemLeave"
                  :href="childItem.href"
                  :active="childItem.active"
                  v-ripple
                >
                  <q-item-section class="site-nav__label">
                    {{ childItem.label }}
                  </q-item-section>
                </q-item>
              </div>
            </q-expansion-item>

            <q-item
              v-else
              class="site-nav__item q-px-lg"
              :class="[
                {
                  'site-nav__item--active': menuItem.active,
                },
                `site-nav__item-${index}`,
              ]"
              clickable
              @click.prevent="onNavItemClick($event, menuItem.href)"
              @focus="onNavItemEnter"
              @blur="onNavItemLeave"
              :href="menuItem.href"
              :active="menuItem.active"
              v-ripple
            >
              <q-item-section class="site-nav__label">
                {{ menuItem.label }}
              </q-item-section>
            </q-item>
            <q-separator :key="'sep' + index" v-if="menuItem.separator" />
          </template>
          <div class="site-nav__bar" :class="[animateNavBar && 'active']" ref="navSlider"></div>
        </q-list>
      </q-scroll-area>
    </q-drawer>

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
.site-nav {
  $self: &;
  position: relative;

  .logo {
    position: relative;

    &:before {
      content: '';
      background: url('@img/logo.png');
      background-size: contain;
      position: absolute;
      left: 50%;
      width: 100px;
      height: 96.5px;
      translate: -50%;
      scale: 1.1;
      transform-origin: center;
      filter: grayscale(1) brightness(500%);
      z-index: -1;
      opacity: 0.3;
      transition: all 400ms ease-in-out;
    }

    img {
      max-width: 100%;
      height: auto;
    }

    &:hover,
    &:focus {
      &:before {
        scale: 1.2;
        rotate: 360deg;
      }
    }
  }

  &__label {
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
  }

  &__exp-content {
    background: rgba(255, 255, 255, 0.075);

    #{$self}__label {
      font-size: 0.9em;
      font-weight: 500;
    }
  }

  &__bar {
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    width: 5px;
    opacity: 1;
    background: var(--q-primary);
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;

    &.active {
      transition: all 200ms;
    }
  }
}

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
import { Head, router } from '@inertiajs/vue3'
import { ionChevronDown } from '@quasar/extras/ionicons-v6'
import PageHeader from '@/Components/PageHeader.vue'

export default {
  setup() {
    return {
      router,
      ionChevronDown
    }
  },

  components: {
    Head,
    PageHeader,
  },

  props: {
    title: String,
  },

  data() {
    return {
      pageLoading: false,
      leftDrawerOpen: true,
      menuList: [
        {
          label: 'Home',
          href: '/',
          separator: false,
        },
        {
          label: 'Rounds',
          href: '/rounds',
          separator: false,
        },
        {
          label: 'Players',
          match: '/players',
          separator: false,
          children: [
            {
              label: 'Overview',
              href: '/players',
            },
            {
              label: 'Highscores',
              href: '/players/highscores',
            },
            {
              label: 'Search',
              href: '/players/search',
            },
          ],
        },
        {
          label: 'Events',
          href: '/events',
          separator: false,
        },
        {
          label: 'Maps',
          href: '/maps',
          separator: false,
        },
      ],
      navSlider: null,
      animateNavBar: false,
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

  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen
    },

    moveNavSliderTo(el) {
      if (!el) return
      const eleRect = el.getBoundingClientRect()
      const targetRect = this.$refs.siteNav.$el.getBoundingClientRect()
      const top = eleRect.top - targetRect.top

      this.$refs.navSlider.style.top = `${top}px`
      this.$refs.navSlider.style.height = `${el.offsetHeight}px`
    },

    onNavItemEnter(e) {
      this.moveNavSliderTo(e.target)
    },

    onNavItemLeave() {
      if (this.pageLoading) return
      let activeItem = document.querySelector('.site-nav__item--active')
      if (activeItem && !activeItem.offsetParent) {
        activeItem = document.querySelector('.site-nav__item-parent--active .q-item')
      }
      this.moveNavSliderTo(activeItem)
    },

    onNavItemClick(e, href) {
      this.moveNavSliderTo(e.target.closest('a'))
      router.visit(href)
    },

    checkItems(menuItems) {
      let activeItem
      menuItems.forEach((item) => {
        item.active = false
        item.expandedActive = false
        if (this.isActive(item)) activeItem = item
        if (item.children) {
          const activeChildItem = this.checkItems(item.children)
          if (activeChildItem) {
            item.expandedActive = true
            activeChildItem.active = true
          }
        }
      })
      return activeItem
    },

    isActive(menuItem) {
      const href = menuItem.match ? menuItem.match : menuItem.href
      if (href === '/' && this.$page.url === '/') return true
      else if (href !== '/') return this.$page.url.startsWith(href)
    },

    onExpansionShow() {
      this.moveNavSliderTo(document.querySelector(`.site-nav__item--active`))
    }
  },

  created() {
    router.on('start', () => {
      this.pageLoading = true
    })
    router.on('finish', () => {
      this.pageLoading = false
    })
  },

  mounted() {
    this.onNavItemLeave()
    this.animateNavBar = true

    const navItems = Array.from(this.$refs.siteNav.$el.querySelectorAll('.site-nav__item:not(.site-nav__item-parent)'))
    const parentNavItems = Array.from(this.$refs.siteNav.$el.querySelectorAll('.site-nav__item-parent .q-item'))
    navItems.concat(parentNavItems).forEach((item) => {
      item.addEventListener('mouseenter', this.onNavItemEnter)
      item.addEventListener('mouseleave', this.onNavItemLeave)
    })
  },

  watch: {
    '$page.url': {
      immediate: true,
      handler(val) {
        const activeItem = this.checkItems(this.menuList)
        activeItem.active = true

        setTimeout(() => {
          this.onNavItemLeave()
        }, 1)
      },
    },
  },

  // watch: {
  //   '$q.screen.width': {
  //     handler(val) {
  //       console.log(val, this.$q.screen.lt.md)

  //       if (this.$q.screen.lt.md && this.leftDrawerOpen) {
  //         this.leftDrawerOpen = false
  //       } else if (!this.$q.screen.lt.md && !this.leftDrawerOpen) {
  //         this.leftDrawerOpen = true
  //       }
  //     }
  //   }
  // }
}
</script>
