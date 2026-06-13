<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { computed, ref, watch } from 'vue';

import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import {
  Activity,
  LayoutDashboard,
  Menu,
  Moon,
  Plus,
  Sun,
  X,
} from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { useLocale } from '@/composables/useLocale';
import { useI18n } from 'vue-i18n';

import { Toaster } from '@/Components/ui/sonner';
import { toast } from 'vue-sonner';
import 'vue-sonner/style.css';

const { t } = useI18n();
const { currentLocale, toggleLocale } = useLocale();

const page = usePage();

const user = computed(() => page.props.auth.user);

const userInitials = computed(() => {
  if (!user.value) return '';
  const names = user.value.name.split(' ');
  return (
    names[0]?.charAt(0).toUpperCase() +
    (names[1]?.charAt(0).toUpperCase() || '')
  );
});

const showingNavigationDropdown = ref(false);

const closeMenu = () => (showingNavigationDropdown.value = false);

const mobileNavItems = computed(() => [
  { label: t('nav.dashboard'), route: 'dashboard', icon: LayoutDashboard },
  { label: t('nav.activities'), route: 'activities.main', icon: Activity },
  { label: t('nav.addActivity'), route: 'activities.create', icon: Plus },
]);

const isDark = useDark();
const toggleDark = useToggle(isDark);

watch(
  () => page.flash.toast,
  (value) => {
    if (!value) return;
    if (value.type === 'success') toast.success(value.message);
    else if (value.type === 'error') toast.error(value.message);
    else toast(value.message);
  },
  { immediate: true },
);
</script>

<template>
  <div class="bg-app text-foreground min-h-screen">
    <!-- NAVBAR -->
    <nav
      class="dark:bg-background/40 sticky top-0 z-999 border-b bg-white/60 backdrop-blur-xs backdrop-saturate-50"
    >
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <!-- LEFT SIDE -->
          <div class="flex items-center gap-6">
            <Link :href="route('dashboard')" class="flex items-center">
              <ApplicationLogo class="text-primary h-8 w-auto" />
            </Link>
            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-1 lg:flex">
              <Link
                :href="route('dashboard')"
                class="rounded-md px-3 py-2 text-sm font-medium transition-colors"
                :class="
                  route().current('dashboard')
                    ? 'text-foreground'
                    : 'text-muted-foreground hover:text-foreground'
                "
              >
                {{ t('nav.dashboard') }}
              </Link>
              <Link
                :href="route('activities.main')"
                class="rounded-md px-3 py-2 text-sm font-medium transition-colors"
                :class="
                  route().current('activities.main')
                    ? 'text-foreground'
                    : 'text-muted-foreground hover:text-foreground'
                "
              >
                {{ t('nav.activities') }}
              </Link>
              <Link
                :href="route('activities.create')"
                class="rounded-md px-3 py-2 text-sm font-medium transition-colors"
                :class="
                  route().current('activities.create')
                    ? 'text-foreground'
                    : 'text-muted-foreground hover:text-foreground'
                "
              >
                {{ t('nav.addActivity') }}
              </Link>
            </div>
          </div>

          <!-- RIGHT SIDE (desktop) -->
          <div class="hidden items-center gap-2 lg:flex">
            <Button
              variant="ghost"
              size="sm"
              class="text-muted-foreground font-medium"
              @click="toggleLocale"
            >
              {{ currentLocale.toUpperCase() === 'PL' ? 'PL' : 'EN' }}
            </Button>

            <Button variant="ghost" size="icon" @click="() => toggleDark()">
              <Sun v-if="isDark" class="h-5 w-5" />
              <Moon v-else class="h-5 w-5" />
            </Button>

            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button
                  variant="ghost"
                  class="flex cursor-pointer items-center gap-2"
                >
                  <Avatar>
                    <!-- INFO: Temporary static avatar. -->
                    <AvatarImage
                      src="https://github.com/shadcn.png"
                      alt="@shadcn"
                    />
                    <AvatarFallback>{{ userInitials }}</AvatarFallback>
                  </Avatar>
                  <span>{{ user?.name || '' }}</span>
                </Button>
              </DropdownMenuTrigger>

              <DropdownMenuContent
                class="z-1000 w-48 *:cursor-pointer"
                align="end"
              >
                <DropdownMenuItem as-child>
                  <Link :href="route('profile.edit')">{{
                    t('nav.profile')
                  }}</Link>
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem as-child>
                  <Link :href="route('logout')" method="post" class="w-full">{{
                    t('nav.logout')
                  }}</Link>
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>

          <!-- Mobile controls -->
          <div class="flex items-center gap-2 lg:hidden">
            <Button
              variant="ghost"
              size="sm"
              class="text-muted-foreground font-medium"
              @click="toggleLocale"
            >
              {{ currentLocale.toUpperCase() === 'PL' ? 'PL' : 'EN' }}
            </Button>
            <Button variant="ghost" size="icon" @click="() => toggleDark()">
              <Sun v-if="isDark" class="h-5 w-5" />
              <Moon v-else class="h-5 w-5" />
            </Button>
            <Button
              variant="ghost"
              size="icon"
              @click="showingNavigationDropdown = !showingNavigationDropdown"
            >
              <X v-if="showingNavigationDropdown" class="h-5 w-5" />
              <Menu v-else class="h-5 w-5" />
            </Button>
          </div>
        </div>
      </div>

      <!-- Mobile drawer -->
      <Teleport to="body">
        <!-- Backdrop -->
        <Transition
          enter-active-class="transition-opacity duration-300 ease-out"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="transition-opacity duration-200 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-if="showingNavigationDropdown"
            class="fixed inset-0 z-1000 bg-black/40 backdrop-blur-sm lg:hidden"
            @click="closeMenu"
          />
        </Transition>

        <!-- Panel -->
        <Transition
          enter-active-class="transition-transform duration-300 ease-out"
          enter-from-class="translate-x-full"
          enter-to-class="translate-x-0"
          leave-active-class="transition-transform duration-200 ease-in"
          leave-from-class="translate-x-0"
          leave-to-class="translate-x-full"
        >
          <div
            v-if="showingNavigationDropdown"
            class="dark:bg-background fixed inset-y-0 right-0 z-1001 flex w-3/4 max-w-sm flex-col overflow-y-auto border-l bg-white shadow-2xl lg:hidden"
          >
            <!-- Close -->
            <div class="flex items-center justify-end px-2 py-2">
              <Button variant="ghost" size="icon" @click="closeMenu">
                <X class="h-5 w-5" />
              </Button>
            </div>

            <!-- Profile first -->
            <div class="border-b px-4 pb-4">
              <div class="flex items-center gap-3">
                <Avatar>
                  <!-- INFO: Temporary static avatar. -->
                  <AvatarImage
                    src="https://github.com/shadcn.png"
                    alt="@shadcn"
                  />
                  <AvatarFallback>{{ userInitials }}</AvatarFallback>
                </Avatar>
                <div class="min-w-0">
                  <div class="truncate text-base font-medium">
                    {{ user?.name || '' }}
                  </div>
                  <div class="text-muted-foreground truncate text-sm">
                    {{ user?.email || '' }}
                  </div>
                </div>
              </div>

              <div class="mt-3 space-y-1">
                <Link
                  :href="route('profile.edit')"
                  class="text-muted-foreground hover:text-foreground block py-2 text-sm"
                  @click="closeMenu"
                >
                  {{ t('nav.profile') }}
                </Link>
                <Link
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="text-muted-foreground hover:text-foreground block py-2 text-sm"
                  @click="closeMenu"
                >
                  {{ t('nav.logout') }}
                </Link>
              </div>
            </div>

            <!-- Routing -->
            <div class="space-y-1 px-4 py-3">
              <Link
                v-for="item in mobileNavItems"
                :key="item.route"
                :href="route(item.route)"
                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors"
                :class="
                  route().current(item.route)
                    ? 'bg-muted text-foreground'
                    : 'text-muted-foreground hover:bg-muted/50 hover:text-foreground'
                "
                @click="closeMenu"
              >
                <component :is="item.icon" class="h-4 w-4 shrink-0" />
                {{ item.label }}
              </Link>
            </div>
          </div>
        </Transition>
      </Teleport>
    </nav>

    <!-- PAGE HEADER -->
    <header
      v-if="$slots.header"
      class="dark:bg-background/70 border-b bg-white/70 backdrop-blur-sm"
    >
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <slot />
    </main>

    <Toaster :theme="isDark ? 'dark' : 'light'" position="top-right" />
  </div>
</template>
