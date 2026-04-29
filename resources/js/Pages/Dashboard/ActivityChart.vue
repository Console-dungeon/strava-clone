<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { VisAxis, VisGroupedBar, VisXYContainer } from '@unovis/vue';

interface ChartDay {
  label: string;
  distance: number;
}

const props = defineProps<{ chartData: ChartDay[] }>();
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>{{ $t('dashboard.last7days') }}</CardTitle>
    </CardHeader>
    <CardContent>
      <VisXYContainer :data="props.chartData" :height="220">
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
    </CardContent>
  </Card>
</template>
