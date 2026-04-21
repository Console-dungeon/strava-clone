<template>
  <img
    :src="isDark ? '/logo-darkmode.png' : '/logo.png'"
    alt="Logo"
    class="h-24 w-auto"
  />
</template>

<script setup lang="ts">
import { useDark } from '@vueuse/core';

const isDark = useDark();
</script>
