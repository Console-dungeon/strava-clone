<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Straba - Dashboard
            </h2>
            student‑developed fitness app
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                         <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                            <!-- Statystyki -->
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="p-4 bg-white shadow rounded-lg">
                                    <h3 class="text-sm text-gray-500">Łączny dystans</h3>
                                    <p class="mt-2 text-2xl font-semibold">{{ stats.distance }} km</p>
                                </div>

                                <div class="p-4 bg-white shadow rounded-lg">
                                    <h3 class="text-sm text-gray-500">Łączny czas</h3>
                                    <p class="mt-2 text-2xl font-semibold">{{ stats.duration }} min</p>
                                </div>

                                <div class="p-4 bg-white shadow rounded-lg">
                                    <h3 class="text-sm text-gray-500">Średnia prędkość</h3>
                                    <p class="mt-2 text-2xl font-semibold">{{ stats.avgSpeed }} km/h</p>
                                </div>
                            </div>

                            <!-- Ostatnie aktywności -->
                            <div class="p-4 bg-white shadow rounded-lg">
                                <h3 class="text-lg font-semibold">Ostatnie aktywności</h3>
                                <p class="mt-2 text-gray-500">Brak aktywności – dodamy je później.</p>
                            </div>
                            <!-- Wykres -->
                            <div class="p-4 bg-white shadow rounded-lg">
                                <h3 class="text-lg font-semibold">Wykres aktywności</h3>
                                <div class="mt-4 h-64 flex items-center justify-center text-gray-400">
                                    (wykres pojawi się tutaj)
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
