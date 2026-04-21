<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Menu, Moon, Sun, X } from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';

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

const isDark = ref(false);

onMounted(() => {
  isDark.value =
    localStorage.getItem('theme') === 'dark' ||
    (!localStorage.getItem('theme') &&
      window.matchMedia('(prefers-color-scheme: dark)').matches);
  document.documentElement.classList.toggle('dark', isDark.value);
});

function toggleDark() {
  isDark.value = !isDark.value;
  document.documentElement.classList.toggle('dark', isDark.value);
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
}
</script>

<template>
  <div class="bg-app text-foreground min-h-screen">
    <!-- NAVBAR -->
    <nav
      class="dark:bg-background/40 sticky top-0 z-50 border-b bg-white/60 backdrop-blur-xs backdrop-saturate-50"
    >
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <!-- LEFT SIDE -->
          <div class="flex items-center gap-6">
            <Link :href="route('dashboard')" class="flex items-center">
              <ApplicationLogo class="text-primary h-8 w-auto" />
            </Link>

            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-1 sm:flex">
              <Link
                :href="route('dashboard')"
                class="rounded-md px-3 py-2 text-sm font-medium transition-colors"
                :class="
                  route().current('dashboard')
                    ? 'text-foreground'
                    : 'text-muted-foreground hover:text-foreground'
                "
              >
                Dashboard
              </Link>
            </div>
          </div>

          <!-- RIGHT SIDE (desktop) -->
          <div class="hidden items-center gap-2 sm:flex">
            <Button variant="ghost" size="icon" @click="toggleDark">
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

              <DropdownMenuContent class="w-48 *:cursor-pointer" align="end">
                <DropdownMenuItem as-child>
                  <Link :href="route('profile.edit')">Profile</Link>
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem as-child>
                  <Link :href="route('logout')" method="post" class="w-full"
                    >Log Out</Link
                  >
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>

          <!-- Mobile controls -->
          <div class="flex items-center gap-2 sm:hidden">
            <Button variant="ghost" size="icon" @click="toggleDark">
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

      <!-- Mobile Menu -->
      <div
        v-if="showingNavigationDropdown"
        class="dark:bg-background/80 border-t bg-white/80 backdrop-blur-sm sm:hidden"
      >
        <div class="space-y-1 px-4 py-3">
          <Link
            :href="route('dashboard')"
            class="block rounded-md px-3 py-2 text-sm font-medium"
            :class="
              route().current('dashboard')
                ? 'text-foreground'
                : 'text-muted-foreground'
            "
          >
            Dashboard
          </Link>
        </div>

        <div class="border-t px-4 py-3">
          <div class="text-base font-medium">{{ user?.name || '' }}</div>
          <div class="text-muted-foreground text-sm">
            {{ user?.email || '' }}
          </div>

          <div class="mt-3 space-y-1">
            <Link
              :href="route('profile.edit')"
              class="text-muted-foreground hover:text-foreground block py-2 text-sm"
            >
              Profile
            </Link>
            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="text-muted-foreground hover:text-foreground block py-2 text-sm"
            >
              Log Out
            </Link>
          </div>
        </div>
      </div>
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
  </div>
</template>
