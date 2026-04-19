<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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
  <Head title="Dodaj aktywność" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl leading-tight font-semibold text-gray-800">
        Straba - Dodaj aktywność
      </h2>
      student‑developed fitness app
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <Card class="mx-auto max-w-md">
          <CardHeader>
            <CardTitle>Nowa aktywność</CardTitle>
          </CardHeader>
          <CardContent>
            <form class="flex flex-col gap-5" @submit.prevent="submit">
              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium">Typ aktywności</label>
                <select
                  v-model="form.type"
                  class="border-input focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                >
                  <option value="" disabled>Wybierz typ</option>
                  <option value="run">Bieg</option>
                  <option value="bike">Rower</option>
                  <option value="swim">Pływanie</option>
                </select>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-distance"
                  >Dystans (km)</label
                >
                <Input
                  id="form-distance"
                  type="number"
                  step="0.01"
                  min="0"
                  v-model="form.distance"
                />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-duration"
                  >Czas trwania (min)</label
                >
                <Input
                  id="form-duration"
                  type="number"
                  min="0"
                  v-model="form.duration"
                />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-date">Data</label>
                <Input id="form-date" type="date" v-model="form.date" />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-notes"
                  >Notatka (opcjonalnie)</label
                >
                <textarea
                  id="form-notes"
                  v-model="form.notes"
                  rows="3"
                  class="border-input focus-visible:border-ring focus-visible:ring-ring/50 w-full resize-none rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                  placeholder="Dodaj notatkę..."
                />
              </div>

              <Button type="submit" :disabled="form.processing" class="w-full">
                Zapisz aktywność
              </Button>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
