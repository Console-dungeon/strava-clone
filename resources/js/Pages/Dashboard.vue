<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { VisAxis, VisGroupedBar, VisXYContainer } from '@unovis/vue';

interface Activity {
    id: number;
    date: string;
    type: string;
    distance: number;
    duration: number;
}

interface ChartDay {
    label: string;
    distance: number;
}

interface Stats {
    distance: number;
    duration: number;
    avgSpeed: number;
    chartData: ChartDay[];
    recent: Activity[];
}

defineProps<{
    stats: Stats | null;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Strava - Dashboard
            </h2>
            student‑developed fitness app
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

                <template v-if="stats">
                    <!-- Statystyki -->
                    <div class="grid gap-4 md:grid-cols-3">
                        <Card>
                            <CardHeader>
                                <CardTitle class="text-sm font-medium text-muted-foreground">
                                    Łączny dystans
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-2xl font-semibold">{{ stats.distance }} km</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle class="text-sm font-medium text-muted-foreground">
                                    Łączny czas
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-2xl font-semibold">{{ stats.duration }}</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle class="text-sm font-medium text-muted-foreground">
                                    Średnia prędkość
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-2xl font-semibold">{{ stats.avgSpeed }} km/h</p>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Ostatnie aktywności -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Ostatnie aktywności</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div
                                v-if="!stats.recent || stats.recent.length === 0"
                                class="py-6 text-center text-muted-foreground"
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
                                    <TableRow
                                        v-for="activity in stats.recent"
                                        :key="activity.id"
                                    >
                                        <TableCell>{{ activity.date }}</TableCell>
                                        <TableCell>{{ activity.type }}</TableCell>
                                        <TableCell>{{ activity.distance }} km</TableCell>
                                        <TableCell>{{ activity.duration }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>

                    <!-- Wykres -->
                    <Card>
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
                    </Card>
                </template>

                <div v-else class="py-6 text-center text-muted-foreground">
                    Brak danych do wyświetlenia.
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
