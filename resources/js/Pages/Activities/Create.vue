<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Button } from '@/components/ui/button';

const form = useForm({
    type: '',
    distance: '',
    duration: '',
    date: '',
    notes: '',
});

function submit() {
    form.post(route('activities.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <form class="w-full max-w-sm flex flex-col gap-4" @submit.prevent="submit">

            <div>
                <InputLabel>Typ aktywności</InputLabel>
                <select v-model="form.type" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="" disabled>Wybierz typ</option>
                    <option value="run">Bieg</option>
                    <option value="bike">Rower</option>
                    <option value="swim">Pływanie</option>
                </select>
            </div>

            <div>
                <InputLabel>Dystans (km)</InputLabel>
                <TextInput id="form-distance" type="number" v-model="form.distance" />
            </div>

            <div>
                <InputLabel>Czas trwania (min)</InputLabel>
                <TextInput id="form-duration" type="number" v-model="form.duration" />
            </div>

            <div>
                <InputLabel>Data</InputLabel>
                <TextInput id="form-date" type="date" v-model="form.date" />
            </div>

            <Button type="submit">Zapisz</Button>

        </form>
    </AuthenticatedLayout>
</template>
