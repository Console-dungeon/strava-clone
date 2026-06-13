<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useMediaQuery } from '@vueuse/core';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import Modal from '@/Components/Modal.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ActivityDetailPanel from '@/Pages/Activities/ActivityDetailPanel.vue';
import {
  Activity as ActivityIcon,
  Bike,
  ChevronDown,
  Clock,
  Footprints,
  RefreshCw,
  Trash2,
  Waves,
} from 'lucide-vue-next';
import type { Component } from 'vue';

interface RoutePoint {
  lat: number;
  lng: number;
  ele?: number;
}

interface Activity {
  id: number;
  date: string;
  type: string;
  distance: number;
  duration: string;
  has_map: boolean;
  avg_hr: number | null;
  max_hr: number | null;
  avg_speed: number | null;
  max_speed: number | null;
  avg_pace: string | null;
  calories: number | null;
}

interface Paginator {
  data: Activity[];
  current_page: number;
  last_page: number;
  next_page_url: string | null;
  prev_page_url: string | null;
}

interface Stats {
  distance: number;
  duration: number;
  avgSpeed: number;
}

const props = defineProps<{
  stats: Stats;
  activities: Paginator;
  filters: { type: string | null };
  garmin_connected: boolean;
}>();

const { t } = useI18n();

const isDesktop = useMediaQuery('(min-width: 768px)');

const types = computed<Record<string, string>>(() => ({
  running: t('activities.types.running'),
  cycling: t('activities.types.cycling'),
  swimming: t('activities.types.swimming'),
}));

const typeStyles: Record<
  string,
  { icon: Component; color: string; bg: string }
> = {
  running: {
    icon: Footprints,
    color: 'text-orange-500',
    bg: 'bg-orange-500/10',
  },
  cycling: { icon: Bike, color: 'text-blue-500', bg: 'bg-blue-500/10' },
  swimming: { icon: Waves, color: 'text-cyan-500', bg: 'bg-cyan-500/10' },
};

const styleFor = (type: string) =>
  typeStyles[type] ?? {
    icon: ActivityIcon,
    color: 'text-primary',
    bg: 'bg-primary/10',
  };

function filterLabel() {
  return props.filters.type
    ? types.value[props.filters.type]
    : t('activities.all');
}

function setType(value: string | null) {
  router.get(route('activities.main'), value ? { type: value } : {}, {
    preserveState: false,
  });
}

function goTo(url: string | null) {
  if (url) router.get(url, {}, { preserveState: true, preserveScroll: true });
}

const confirmingId = ref<number | null>(null);

function askDelete(id: number) {
  confirmingId.value = id;
}

function cancelDelete() {
  confirmingId.value = null;
}

function confirmDelete() {
  if (!confirmingId.value) return;
  router.delete(route('activities.destroy', confirmingId.value), {
    preserveScroll: true,
    onFinish: () => (confirmingId.value = null),
  });
}

const syncing = ref(false);

function syncGarmin() {
  syncing.value = true;
  router.post(
    route('garmin.sync'),
    {},
    { onFinish: () => (syncing.value = false) },
  );
}

// GPX map panel
const mapActivityId = ref<number | null>(null);
const routePoints = ref<RoutePoint[]>([]);
const mapLoading = ref(false);

async function toggleMap(activity: Activity) {
  if (mapActivityId.value === activity.id) {
    mapActivityId.value = null;
    routePoints.value = [];
    return;
  }

  mapActivityId.value = activity.id;
  routePoints.value = [];
  mapLoading.value = true;

  try {
    const res = await fetch(route('activities.gpx-data', activity.id), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    });
    const data = (await res.json()) as { route_points: RoutePoint[] };
    routePoints.value = data.route_points;
  } finally {
    mapLoading.value = false;
  }
}
</script>

<template>
  <Head title="Aktywności" />

  <AuthenticatedLayout>
    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <Card class="py-4 md:py-6">
          <CardHeader
            class="flex flex-col gap-3 px-4 sm:flex-row sm:items-center sm:justify-between md:px-6"
          >
            <CardTitle>{{ t('activities.recentActivities') }}</CardTitle>

            <div class="flex items-center gap-2">
              <Button
                v-if="garmin_connected"
                variant="outline"
                size="sm"
                :disabled="syncing"
                @click="syncGarmin"
              >
                <RefreshCw
                  class="mr-1.5 h-4 w-4"
                  :class="{ 'animate-spin': syncing }"
                />
                {{
                  syncing
                    ? t('activities.garminSyncing')
                    : t('activities.garminSync')
                }}
              </Button>

              <DropdownMenu>
                <DropdownMenuTrigger as-child>
                  <Button variant="outline" size="sm">
                    {{ filterLabel() }}
                    <ChevronDown class="ml-1 h-4 w-4" />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuRadioGroup
                    :model-value="filters.type ?? ''"
                    @update:model-value="(v) => setType(v as string | null)"
                  >
                    <DropdownMenuRadioItem value="">{{
                      t('activities.all')
                    }}</DropdownMenuRadioItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuRadioItem value="running">{{
                      t('activities.types.running')
                    }}</DropdownMenuRadioItem>
                    <DropdownMenuRadioItem value="cycling">{{
                      t('activities.types.cycling')
                    }}</DropdownMenuRadioItem>
                    <DropdownMenuRadioItem value="swimming">{{
                      t('activities.types.swimming')
                    }}</DropdownMenuRadioItem>
                  </DropdownMenuRadioGroup>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
          </CardHeader>

          <CardContent class="px-4 md:px-6">
            <div
              v-if="activities.data.length === 0"
              class="text-muted-foreground py-6 text-center"
            >
              {{ t('activities.noActivities') }}
            </div>

            <template v-else>
              <!-- Desktop: table -->
              <div v-if="isDesktop">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>{{ t('activities.date') }}</TableHead>
                      <TableHead>{{ t('activities.type') }}</TableHead>
                      <TableHead>{{ t('activities.distance') }}</TableHead>
                      <TableHead>{{ t('activities.time') }}</TableHead>
                      <TableHead />
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    <template
                      v-for="activity in activities.data"
                      :key="activity.id"
                    >
                      <TableRow
                        class="cursor-pointer select-none"
                        :class="{
                          'bg-muted/40': mapActivityId === activity.id,
                        }"
                        @click="toggleMap(activity)"
                      >
                        <TableCell>{{ activity.date }}</TableCell>
                        <TableCell>
                          <div class="flex items-center gap-2">
                            <div
                              :class="[
                                'flex h-7 w-7 shrink-0 items-center justify-center rounded-full',
                                styleFor(activity.type).bg,
                              ]"
                            >
                              <component
                                :is="styleFor(activity.type).icon"
                                :class="[
                                  'h-3.5 w-3.5',
                                  styleFor(activity.type).color,
                                ]"
                              />
                            </div>
                            <span>{{
                              types[activity.type] ?? activity.type
                            }}</span>
                          </div>
                        </TableCell>
                        <TableCell>{{ activity.distance }} km</TableCell>
                        <TableCell>{{ activity.duration }}</TableCell>
                        <TableCell class="text-right">
                          <button
                            class="text-muted-foreground hover:text-destructive cursor-pointer p-2 transition-colors"
                            aria-label="Delete"
                            @click.stop="askDelete(activity.id)"
                          >
                            <Trash2 class="h-4 w-4" />
                          </button>
                        </TableCell>
                      </TableRow>

                      <TableRow v-if="mapActivityId === activity.id">
                        <TableCell colspan="5" class="p-0">
                          <ActivityDetailPanel
                            class="p-4"
                            :activity="activity"
                            :route-points="routePoints"
                            :map-loading="mapLoading"
                          />
                        </TableCell>
                      </TableRow>
                    </template>
                  </TableBody>
                </Table>
              </div>

              <!-- Mobile: tiles -->
              <div v-else class="space-y-2">
                <div
                  v-for="activity in activities.data"
                  :key="activity.id"
                  class="overflow-hidden rounded-lg border"
                  :class="{ 'bg-muted/40': mapActivityId === activity.id }"
                >
                  <div
                    class="flex cursor-pointer items-center gap-3 p-3 select-none"
                    @click="toggleMap(activity)"
                  >
                    <div
                      :class="[
                        'flex h-9 w-9 shrink-0 items-center justify-center rounded-full',
                        styleFor(activity.type).bg,
                      ]"
                    >
                      <component
                        :is="styleFor(activity.type).icon"
                        :class="['h-4 w-4', styleFor(activity.type).color]"
                      />
                    </div>
                    <div class="min-w-0 flex-1">
                      <div class="font-medium">
                        {{ types[activity.type] ?? activity.type }}
                      </div>
                      <div class="text-muted-foreground text-xs">
                        {{ activity.date }}
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="font-semibold">
                        {{ activity.distance }} km
                      </div>
                      <div
                        class="text-muted-foreground flex items-center justify-end gap-1 text-xs"
                      >
                        <Clock class="h-3 w-3" />
                        {{ activity.duration }}
                      </div>
                    </div>
                    <button
                      class="text-muted-foreground hover:text-destructive shrink-0 transition-colors"
                      @click.stop="askDelete(activity.id)"
                    >
                      <Trash2 class="h-4 w-4" />
                    </button>
                  </div>

                  <ActivityDetailPanel
                    v-if="mapActivityId === activity.id"
                    class="border-t p-3"
                    :activity="activity"
                    :route-points="routePoints"
                    :map-loading="mapLoading"
                  />
                </div>
              </div>

              <div class="mt-4 flex items-center justify-between">
                <Button
                  variant="outline"
                  :disabled="!activities.prev_page_url"
                  @click="goTo(activities.prev_page_url)"
                >
                  {{ t('activities.previous') }}
                </Button>
                <span class="text-muted-foreground text-sm">
                  {{ t('activities.page') }} {{ activities.current_page }}
                  {{ t('activities.of') }}
                  {{ activities.last_page }}
                </span>
                <Button
                  variant="outline"
                  :disabled="!activities.next_page_url"
                  @click="goTo(activities.next_page_url)"
                >
                  {{ t('activities.next') }}
                </Button>
              </div>
            </template>
          </CardContent>
        </Card>
      </div>
    </div>

    <Modal :show="confirmingId !== null" max-width="sm" @close="cancelDelete">
      <div class="p-6">
        <h2 class="text-lg font-semibold">
          {{ t('activities.delete.title') }}
        </h2>
        <p class="text-muted-foreground mt-2 text-sm">
          {{ t('activities.delete.message') }}
        </p>
        <div class="mt-6 flex justify-end gap-3">
          <Button variant="outline" @click="cancelDelete">{{
            t('activities.delete.cancel')
          }}</Button>
          <Button variant="destructive" @click="confirmDelete">{{
            t('activities.delete.confirm')
          }}</Button>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>
