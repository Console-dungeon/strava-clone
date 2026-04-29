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
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <div class="mb-6 flex justify-center">
      <Link href="/">
        <ApplicationLogo />
      </Link>
    </div>

    <Card class="w-full sm:w-md">
      <CardHeader>
        <CardTitle class="text-xl">Create an account</CardTitle>
        <CardDescription>Fill in your details to get started</CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="submit" class="flex flex-col gap-4">
          <div class="flex flex-col gap-1.5">
            <Label for="name">Name</Label>
            <Input
              id="name"
              type="text"
              v-model="form.name"
              placeholder="John Doe"
              required
              autofocus
              autocomplete="name"
            />
            <p v-if="form.errors.name" class="text-destructive text-sm">
              {{ form.errors.name }}
            </p>
          </div>

          <div class="flex flex-col gap-1.5">
            <Label for="email">Email</Label>
            <Input
              id="email"
              type="email"
              v-model="form.email"
              placeholder="you@example.com"
              required
              autocomplete="username"
            />
            <p v-if="form.errors.email" class="text-destructive text-sm">
              {{ form.errors.email }}
            </p>
          </div>

          <div class="flex flex-col gap-1.5">
            <Label for="password">Password</Label>
            <Input
              id="password"
              type="password"
              v-model="form.password"
              placeholder="••••••••"
              required
              autocomplete="new-password"
            />
            <p v-if="form.errors.password" class="text-destructive text-sm">
              {{ form.errors.password }}
            </p>
          </div>

          <div class="flex flex-col gap-1.5">
            <Label for="password_confirmation">Confirm Password</Label>
            <Input
              id="password_confirmation"
              type="password"
              v-model="form.password_confirmation"
              placeholder="••••••••"
              required
              autocomplete="new-password"
            />
            <p
              v-if="form.errors.password_confirmation"
              class="text-destructive text-sm"
            >
              {{ form.errors.password_confirmation }}
            </p>
          </div>

          <Button
            type="submit"
            class="w-full"
            :class="{ 'opacity-50': form.processing }"
            :disabled="form.processing"
          >
            Register
          </Button>
        </form>
      </CardContent>

      <CardFooter class="text-muted-foreground flex justify-center text-sm">
        <Link
          :href="route('login')"
          class="hover:text-foreground underline transition-colors"
        >
          Already have an account?
        </Link>
      </CardFooter>
    </Card>
  </GuestLayout>
</template>
