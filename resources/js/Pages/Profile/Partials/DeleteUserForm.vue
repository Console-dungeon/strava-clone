<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({ password: '' });

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;
  nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
  form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;
  form.clearErrors();
  form.reset();
};
</script>

<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-foreground text-lg font-medium">
        {{ t('profile.delete.title') }}
      </h2>
      <p class="text-muted-foreground mt-1 text-sm">
        {{ t('profile.delete.description') }}
      </p>
    </header>

    <Button variant="destructive" @click="confirmUserDeletion">{{
      t('profile.delete.button')
    }}</Button>

    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <div class="p-6">
        <h2 class="text-foreground text-lg font-medium">
          {{ t('profile.delete.modal.title') }}
        </h2>
        <p class="text-muted-foreground mt-1 text-sm">
          {{ t('profile.delete.modal.description') }}
        </p>

        <div class="mt-6">
          <InputLabel
            for="password"
            :value="t('profile.delete.modal.password')"
            class="sr-only"
          />
          <Input
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-3/4"
            :placeholder="t('profile.delete.modal.password')"
            @keyup.enter="deleteUser"
          />
          <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <Button variant="secondary" @click="closeModal">{{
            t('profile.delete.modal.cancel')
          }}</Button>
          <Button
            variant="destructive"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteUser"
          >
            {{ t('profile.delete.modal.confirm') }}
          </Button>
        </div>
      </div>
    </Modal>
  </section>
</template>
