<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import Button from '@/Components/ui/button/Button.vue';
import Input from '@/Components/ui/input/Input.vue';
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

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <label
                    for="email"
                    class="block text-sm font-medium text-gray-700"
                    >Email</label
                >

                <Input
                    id="email"
                    type="email"
                    class="mt-1"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <div class="mt-4">
                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                    >Password</label
                >

                <Input
                    id="password"
                    type="password"
                    class="mt-1"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <p
                    v-if="form.errors.password"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="mt-4 flex justify-center">
                <Button
                    type="button"
                    variant="outline"
                    @click="fillDevCredentials"
                    class="cursor-pointer border-purple-500 text-xs"
                >
                    Click here, you lazy bastard - I got you ❤
                </Button>
            </div>

            <div class="mt-4 flex items-center justify-end gap-4">
                <div class="flex flex-col gap-1">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Forgot your password?
                    </Link>
                    <Link
                        :href="route('register')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Register
                    </Link>
                </div>
                <Button
                    type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
