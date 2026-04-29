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

interface RecentActivity {
  id: number;
  date: string;
  type: string;
  distance: number;
  duration: string;
}

defineProps<{ recent: RecentActivity[] }>();
</script>

<template>
  <Card>
    <CardHeader class="flex flex-row items-center justify-between">
      <CardTitle>{{ $t('dashboard.recentActivities') }}</CardTitle>
      <Link
        :href="route('activities.main')"
        class="text-muted-foreground hover:text-foreground text-sm underline transition-colors"
      >
        {{ $t('dashboard.seeAll') }}
      </Link>
    </CardHeader>
    <CardContent>
      <div
        v-if="recent.length === 0"
        class="text-muted-foreground py-6 text-center"
      >
        {{ $t('dashboard.noActivities') }}
      </div>

      <div v-else class="max-h-125 overflow-y-auto">
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
              <TableCell>{{
                $t(`activities.types.${activity.type}`)
              }}</TableCell>
              <TableCell>{{ activity.distance }} km</TableCell>
              <TableCell>{{ activity.duration }}</TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </CardContent>
  </Card>
</template>
