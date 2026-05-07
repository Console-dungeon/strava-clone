<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import ActivityMap from '@/Components/ActivityMap.vue';
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
import {
  ChevronDown,
  Flame,
  Gauge,
  Heart,
  Timer,
  Trash2,
  Zap,
} from 'lucide-vue-next';

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
}>();

const { t } = useI18n();

const types = computed<Record<string, string>>(() => ({
  running: t('activities.types.running'),
  cycling: t('activities.types.cycling'),
  swimming: t('activities.types.swimming'),
}));

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

const d = t('activities.details.noData');

interface StatCard {
  label: string;
  value: string | null;
  unit: string;
  icon: typeof Heart;
}

function buildStatCards(activity: Activity): StatCard[] {
  return [
    {
      label: t('activities.details.avgHr'),
      value: activity.avg_hr != null ? String(activity.avg_hr) : null,
      unit: t('activities.details.bpm'),
      icon: Heart,
    },
    {
      label: t('activities.details.maxHr'),
      value: activity.max_hr != null ? String(activity.max_hr) : null,
      unit: t('activities.details.bpm'),
      icon: Heart,
    },
    {
      label: t('activities.details.avgPace'),
      value: activity.avg_pace ?? null,
      unit: '',
      icon: Timer,
    },
    {
      label: t('activities.details.avgSpeed'),
      value: activity.avg_speed != null ? activity.avg_speed.toFixed(1) : null,
      unit: t('activities.details.kmh'),
      icon: Gauge,
    },
    {
      label: t('activities.details.maxSpeed'),
      value: activity.max_speed != null ? activity.max_speed.toFixed(1) : null,
      unit: t('activities.details.kmh'),
      icon: Zap,
    },
    {
      label: t('activities.details.calories'),
      value: activity.calories != null ? String(activity.calories) : null,
      unit: t('activities.details.kcal'),
      icon: Flame,
    },
  ];
}
</script>

<template>
  <Head title="Aktywności" />

  <AuthenticatedLayout>
    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <Card>
          <CardHeader class="flex flex-row items-center justify-between">
            <CardTitle>{{ t('activities.recentActivities') }}</CardTitle>

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
          </CardHeader>

          <CardContent>
            <div
              v-if="activities.data.length === 0"
              class="text-muted-foreground py-6 text-center"
            >
              {{ t('activities.noActivities') }}
            </div>

            <template v-else>
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
                      :class="{ 'bg-muted/40': mapActivityId === activity.id }"
                      @click="toggleMap(activity)"
                    >
                      <TableCell>{{ activity.date }}</TableCell>
                      <TableCell>{{
                        types[activity.type] ?? activity.type
                      }}</TableCell>
                      <TableCell>{{ activity.distance }} km</TableCell>
                      <TableCell>{{ activity.duration }}</TableCell>
                      <TableCell class="text-right">
                        <button
                          class="text-muted-foreground hover:text-destructive transition-colors"
                          @click.stop="askDelete(activity.id)"
                        >
                          <Trash2 class="h-4 w-4" />
                        </button>
                      </TableCell>
                    </TableRow>

                    <TableRow v-if="mapActivityId === activity.id">
                      <TableCell colspan="5" class="p-0">
                        <div class="flex gap-4 p-4">
                          <!-- Map (left half) -->
                          <div class="min-w-0 flex-1">
                            <div
                              v-if="mapLoading"
                              class="text-muted-foreground flex h-64 items-center justify-center text-sm"
                            >
                              {{ t('activities.mapLoading') }}
                            </div>
                            <ActivityMap
                              v-else-if="routePoints.length > 0"
                              :route-points="routePoints"
                            />
                            <div
                              v-else
                              class="text-muted-foreground flex h-64 items-center justify-center rounded-lg border text-sm"
                            >
                              {{ t('activities.mapNoData') }}
                            </div>
                          </div>

                          <!-- Stats (right half) -->
                          <div class="w-1/2 shrink-0">
                            <div class="grid grid-cols-2 gap-3">
                              <div
                                v-for="card in buildStatCards(activity)"
                                :key="card.label"
                                class="bg-muted/40 flex flex-col gap-1 rounded-lg p-3"
                              >
                                <div
                                  class="text-muted-foreground flex items-center gap-1.5 text-xs"
                                >
                                  <component
                                    :is="card.icon"
                                    class="h-3.5 w-3.5"
                                  />
                                  {{ card.label }}
                                </div>
                                <div class="text-sm font-semibold">
                                  <span v-if="card.value != null">
                                    {{ card.value
                                    }}<span
                                      v-if="card.unit"
                                      class="text-muted-foreground ml-1 text-xs font-normal"
                                      >{{ card.unit }}</span
                                    >
                                  </span>
                                  <span
                                    v-else
                                    class="text-muted-foreground font-normal"
                                    >{{ d }}</span
                                  >
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </TableCell>
                    </TableRow>
                  </template>
                </TableBody>
              </Table>

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
