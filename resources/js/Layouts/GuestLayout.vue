<script setup>
import { useDark } from '@vueuse/core';

useDark();
</script>

<template>
  <div
    class="bg-app flex min-h-screen flex-col items-center justify-center px-4"
  >
    <slot />
  </div>
</template>
