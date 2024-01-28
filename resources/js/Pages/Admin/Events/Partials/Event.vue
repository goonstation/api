<template>
  <tr>
    <td>{{ eventData.type }}</td>
    <td>{{ eventData.data.round_id }}</td>
    <td>{{ eventData.data.created_at }}</td>
    <td style="white-space: pre-wrap;">
      <q-chip v-for="([key, value]) in Object.entries(eventSpecificData)" style="font-size: 13px;" square>
        <q-avatar color="grey" text-color="black">{{ key }}</q-avatar>
        {{ value }}
      </q-chip>
    </td>
  </tr>
</template>

<style lang="scss" scoped>
.q-avatar {
  width: auto;
  padding-left: 5px;
  padding-right: 5px;
}
</style>

<script>
export default {
  props: {
    event: String
  },

  computed: {
    eventData() {
      return JSON.parse(this.event)
    },

    eventSpecificData() {
      const clone = {...this.eventData.data}
      delete clone.round_id
      delete clone.player_id
      delete clone.created_at
      return clone
    }
  }
}
</script>
