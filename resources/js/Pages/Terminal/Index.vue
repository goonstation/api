<template>
  <Head title="Terminal" />
  <div ref="terminal" class="jsterm-wrapper"></div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import './jsterm/css/style.css'
import LoggedOutCommands from './jsterm/js/commands/loggedout'
import Terminal from './jsterm/js/jsterm'
import Filesystem from './jsterm/js/filesystem.json'

const config = {
  prompt: function (cwd, user) {
    if (user && user === 'hydro4')
      return 'hydro4@nstation ['+cwd+'] > '
    return '> '
  },
  username: '',
  sudoer: false
}

export default {
  components: {
    Head
  },

  mounted() {
    Terminal.init(config, Filesystem, LoggedOutCommands, () => {
      Terminal.begin(this.$refs.terminal)
    })
  },
}
</script>
