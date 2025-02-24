import pluginJs from '@eslint/js'
import eslintConfigPrettier from 'eslint-config-prettier'
import pluginVue from 'eslint-plugin-vue'
import globals from 'globals'

/** @type {import('eslint').Linter.Config[]} */
export default [
  { files: ['**/*.{js,mjs,cjs,vue}'] },
  {
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
        route: true,
        axios: true,
        Pusher: true,
        Echo: true,
        Ziggy: true,
      },
    },
  },
  pluginJs.configs.recommended,
  ...pluginVue.configs['flat/essential'],
  eslintConfigPrettier,
  {
    rules: {
      'vue/multi-word-component-names': 'off',
    },
  },
]
