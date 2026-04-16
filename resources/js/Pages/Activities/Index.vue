<script setup>
defineProps({
    activities: Array,
});
</script>

<template>
    <div class="py-12">
        <div class="mx-auto max-w-4xl space-y-4">
            <h1 class="text-2xl font-bold">Twoje aktywności</h1>

            <div v-if="activities.length === 0" class="text-gray-500">
                //placeholder
            </div>

            <div v-else class="space-y-2">
                <div
                    v-for="activity in activities"
                    :key="activity.id"
                    class="rounded-lg bg-white p-4 shadow"
                >
                    <p class="font-semibold">{{ activity.type }}</p>
                    <p>Dystans: {{ activity.distance }} km</p>
                    <p>Czas: {{ activity.duration }} min</p>
                    <p>Data: {{ activity.date }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
