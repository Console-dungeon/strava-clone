<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/Components/ui/dialog';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Spinner } from '@/Components/ui/spinner';

const DEV_CREDENTIALS = {
  email: import.meta.env.VITE_GARMIN_EMAIL ?? '',
  password: import.meta.env.VITE_GARMIN_PASSWORD ?? '',
};

const showModal = ref(false);

const form = useForm({
  email: '',
  password: '',
});

function submitImport() {
  form.post(route('garmin.import'), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
  });
}
</script>

<template>
  <Card>
    <CardContent class="flex flex-col items-center gap-4 py-16 text-center">
      <p class="text-muted-foreground text-lg font-medium">
        {{ $t('dashboard.connectTitle') }}
      </p>
      <p class="text-muted-foreground text-sm">
        {{ $t('dashboard.connectDescription') }}
      </p>
      <Button @click="showModal = true">
        {{ $t('dashboard.connectButton') }}
      </Button>
    </CardContent>
  </Card>

  <Dialog :open="showModal" @update:open="showModal = $event">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ $t('dashboard.garminModal.title') }}</DialogTitle>
        <DialogDescription>{{
          $t('dashboard.garminModal.description')
        }}</DialogDescription>
      </DialogHeader>

      <form class="space-y-4" @submit.prevent="submitImport">
        <div class="space-y-1">
          <Label>{{ $t('dashboard.garminModal.email') }}</Label>
          <Input
            v-model="form.email"
            type="email"
            autocomplete="off"
            :disabled="form.processing"
          />
          <p v-if="form.errors.email" class="text-destructive text-xs">
            {{ form.errors.email }}
          </p>
        </div>

        <div class="space-y-1">
          <Label>{{ $t('dashboard.garminModal.password') }}</Label>
          <Input
            v-model="form.password"
            type="password"
            autocomplete="off"
            :disabled="form.processing"
          />
        </div>

        <DialogFooter class="flex-row items-center">
          <Button
            type="button"
            variant="outline"
            size="sm"
            class="borde-2 mr-auto border-dashed border-pink-400 text-xs"
            :disabled="form.processing"
            @click="
              form.email = DEV_CREDENTIALS.email;
              form.password = DEV_CREDENTIALS.password;
            "
          >
            Dev 🚀
          </Button>
          <Button
            type="button"
            variant="outline"
            :disabled="form.processing"
            @click="showModal = false"
          >
            {{ $t('dashboard.garminModal.cancel') }}
          </Button>
          <Button type="submit" :disabled="form.processing">
            <Spinner v-if="form.processing" class="mr-2" />
            {{ $t('dashboard.garminModal.submit') }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
