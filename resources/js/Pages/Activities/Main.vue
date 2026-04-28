<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
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
import { ChevronDown, Trash2 } from 'lucide-vue-next';

interface Activity {
  id: number;
  date: string;
  type: string;
  distance: number;
  duration: number;
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
                  <DropdownMenuRadioItem value=""
                    >Wszystkie</DropdownMenuRadioItem
                  >
                  <DropdownMenuSeparator />
                  <DropdownMenuRadioItem value="running"
                    >Bieg</DropdownMenuRadioItem
                  >
                  <DropdownMenuRadioItem value="cycling"
                    >Rower</DropdownMenuRadioItem
                  >
                  <DropdownMenuRadioItem value="swimming"
                    >Pływanie</DropdownMenuRadioItem
                  >
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
                  <TableRow
                    v-for="activity in activities.data"
                    :key="activity.id"
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
                        @click="askDelete(activity.id)"
                      >
                        <Trash2 class="h-4 w-4" />
                      </button>
                    </TableCell>
                  </TableRow>
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
