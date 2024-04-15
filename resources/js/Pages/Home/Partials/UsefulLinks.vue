<template>
  <q-card flat class="q-mb-md q-py-sm">
    <div class="row items-center">
      <q-card-section class="col-12 col-sm-auto q-py-sm q-pr-none">
        <div class="links-title">Useful Links</div>
        <div class="links-subtitle">
          <q-icon :name="ionLink" class="q-mr-xs" />
          In the community
        </div>
      </q-card-section>
      <q-space />
      <div class="col-12 col-sm-auto q-px-sm links-grid">
        <q-btn
          v-for="link in links"
          unelevated
          stack
          color="grey-9"
          size="sm"
          padding="sm sm xs sm"
          type="a"
          target="_blank"
          :href="link.href"
          :icon="link.icon"
          :label="link.label"
        />
      </div>
    </div>
  </q-card>
</template>

<style lang="scss" scoped>
.links-title {
  font-size: 0.9em;
  font-weight: 600;
  letter-spacing: 0.5px;
  line-height: 1;
  margin-bottom: 5px;
}

.links-subtitle {
  font-size: 0.8em;
  letter-spacing: 0.5px;
  opacity: 0.8;
  line-height: 1;
}

.links-grid {
  flex-grow: 1;
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 4px;
}
</style>

<script>
import {
  ionLogoDiscord,
  ionChatbubbles,
  ionSchool,
  ionLogoGithub,
  ionLink,
  ionCash
} from '@quasar/extras/ionicons-v6'

export default {
  setup() {
    return {
      ionLogoDiscord,
      ionChatbubbles,
      ionSchool,
      ionLogoGithub,
      ionLink
    }
  },

  data: () => {
    return {
      links: [
        {
          href: 'https://discord.gg/zd8t6pY',
          icon: ionLogoDiscord,
          label: 'Discord',
        },
        {
          href: 'https://forum.ss13.co/',
          icon: ionChatbubbles,
          label: 'Forum',
        },
        {
          href: 'https://wiki.ss13.co/',
          icon: ionSchool,
          label: 'Wiki',
        },
        {
          href: 'https://github.com/goonstation/goonstation',
          icon: ionLogoGithub,
          label: 'GitHub',
        },
        {
          href: 'https://www.patreon.com/goonstation',
          icon: ionCash,
          label: 'Donate',
        },
      ],
    }
  },
}
</script>
