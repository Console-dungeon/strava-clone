<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps({
  mustVerifyEmail: { type: Boolean },
  status: { type: String },
});

const user = usePage().props.auth.user;

const form = useForm({
  name: user.name,
  email: user.email,
});
</script>

<template>
  <section>
    <header>
      <h2 class="text-foreground text-lg font-medium">
        {{ t('profile.information.title') }}
      </h2>
      <p class="text-muted-foreground mt-1 text-sm">
        {{ t('profile.information.description') }}
      </p>
    </header>

    <form
      @submit.prevent="form.patch(route('profile.update'))"
      class="mt-6 space-y-6"
    >
      <div>
        <InputLabel for="name" :value="t('profile.information.name')" />
        <Input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />
        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div>
        <InputLabel for="email" :value="t('profile.information.email')" />
        <Input
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-muted-foreground mt-2 text-sm">
          {{ t('profile.information.unverified') }}
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="text-foreground hover:text-muted-foreground underline focus:outline-none"
          >
            {{ t('profile.information.resend') }}
          </Link>
        </p>
        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 text-sm font-medium text-green-600"
        >
          {{ t('profile.information.verificationSent') }}
        </div>
      </div>

      <div class="flex items-center gap-4">
        <Button :disabled="form.processing">{{
          t('profile.information.save')
        }}</Button>
        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-muted-foreground text-sm"
          >
            {{ t('profile.information.saved') }}
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
