<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Button } from '@/Components/ui/button';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const { canResetPassword, status } = defineProps<{
  canResetPassword?: boolean;
  status?: string;
}>();

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};

const fillDevCredentials = () => {
  form.email = 'nadia@doe.com';
  form.password = 'password';
  submit();
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div class="mb-6 flex justify-center">
      <Link href="/">
        <ApplicationLogo />
      </Link>
    </div>

    <Card class="w-full sm:w-md">
      <CardHeader>
        <CardTitle class="text-xl">Welcome back</CardTitle>
        <CardDescription>Sign in to your account</CardDescription>
      </CardHeader>

      <CardContent>
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
          {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-4">
          <div class="flex flex-col gap-1.5">
            <label for="email" class="text-foreground text-sm font-medium"
              >Email</label
            >
            <Input
              id="email"
              type="email"
              v-model="form.email"
              placeholder="you@example.com"
              required
              autofocus
              autocomplete="username"
            />
            <p v-if="form.errors.email" class="text-destructive text-sm">
              {{ form.errors.email }}
            </p>
          </div>

          <div class="flex flex-col gap-1.5">
            <label for="password" class="text-foreground text-sm font-medium"
              >Password</label
            >
            <Input
              id="password"
              type="password"
              v-model="form.password"
              placeholder="••••••••"
              required
              autocomplete="current-password"
            />
            <p v-if="form.errors.password" class="text-destructive text-sm">
              {{ form.errors.password }}
            </p>
          </div>

          <div class="flex items-center gap-2">
            <input
              id="remember"
              type="checkbox"
              v-model="form.remember"
              class="border-border text-primary focus:ring-ring rounded"
            />
            <label for="remember" class="text-muted-foreground text-sm"
              >Remember me</label
            >
          </div>

          <Button
            type="submit"
            class="w-full"
            :class="{ 'opacity-50': form.processing }"
            :disabled="form.processing"
          >
            Sign in
          </Button>

          <Button
            type="button"
            variant="outline"
            @click="fillDevCredentials"
            class="w-full border-purple-500 text-xs"
          >
            Click here, you lazy bastard - I got you ❤
          </Button>
        </form>
      </CardContent>

      <CardFooter
        class="text-muted-foreground flex justify-center gap-4 text-sm"
      >
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="hover:text-foreground underline transition-colors"
        >
          Forgot your password?
        </Link>
        <Link
          :href="route('register')"
          class="hover:text-foreground underline transition-colors"
        >
          Register
        </Link>
      </CardFooter>
    </Card>
  </GuestLayout>
</template>
