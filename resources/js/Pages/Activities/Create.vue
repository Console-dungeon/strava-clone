<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { t } = useI18n();

type Tab = 'manual' | 'gpx';
const activeTab = ref<Tab>('manual');

const manualForm = useForm({
  type: '',
  distance: '',
  duration: '',
  date: '',
  notes: '',
});

const gpxForm = useForm({
  gpx_file: null as File | null,
  type: '',
  notes: '',
});

function submitManual() {
  manualForm.post(route('activities.store'));
}

function submitGpx() {
  gpxForm.post(route('activities.import-gpx'), {
    forceFormData: true,
  });
}

function onFileChange(e: Event) {
  const input = e.target as HTMLInputElement;
  gpxForm.gpx_file = input.files?.[0] ?? null;
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

            <div class="mt-3 flex gap-2">
              <button
                class="rounded-md px-4 py-1.5 text-sm font-medium transition-colors"
                :class="
                  activeTab === 'manual'
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:text-foreground'
                "
                @click="activeTab = 'manual'"
              >
                {{ t('create.tabManual') }}
              </button>
              <button
                class="rounded-md px-4 py-1.5 text-sm font-medium transition-colors"
                :class="
                  activeTab === 'gpx'
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:text-foreground'
                "
                @click="activeTab = 'gpx'"
              >
                {{ t('create.tabGpx') }}
              </button>
            </div>
          </CardHeader>

          <CardContent>
            <!-- Formularz ręczny -->
            <form
              v-if="activeTab === 'manual'"
              class="flex flex-col gap-5"
              @submit.prevent="submitManual"
            >
              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium">{{
                  t('create.type')
                }}</label>
                <select
                  v-model="manualForm.type"
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
                <p
                  v-if="manualForm.errors.type"
                  class="text-destructive text-xs"
                >
                  {{ manualForm.errors.type }}
                </p>
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
                  v-model="manualForm.distance"
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
                  v-model="manualForm.duration"
                />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-date">{{
                  t('create.date')
                }}</label>
                <Input id="form-date" type="date" v-model="manualForm.date" />
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="form-notes">{{
                  t('create.notes')
                }}</label>
                <textarea
                  id="form-notes"
                  v-model="manualForm.notes"
                  rows="3"
                  class="border-input focus-visible:border-ring focus-visible:ring-ring/50 w-full resize-none rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                  :placeholder="t('create.notesPlaceholder')"
                />
              </div>

              <Button
                type="submit"
                :disabled="manualForm.processing"
                class="w-full"
              >
                {{ t('create.submit') }}
              </Button>
            </form>

            <!-- Import GPX -->
            <form
              v-else
              class="flex flex-col gap-5"
              @submit.prevent="submitGpx"
            >
              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="gpx-file">{{
                  t('create.gpxFile')
                }}</label>
                <input
                  id="gpx-file"
                  type="file"
                  accept=".gpx,.xml"
                  class="border-input text-foreground file:text-foreground focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:ring-[3px]"
                  @change="onFileChange"
                />
                <p
                  v-if="gpxForm.errors.gpx_file"
                  class="text-destructive text-xs"
                >
                  {{ gpxForm.errors.gpx_file }}
                </p>
                <p class="text-muted-foreground text-xs">
                  {{ t('create.gpxHint') }}
                </p>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium">{{
                  t('create.type')
                }}</label>
                <select
                  v-model="gpxForm.type"
                  class="border-input focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                >
                  <option value="">
                    {{ t('create.gpxTypeAuto') }}
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
                <p class="text-muted-foreground text-xs">
                  {{ t('create.gpxTypeHint') }}
                </p>
                <p v-if="gpxForm.errors.type" class="text-destructive text-xs">
                  {{ gpxForm.errors.type }}
                </p>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-sm font-medium" for="gpx-notes">{{
                  t('create.notes')
                }}</label>
                <textarea
                  id="gpx-notes"
                  v-model="gpxForm.notes"
                  rows="3"
                  class="border-input focus-visible:border-ring focus-visible:ring-ring/50 w-full resize-none rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                  :placeholder="t('create.notesPlaceholder')"
                />
              </div>

              <Button
                type="submit"
                :disabled="gpxForm.processing || !gpxForm.gpx_file"
                class="w-full"
              >
                {{
                  gpxForm.processing
                    ? t('create.gpxImporting')
                    : t('create.gpxImport')
                }}
              </Button>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
