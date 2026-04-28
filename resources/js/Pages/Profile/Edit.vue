<script setup lang="ts">
import { Card, CardContent } from '@/Components/ui/card';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

const { t } = useI18n();

defineProps<{
  mustVerifyEmail: boolean;
  status?: string;
}>();
</script>

<template>
  <Head :title="t('profile.title')" />

  <AuthenticatedLayout>
    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <Card class="mx-auto max-w-2xl">
          <CardContent class="p-6 sm:p-8">
            <UpdateProfileInformationForm
              :must-verify-email="mustVerifyEmail"
              :status="status"
            />
          </CardContent>
        </Card>

        <Card class="mx-auto max-w-2xl">
          <CardContent class="p-6 sm:p-8">
            <UpdatePasswordForm />
          </CardContent>
        </Card>

        <Card class="mx-auto max-w-2xl">
          <CardContent class="p-6 sm:p-8">
            <DeleteUserForm />
          </CardContent>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
