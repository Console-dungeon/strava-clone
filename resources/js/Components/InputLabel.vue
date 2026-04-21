<script setup>
defineProps({
  value: {
    type: String,
  },
});
</script>

<template>
  <label class="text-foreground block text-sm font-medium">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </label>
</template>
