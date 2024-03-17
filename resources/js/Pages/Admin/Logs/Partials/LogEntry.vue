<template>
  <tr class="log-type-row" :class="`log-type-${log.type}`">
    <td>{{ createdAt }}</td>
    <td>
      <q-chip class="log-type-label" :class="`log-type-label-${log.type}`" square dense>
        <div class="content">{{ log.type }}</div>
      </q-chip>
    </td>
    <td class="log-message" style="white-space: pre-wrap">
      <div v-html="message"></div>
    </td>
  </tr>
</template>

<style lang="scss" scoped>
.q-chip {
  width: 100%;
  font-size: 12px;

  .content {
    width: 100%;
    text-align: center;
  }
}

:deep(a) {
  display: inline-block;
  white-space: normal;
}

:deep(a #innerContent) {
  color: black;
}

:deep(.name) {
  font-weight: bold;
  color: #ffd125;
}

:deep(.log-player) {
  display: inline-block;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid grey;
  border-radius: 3px;
  padding: 0 6px;
}

:deep(.log-loc) {
  color: #ffd125;
}

:deep(.log-loc-name) {
  font-weight: bold;
}

:deep(.log-damage) {
  white-space: nowrap;
}

:deep(.log-damage span) {
  display: inline-block;
  padding: 0 4px;
}

:deep(.dmg-brain) {
  background: grey;
}
:deep(.dmg-oxy) {
  background: powderblue;
  color: black;
}
:deep(.dmg-tox) {
  background: lightgreen;
  color: black;
}
:deep(.dmg-burn) {
  background: burlywood;
  color: black;
}
:deep(.dmg-brute) {
  background: deeppink;
}
</style>

<script>
export default {
  props: {
    log: Object,
  },

  computed: {
    createdAt() {
      const opts = {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      }
      return new Date(this.log.created_at).toLocaleDateString('en-GB', opts)
    },

    message() {
      let message = this.log.source + ' ' + this.log.message

      const poptsRegex =
        /<a href='\?src=%admin_ref%;action=adminplayeropts;targetckey=.*?' title='Player Options'>(.*?)<\/a>/g
      message = message.replaceAll(poptsRegex, '<span class="log-player">$1</span>')

      const locRegex =
        /\(<a href='\?src=%admin_ref%;action=jumptocoords;.*title='Jump to Coords'>(.*)<\/a> in (.*?)(\)?)\)/g
      message = message.replaceAll(
        locRegex,
        '<span class="log-loc"><span class="log-loc-name">$2$3</span> ($1)</span>'
      )

      const privMsgRegex = /<a href="byond:\/\/\?action=priv_msg&target=.*?">(.*?)<\/a>/g
      message = message.replaceAll(privMsgRegex, '$1')

      const damageRegex = /\(<b>Damage:<\/b> <i>(.*?), (.*?), (.*?), (.*?), (.*?)<\/i>\)/g
      message = message.replaceAll(
        damageRegex,
        `<span class="log-damage">
        <span class="dmg-brain" title="Brain">$1</span>
        <span class="dmg-oxy" title="Oxygen">$2</span>
        <span class="dmg-tox" title="Toxin">$3</span>
        <span class="dmg-burn" title="Burn">$4</span>
        <span class="dmg-brute" title="Burn">$5</span>
      </span>`
      )

      return message
    },
  },
}
</script>
