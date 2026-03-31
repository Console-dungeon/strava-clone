<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

// shadcn imports
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card'
import { 
  Table, 
  TableHeader, 
  TableBody, 
  TableRow, 
  TableHead, 
  TableCell 
} from '@/components/ui/table'

defineProps({
    stats: Object
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col">
                <h2 class="text-2xl font-bold tracking-tight">Straba – Dashboard</h2>
                <p class="text-sm text-muted-foreground">student‑developed fitness app</p>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl px-4 space-y-8">

                <!-- Statystyki -->
                <div class="grid gap-6 md:grid-cols-3">
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-sm text-muted-foreground">Łączny dystans</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-3xl font-semibold">{{ stats.distance }} km</p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="text-sm text-muted-foreground">Łączny czas</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-3xl font-semibold">{{ stats.duration }} min</p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="text-sm text-muted-foreground">Średnia prędkość</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-3xl font-semibold">{{ stats.avgSpeed }} km/h</p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Ostatnie aktywności -->
                <Card>
                    <CardHeader>
                        <CardTitle>Ostatnie aktywności</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <!-- Jeśli nie ma aktywności -->
                        <div v-if="!stats.recent || stats.recent.length === 0" 
                            class="text-muted-foreground py-6 text-center">
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
                            <TableRow v-for="activity in stats.recent" :key="activity.id">
                                <TableCell>{{ activity.date }}</TableCell>
                                <TableCell>{{ activity.type }}</TableCell>
                                <TableCell>{{ activity.distance }} km</TableCell>
                                <TableCell>{{ activity.duration }} min</TableCell>
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
                        <div class="h-64 flex items-center justify-center text-muted-foreground">
                            (wykres pojawi się tutaj)
                        </div>
                    </CardContent>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>