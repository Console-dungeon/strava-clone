<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { VisAxis, VisGroupedBar, VisXYContainer } from '@unovis/vue';

const { t } = useI18n();

interface Activity {
  id: number;
  date: string;
  type: string;
  distance: number;
  duration: string;
}

interface ChartDay {
  label: string;
  distance: number;
}

interface Stats {
  distance: number;
  duration: string;
  avgSpeed: number;
  chartData: ChartDay[];
  recent: Activity[];
}

defineProps<{
  stats: Stats | null;
}>();
</script>

<template>
  <Head :title="t('dashboard.title')" />

  <AuthenticatedLayout>
    <div class="space-y-6">
      <template v-if="stats">
        <div class="grid gap-4 md:grid-cols-3">
          <Card>
            <CardHeader>
              <CardTitle class="text-muted-foreground text-sm font-medium">
                {{ t('dashboard.totalDistance') }}
              </CardTitle>
            </CardHeader>
            <CardContent>
              <p class="text-2xl font-semibold">{{ stats.distance }} km</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle class="text-muted-foreground text-sm font-medium">
                {{ t('dashboard.totalTime') }}
              </CardTitle>
            </CardHeader>
            <CardContent>
              <p class="text-2xl font-semibold">{{ stats.duration }}</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle class="text-muted-foreground text-sm font-medium">
                {{ t('dashboard.avgSpeed') }}
              </CardTitle>
            </CardHeader>
            <CardContent>
              <p class="text-2xl font-semibold">{{ stats.avgSpeed }} km/h</p>
            </CardContent>
          </Card>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>{{ t('dashboard.recentActivities') }}</CardTitle>
          </CardHeader>
          <CardContent>
            <div
              v-if="!stats.recent || stats.recent.length === 0"
              class="text-muted-foreground py-6 text-center"
            >
              {{ t('dashboard.noActivities') }}
            </div>

            <Table v-else>
              <TableHeader>
                <TableRow>
                  <TableHead>{{ t('activities.date') }}</TableHead>
                  <TableHead>{{ t('activities.type') }}</TableHead>
                  <TableHead>{{ t('activities.distance') }}</TableHead>
                  <TableHead>{{ t('activities.time') }}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="activity in stats.recent" :key="activity.id">
                  <TableCell>{{ activity.date }}</TableCell>
                  <TableCell>{{
                    t(`activities.types.${activity.type}`, activity.type)
                  }}</TableCell>
                  <TableCell>{{ activity.distance }} km</TableCell>
                  <TableCell>{{ activity.duration }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>{{ t('dashboard.last7days') }}</CardTitle>
          </CardHeader>
          <CardContent>
            <VisXYContainer :data="stats.chartData" :height="220">
              <VisGroupedBar
                :x="(_: ChartDay, i: number) => i"
                :y="[(d: ChartDay) => d.distance]"
              />
              <VisAxis
                type="x"
                :tick-format="(i: number) => stats!.chartData[i]?.label ?? ''"
              />
              <VisAxis type="y" :label="'km'" />
            </VisXYContainer>
          </CardContent>
        </Card>
      </template>

      <div v-else class="text-muted-foreground py-6 text-center">
        {{ t('dashboard.noData') }}
      </div>
    </div>
  </AuthenticatedLayout>
</template>
