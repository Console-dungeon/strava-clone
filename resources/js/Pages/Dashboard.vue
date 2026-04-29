<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import ActivityChart from './Dashboard/ActivityChart.vue';
import GarminConnectModal from './Dashboard/GarminConnectModal.vue';
import RecentTable from './Dashboard/RecentTable.vue';
import StatsCards from './Dashboard/StatsCards.vue';

interface ChartDay {
  label: string;
  distance: number;
}

interface RecentActivity {
  id: number;
  date: string;
  type: string;
  distance: number;
  duration: string;
}

interface Stats {
  distance: number;
  duration: string;
  avgSpeed: number;
}

const props = defineProps<{
  hasActivities: boolean;
  stats?: Stats;
  recent?: RecentActivity[];
  chartData?: ChartDay[];
}>();
</script>

<template>
  <Head :title="$t('dashboard.title')" />

  <AuthenticatedLayout>
    <div class="space-y-6">
      <template v-if="!props.hasActivities">
        <GarminConnectModal />
      </template>

      <template v-else>
        <StatsCards :stats="props.stats!" />
        <RecentTable :recent="props.recent!" />
        <ActivityChart :chart-data="props.chartData!" />
      </template>
    </div>
  </AuthenticatedLayout>
</template>
