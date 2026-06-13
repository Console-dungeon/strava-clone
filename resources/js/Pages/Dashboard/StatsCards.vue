<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';

interface Stats {
  distance: number;
  duration: string;
  avgSpeed: number;
}

defineProps<{ stats: Stats }>();
</script>

<template>
  <div class="grid gap-4 md:grid-cols-3">
    <Card class="py-4 md:py-6">
      <CardHeader class="px-4 md:px-6">
        <CardTitle class="text-muted-foreground text-sm font-medium">
          {{ $t('dashboard.totalDistance') }}
        </CardTitle>
      </CardHeader>
      <CardContent class="px-4 md:px-6">
        <p class="text-lg font-semibold lg:text-2xl">{{ stats.distance }} km</p>
      </CardContent>
    </Card>

    <Card class="py-4 md:py-6">
      <CardHeader class="px-4 md:px-6">
        <CardTitle class="text-muted-foreground text-sm font-medium">
          {{ $t('dashboard.totalTime') }}
        </CardTitle>
      </CardHeader>
      <CardContent class="px-4 md:px-6">
        <p class="text-lg font-semibold lg:text-2xl">
          {{ stats.duration }}
        </p>
      </CardContent>
    </Card>

    <Card class="py-4 md:py-6">
      <CardHeader class="px-4 md:px-6">
        <CardTitle class="text-muted-foreground text-sm font-medium">
          {{ $t('dashboard.avgSpeed') }}
        </CardTitle>
      </CardHeader>
      <CardContent class="px-4 md:px-6">
        <p class="text-lg font-semibold lg:text-2xl">
          {{ stats.avgSpeed }} km/h
        </p>
      </CardContent>
    </Card>
  </div>
</template>
