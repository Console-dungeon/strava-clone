<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// shadcn imports
import { Avatar } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
} from '@/Components/ui/navigation-menu';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="bg-background text-foreground min-h-screen">
        <!-- NAVBAR -->
        <nav class="border-b bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- LEFT SIDE -->
                    <div class="flex items-center gap-6">
                        <!-- Logo -->
                        <Link
                            :href="route('dashboard')"
                            class="flex items-center"
                        >
                            <ApplicationLogo class="text-primary h-8 w-auto" />
                        </Link>

                        <!-- Desktop Navigation -->
                        <NavigationMenu class="hidden sm:flex">
                            <NavigationMenuList>
                                <NavigationMenuItem>
                                    <NavigationMenuLink as-child>
                                        <Link
                                            :href="route('dashboard')"
                                            :class="
                                                route().current('dashboard')
                                                    ? 'text-primary font-medium'
                                                    : 'text-muted-foreground'
                                            "
                                        >
                                            Dashboard
                                        </Link>
                                    </NavigationMenuLink>
                                </NavigationMenuItem>
                            </NavigationMenuList>
                        </NavigationMenu>
                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="hidden items-center gap-4 sm:flex">
                        <!-- User Dropdown -->
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button
                                    variant="ghost"
                                    class="flex items-center gap-2"
                                >
                                    <Avatar class="h-6 w-6" />
                                    <span>{{
                                        $page.props.auth.user.name
                                    }}</span>
                                </Button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent class="w-48" align="end">
                                <DropdownMenuItem as-child>
                                    <Link :href="route('profile.edit')"
                                        >Profile</Link
                                    >
                                </DropdownMenuItem>

                                <DropdownMenuSeparator />

                                <DropdownMenuItem as-child>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="sm:hidden">
                        <Button
                            variant="ghost"
                            size="icon"
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                        >
                            <svg
                                v-if="!showingNavigationDropdown"
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>

                            <svg
                                v-else
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div
                v-if="showingNavigationDropdown"
                class="border-t bg-white sm:hidden"
            >
                <div class="space-y-2 px-4 py-3">
                    <Link
                        :href="route('dashboard')"
                        class="block py-2 text-sm"
                        :class="
                            route().current('dashboard')
                                ? 'text-primary font-medium'
                                : 'text-muted-foreground'
                        "
                    >
                        Dashboard
                    </Link>

                    <div class="border-t pt-3">
                        <div class="text-base font-medium">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="text-muted-foreground text-sm">
                            {{ $page.props.auth.user.email }}
                        </div>
                    </div>

                    <div class="space-y-1 pt-2">
                        <Link
                            :href="route('profile.edit')"
                            class="block py-2 text-sm"
                        >
                            Profile
                        </Link>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="block py-2 text-sm"
                        >
                            Log Out
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- PAGE HEADER -->
        <header v-if="$slots.header" class="border-b bg-white">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
