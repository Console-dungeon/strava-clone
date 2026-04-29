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
    <Card>
      <CardHeader>
        <CardTitle class="text-muted-foreground text-sm font-medium">
          {{ $t('dashboard.totalDistance') }}
        </CardTitle>
      </CardHeader>
      <CardContent>
        <p class="text-2xl font-semibold">{{ stats.distance }} km</p>
      </CardContent>
    </Card>

    <Card>
      <CardHeader>
        <CardTitle class="text-muted-foreground text-sm font-medium">
          {{ $t('dashboard.totalTime') }}
        </CardTitle>
      </CardHeader>
      <CardContent>
        <p class="text-2xl font-semibold">{{ stats.duration }}</p>
      </CardContent>
    </Card>

    <Card>
      <CardHeader>
        <CardTitle class="text-muted-foreground text-sm font-medium">
          {{ $t('dashboard.avgSpeed') }}
        </CardTitle>
      </CardHeader>
      <CardContent>
        <p class="text-2xl font-semibold">{{ stats.avgSpeed }} km/h</p>
      </CardContent>
    </Card>
  </div>
</template>
