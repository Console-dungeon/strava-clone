<script setup lang="ts">
import ActivityMap from '@/Components/ActivityMap.vue';
import { Flame, Gauge, Heart, Timer, Zap } from 'lucide-vue-next';
import type { Component } from 'vue';
import { useI18n } from 'vue-i18n';

interface RoutePoint {
  lat: number;
  lng: number;
  ele?: number;
}

interface ActivityStats {
  avg_hr: number | null;
  max_hr: number | null;
  avg_speed: number | null;
  max_speed: number | null;
  avg_pace: string | null;
  calories: number | null;
}

const props = defineProps<{
  activity: ActivityStats;
  routePoints: RoutePoint[];
  mapLoading: boolean;
}>();

const { t } = useI18n();

const d = t('activities.details.noData');

interface StatCard {
  label: string;
  value: string | null;
  unit: string;
  icon: Component;
}

const statCards = (): StatCard[] => [
  {
    label: t('activities.details.avgHr'),
    value: props.activity.avg_hr != null ? String(props.activity.avg_hr) : null,
    unit: t('activities.details.bpm'),
    icon: Heart,
  },
  {
    label: t('activities.details.maxHr'),
    value: props.activity.max_hr != null ? String(props.activity.max_hr) : null,
    unit: t('activities.details.bpm'),
    icon: Heart,
  },
  {
    label: t('activities.details.avgPace'),
    value: props.activity.avg_pace ?? null,
    unit: '',
    icon: Timer,
  },
  {
    label: t('activities.details.avgSpeed'),
    value:
      props.activity.avg_speed != null
        ? props.activity.avg_speed.toFixed(1)
        : null,
    unit: t('activities.details.kmh'),
    icon: Gauge,
  },
  {
    label: t('activities.details.maxSpeed'),
    value:
      props.activity.max_speed != null
        ? props.activity.max_speed.toFixed(1)
        : null,
    unit: t('activities.details.kmh'),
    icon: Zap,
  },
  {
    label: t('activities.details.calories'),
    value:
      props.activity.calories != null ? String(props.activity.calories) : null,
    unit: t('activities.details.kcal'),
    icon: Flame,
  },
];
</script>

<template>
  <div class="flex flex-col gap-4 lg:flex-row">
    <!-- Map -->
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

    <!-- Stats -->
    <div class="w-full shrink-0 lg:w-1/2">
      <div class="grid grid-cols-2 gap-3">
        <div
          v-for="card in statCards()"
          :key="card.label"
          class="bg-muted/40 flex flex-col gap-1 rounded-lg p-3"
        >
          <div class="text-muted-foreground flex items-center gap-1.5 text-xs">
            <component :is="card.icon" class="h-3.5 w-3.5 shrink-0" />
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
            <span v-else class="text-muted-foreground font-normal">{{
              d
            }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
