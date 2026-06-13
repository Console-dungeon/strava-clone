<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { VisAxis, VisGroupedBar, VisXYContainer } from '@unovis/vue';
import { useMediaQuery } from '@vueuse/core';
import { computed } from 'vue';

interface ChartDay {
  label: string;
  distance: number;
}

const props = defineProps<{ chartData: ChartDay[] }>();

const isDesktop = useMediaQuery('(min-width: 768px)');

const maxDistance = computed(() =>
  Math.max(0, ...props.chartData.map((d) => d.distance)),
);

const barWidth = (distance: number) =>
  maxDistance.value > 0 ? `${(distance / maxDistance.value) * 100}%` : '0%';
</script>

<template>
  <Card class="py-4 md:py-6">
    <CardHeader class="px-4 md:px-6">
      <CardTitle>{{ $t('dashboard.last7days') }}</CardTitle>
    </CardHeader>
    <CardContent class="px-4 md:px-6">
      <!-- Desktop: bar chart -->
      <VisXYContainer v-if="isDesktop" :data="props.chartData" :height="220">
        <VisGroupedBar
          :x="(_: ChartDay, i: number) => i"
          :y="[(d: ChartDay) => d.distance]"
        />
        <VisAxis
          type="x"
          :tick-format="(i: number) => props.chartData[i]?.label ?? ''"
        />
        <VisAxis type="y" :label="'km'" />
      </VisXYContainer>

      <!-- Mobile: horizontal bar list -->
      <div v-else class="space-y-2">
        <div
          v-for="day in props.chartData"
          :key="day.label"
          class="flex items-center gap-3"
        >
          <span class="text-muted-foreground w-12 shrink-0 text-xs">
            {{ day.label }}
          </span>
          <div class="bg-muted h-2 flex-1 overflow-hidden rounded-full">
            <div
              class="bg-primary h-full rounded-full"
              :style="{ width: barWidth(day.distance) }"
            />
          </div>
          <span class="w-16 shrink-0 text-right text-sm font-medium">
            {{ day.distance }} km
          </span>
        </div>
      </div>
    </CardContent>
  </Card>
</template>
