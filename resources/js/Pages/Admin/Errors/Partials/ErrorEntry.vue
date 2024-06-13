<template>
  <tr>
    <td>{{ createdAt }}</td>
    <td class="error-message">
      <div class="error-card">
        <div class="error-card__content"
          >This error has occurred <strong>{{ $formats.number(errorCount) }}</strong> time<template
            v-if="errorCount !== 1"
            >s</template
          >
          this round.
        </div>
      </div>
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

:deep(pre) {
  margin: 0;
}

:deep(.error-message-highlight) {
  background: yellow;
  color: black;
}

:deep(.error-card) {
  margin: 5px 0;
}

:deep(.error-card__title) {
  display: inline-block;
  padding: 5px 8px;
  background: #222;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  font-weight: bold;
  font-size: 0.85em;
}

:deep(.error-card__content) {
  background: #333;
  padding: 5px 8px;
  border-bottom-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
  white-space: pre-wrap;
  word-break: break-work;
}
</style>

<script>
import { date } from 'quasar'

export default {
  props: {
    error: Object,
    relativeTimestamps: Boolean,
    roundStartedAt: String,
    searchTerms: Array,
    errorCount: Number,
  },

  computed: {
    createdAt() {
      let dateToFormat
      if (this.relativeTimestamps) {
        const msDiff = new Date(this.error.created_at) - new Date(this.roundStartedAt)
        dateToFormat = date.addToDate(new Date('2000-01-1'), { milliseconds: msDiff })
      } else {
        dateToFormat = new Date(this.error.created_at)
      }
      return date.formatDate(dateToFormat, 'HH:mm:ss.SSS')
    },

    message() {
      let message = `<div class="error-card">
        <div class="error-card__title">Summary</div>
        <div class="error-card__content"><strong>${this.error.file}</strong>, line <strong>${this.error.line}</strong>: ${this.error.name}</div>
      </div>`
      if (this.error.user || this.error.user_ckey) {
        message += `<div class="error-card">
          <div class="error-card__title">User</div>
          <div class="error-card__content">${this.error.user}`
        if (this.error.user_ckey) {
          message += ` (${this.error.user_ckey})`
        }
        message += `</div></div>`
      }
      if (this.error.desc) {
        message += `<div class="error-card">
          <div class="error-card__title">Description</div>
          <div class="error-card__content"><pre style="overflow-x: auto;">${this.error.desc}</pre></div>
        </div>`
      }

      if (this.searchTerms.length) {
        const searchRegex = new RegExp(
          this.searchTerms.map((term) => `(${this.escapeRegExp(term)})`).join('|'),
          'gim'
        )

        const renderer = document.createElement('div')
        renderer.innerHTML = message
        this.iterateTextNodes(renderer, (node) => {
          const newContent = node.nodeValue.replace(
            searchRegex,
            '<span class="error-message-highlight">$&</span>'
          )
          node.replaceWith(document.createRange().createContextualFragment(newContent))
        })

        return renderer.innerHTML
      }

      return message
    },
  },

  methods: {
    escapeRegExp(string) {
      return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    },

    iterateTextNodes(container, callback) {
      Array.from(container.childNodes).forEach((node) => {
        if (node.nodeType === node.TEXT_NODE) {
          callback(node)
        } else {
          this.iterateTextNodes(node, callback)
        }
      })
    },
  },
}
</script>
