<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table';
import { Link } from '@inertiajs/vue3';
import { Activity, Bike, Clock, Footprints, Waves } from 'lucide-vue-next';
import type { Component } from 'vue';

interface RecentActivity {
  id: number;
  date: string;
  type: string;
  distance: number;
  duration: string;
}

defineProps<{ recent: RecentActivity[] }>();

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
    icon: Activity,
    color: 'text-primary',
    bg: 'bg-primary/10',
  };
</script>

<template>
  <Card class="py-4 md:py-6">
    <CardHeader class="flex flex-row items-center justify-between px-4 md:px-6">
      <CardTitle>{{ $t('dashboard.recentActivities') }}</CardTitle>
      <Link
        :href="route('activities.main')"
        class="text-muted-foreground hover:text-foreground text-sm underline transition-colors"
      >
        {{ $t('dashboard.seeAll') }}
      </Link>
    </CardHeader>
    <CardContent class="px-4 md:px-6">
      <div
        v-if="recent.length === 0"
        class="text-muted-foreground py-6 text-center"
      >
        {{ $t('dashboard.noActivities') }}
      </div>

      <template v-else>
        <!-- Desktop: table -->
        <div class="hidden max-h-125 overflow-y-auto md:block">
          <Table>
            <TableHeader class="top-0">
              <TableRow class="sticky">
                <TableHead>{{ $t('activities.date') }}</TableHead>
                <TableHead>{{ $t('activities.type') }}</TableHead>
                <TableHead>{{ $t('activities.distance') }}</TableHead>
                <TableHead>{{ $t('activities.time') }}</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="activity in recent" :key="activity.id">
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
                        :class="['h-3.5 w-3.5', styleFor(activity.type).color]"
                      />
                    </div>
                    <span>{{ $t(`activities.types.${activity.type}`) }}</span>
                  </div>
                </TableCell>
                <TableCell>{{ activity.distance }} km</TableCell>
                <TableCell>{{ activity.duration }}</TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Mobile: tiles -->
        <div class="max-h-125 space-y-2 overflow-y-auto md:hidden">
          <div
            v-for="activity in recent"
            :key="activity.id"
            class="hover:bg-muted/50 flex items-center gap-3 rounded-lg border p-3 transition-colors"
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
                {{ $t(`activities.types.${activity.type}`) }}
              </div>
              <div class="text-muted-foreground text-xs">
                {{ activity.date }}
              </div>
            </div>
            <div class="text-right">
              <div class="font-semibold">{{ activity.distance }} km</div>
              <div
                class="text-muted-foreground flex items-center justify-end gap-1 text-xs"
              >
                <Clock class="h-3 w-3" />
                {{ activity.duration }}
              </div>
            </div>
          </div>
        </div>
      </template>
    </CardContent>
  </Card>
</template>
