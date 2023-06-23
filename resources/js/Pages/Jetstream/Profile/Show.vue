<template>
  <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
          <div v-if="$page.props.jetstream.canUpdateProfileInformation">
              <UpdateProfileInformationForm :user="$page.props.user" />

              <SectionBorder />
          </div>

          <div v-if="$page.props.jetstream.canUpdatePassword">
              <UpdatePasswordForm class="mt-10 sm:mt-0" />

              <SectionBorder />
          </div>

          <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
              <TwoFactorAuthenticationForm
                  :requires-confirmation="confirmsTwoFactorAuthentication"
                  class="mt-10 sm:mt-0"
              />

              <SectionBorder />
          </div>

          <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

          <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
              <SectionBorder />

              <DeleteUserForm class="mt-10 sm:mt-0" />
          </template>
      </div>
  </div>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from './Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/Jetstream/SectionBorder.vue';
import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

export default {
  components: {
    DeleteUserForm,
    LogoutOtherBrowserSessionsForm,
    SectionBorder,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm
  },

  props: {
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array
  },

  layout: (h, page) => h(AdminLayout, { title: 'Profile' }, () => page),
}
</script>
