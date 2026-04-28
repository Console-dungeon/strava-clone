<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { t } = useI18n();

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
  <Head :title="t('create.pageTitle')" />

  <AuthenticatedLayout>
    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <Card class="mx-auto max-w-md">
          <CardHeader>
            <CardTitle>{{ t('create.title') }}</CardTitle>
          </CardHeader>
          <CardContent>
            <form class="flex flex-col gap-5" @submit.prevent="submit">
              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium">{{
                  t('create.type')
                }}</label>
                <select
                  v-model="form.type"
                  class="border-input focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                >
                  <option value="" disabled>
                    {{ t('create.selectType') }}
                  </option>
                  <option value="running">
                    {{ t('activities.types.running') }}
                  </option>
                  <option value="cycling">
                    {{ t('activities.types.cycling') }}
                  </option>
                  <option value="swimming">
                    {{ t('activities.types.swimming') }}
                  </option>
                </select>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-distance">{{
                  t('create.distance')
                }}</label>
                <Input
                  id="form-distance"
                  type="number"
                  step="0.01"
                  min="0"
                  v-model="form.distance"
                />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-duration">{{
                  t('create.duration')
                }}</label>
                <Input
                  id="form-duration"
                  type="number"
                  min="0"
                  v-model="form.duration"
                />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-date">{{
                  t('create.date')
                }}</label>
                <Input id="form-date" type="date" v-model="form.date" />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-notes">{{
                  t('create.notes')
                }}</label>
                <textarea
                  id="form-notes"
                  v-model="form.notes"
                  rows="3"
                  class="border-input focus-visible:border-ring focus-visible:ring-ring/50 w-full resize-none rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                  :placeholder="t('create.notesPlaceholder')"
                />
              </div>

              <Button type="submit" :disabled="form.processing" class="w-full">
                {{ t('create.submit') }}
              </Button>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
