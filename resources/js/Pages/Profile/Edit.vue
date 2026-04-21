<script setup lang="ts">
import { Card, CardContent } from '@/Components/ui/card';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps<{
  mustVerifyEmail: boolean;
  status?: string;
}>();
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <!-- <template #header>
      <h2 class="text-foreground text-xl leading-tight font-semibold">
        Profile
      </h2>
    </template> -->

    <div class="space-y-6">
      <Card>
        <CardContent class="p-6 sm:p-8">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
          />
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6 sm:p-8">
          <UpdatePasswordForm class="max-w-xl" />
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6 sm:p-8">
          <DeleteUserForm class="max-w-xl" />
        </CardContent>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>
