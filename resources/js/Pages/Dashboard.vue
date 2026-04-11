<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// shadcn imports
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

defineProps({
    stats: Object,
});
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
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div
                            class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8"
                        >
                            <!-- Statystyki -->
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="rounded-lg bg-white p-4 shadow">
                                    <h3 class="text-sm text-gray-500">
                                        Łączny dystans
                                    </h3>
                                    <p class="mt-2 text-2xl font-semibold">
                                        {{ stats.distance }} km
                                    </p>
                                </div>

                                <div class="rounded-lg bg-white p-4 shadow">
                                    <h3 class="text-sm text-gray-500">
                                        Łączny czas
                                    </h3>
                                    <p class="mt-2 text-2xl font-semibold">
                                        {{ stats.duration }} min
                                    </p>
                                </div>

                                <div class="rounded-lg bg-white p-4 shadow">
                                    <h3 class="text-sm text-gray-500">
                                        Średnia prędkość
                                    </h3>
                                    <p class="mt-2 text-2xl font-semibold">
                                        {{ stats.avgSpeed }} km/h
                                    </p>
                                </div>
                            </div>

                            <!-- Ostatnie aktywności -->
                            <div class="rounded-lg bg-white p-4 shadow">
                                <h3 class="text-lg font-semibold">
                                    Ostatnie aktywności
                                </h3>
                                <p class="mt-2 text-gray-500">
                                    Brak aktywności – dodamy je później.
                                </p>
                            </div>
                            <!-- Wykres -->
                            <div class="rounded-lg bg-white p-4 shadow">
                                <h3 class="text-lg font-semibold">
                                    Wykres aktywności
                                </h3>
                                <div
                                    class="mt-4 flex h-64 items-center justify-center text-gray-400"
                                >
                                    (wykres pojawi się tutaj)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ostatnie aktywności -->
                <Card>
                    <CardHeader>
                        <CardTitle>Ostatnie aktywności</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <!-- Jeśli nie ma aktywności -->
                        <div
                            v-if="!stats.recent || stats.recent.length === 0"
                            class="py-6 text-center text-muted-foreground"
                        >
                            Brak aktywności – dodamy je później.
                        </div>

                        <!-- Jeśli są aktywności -->
                        <div v-else>
                            <Table>
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
                                        <TableCell>{{
                                            activity.date
                                        }}</TableCell>
                                        <TableCell>{{
                                            activity.type
                                        }}</TableCell>
                                        <TableCell
                                            >{{
                                                activity.distance
                                            }}
                                            km</TableCell
                                        >
                                        <TableCell
                                            >{{
                                                activity.duration
                                            }}
                                            min</TableCell
                                        >
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>

                <!-- Wykres -->
                <Card>
                    <CardHeader>
                        <CardTitle>Wykres aktywności</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="flex h-64 items-center justify-center text-muted-foreground"
                        >
                            (wykres pojawi się tutaj)
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
