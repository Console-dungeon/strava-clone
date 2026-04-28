<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';

import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import {
  Table,
  TableBody,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = usePage<{
  id: number;
  name: string;
  startDate: string;
  endDate: string;
  activityType: object;
  startTime: string;
  duration: number;
  distance: number;
  avarageSpeed: number;
}>().props;

console.log(props);
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- <template #header>
      <h2 class="text-foreground text-xl leading-tight font-semibold">
        Straba - Dashboard
      </h2>
      <p class="text-muted-foreground mt-1 text-sm">student‑developed fitness app</p>
    </template> -->

    <div class="space-y-6">
      <!-- Statystyki -->
      <div class="grid gap-4 md:grid-cols-3">
        <Card>
          <CardHeader>
            <CardTitle class="text-muted-foreground text-sm font-medium">
              Łączny dystans
            </CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-2xl font-semibold">{{ props.distance }} km</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle class="text-muted-foreground text-sm font-medium">
              Łączny czas
            </CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-2xl font-semibold">
              {{ props.duration }}
            </p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle class="text-muted-foreground text-sm font-medium">
              Średnia prędkość
            </CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-2xl font-semibold">{{ props.avarageSpeed }} km/h</p>
          </CardContent>
        </Card>
      </div>

      <!-- Ostatnie aktywności -->
      <Card>
        <CardHeader>
          <CardTitle>Ostatnie aktywności</CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="true" class="text-muted-foreground py-6 text-center">
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
              <!-- <TableRow v-for="activity in stats.recent" :key="activity.id">
                  <TableCell>{{ activity.date }}</TableCell>
                  <TableCell>{{
                    types[activity.type] ?? activity.type
                  }}</TableCell>
                  <TableCell>{{ activity.distance }} km</TableCell>
                  <TableCell>{{ activity.duration }}</TableCell>
                </TableRow> -->
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      <!-- Wykres -->
      <!-- <Card>
          <CardHeader>
            <CardTitle>Dystans — ostatnie 7 dni</CardTitle>
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
        </Card> -->

      <!-- <div v-else class="text-muted-foreground py-6 text-center">
        Brak danych do wyświetlenia.
      </div> -->
    </div>
  </AuthenticatedLayout>
</template>
