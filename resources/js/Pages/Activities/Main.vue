<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

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

interface Activity {
  id: number;
  date: [string, string];
  type: string;
  distance: number;
  duration: number;
}

interface Stats {
  distance: number;
  duration: number;
  avgSpeed: number;
  recent: Activity[];
}

defineProps<{
  stats: Stats | null;
}>();
</script>

<template>
  <Head title="Aktywności" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl leading-tight font-semibold text-gray-800">
        Straba - Aktywności
      </h2>
      student‑developed fitness app
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <template v-if="stats">
          <!-- Ostatnie aktywności -->
          <Card>
            <CardHeader>
              <CardTitle>Ostatnie aktywności</CardTitle>
            </CardHeader>
            <CardContent>
              <div
                v-if="!stats.recent || stats.recent.length === 0"
                class="text-muted-foreground py-6 text-center"
              >
                Brak aktywności.
              </div>

              <Table v-else>
                <TableHeader>
                  <TableRow>
                    <TableHead>Data</TableHead>
                    <TableHead>Typ</TableHead>
                    <TableHead>Dystans</TableHead>
                    <TableHead>Czas</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow v-for="activity in stats.recent" :key="activity.id">
                    <TableCell>{{ activity.date }}</TableCell>
                    <TableCell>{{ activity.type }}</TableCell>
                    <TableCell>{{ activity.distance }} km</TableCell>
                    <TableCell>{{ activity.duration }}</TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </CardContent>
          </Card>
        </template>

        <div v-else class="text-muted-foreground py-6 text-center">
          Brak danych do wyświetlenia.
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
